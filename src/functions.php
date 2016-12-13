<?php
  //Mode 0 = video upload
  //Mode 1 = document upload
  function upload_file($mode){
    //Setting up file variables
    $request    = Flight::request();
    $file       = $request->files['file'];
    $file_name  = $file['name'];
    $file_tmp   = $file['tmp_name'];
    $file_size  = $file['size'];
    $file_error = $file['error'];
    $max_size = 1000000000;
    $type = "video/mp4";
    $allowed;
    $target_dir;
    $json_file_path;
    //checking for double extension/php attacks
    $file_ext = explode('.', $file_name);
        echo implode(" ",$file_ext);
    if (count($file_ext) !== 2) {
      for($i=0;$i<sizeof($file_ext);$i++){
        if($file_ext[$i]==='php'){
          echo 'detected something';
          return 'detected php attack';
        }
      }
    }

    $file_ext = strtolower(end($file_ext));

    //setting variables depending on upload mode;
    if($mode===0){
      $allowed  = array('mp4','mov');
      $target_dir='../resources/videos/';
      $json_file_path = '../resources/json/videoList.json';
    }elseif($mode===1){
      $allowed = array('pdf');
      $target_dir='../resources/documents/';
      $json_file_path = '../resources/json/documentList.json';
      $max_size = 10000000;
      $type = "application/pdf";
    }


    //complete upload and updating corresponding json file
    if (in_array($file_ext, $allowed)) {

        if ($file_error === 0) {

            if ($file_size <= $max_size) {

                $file_name_new    = uniqid('', true) . '.' . $file_ext;
                $file_destination = $target_dir . $file_name_new;
                if (move_uploaded_file($file_tmp, $file_destination)) {
                    $filepath = $json_file_path;
                    $string   = file_get_contents($filepath);
                    $obj      = json_decode($string, true);


                    $tempFile["name"] = $file_name;
                    $tempfile["storageName"]=$file_name_new;
                    $tempFile["src"]  = $file_destination;
                    $tempFile["type"] = $type;

                    $obj[] = $tempFile;

                    $encodedJson = json_encode($obj);
                    file_put_contents($filepath, $encodedJson);
                }
            }
        }
    }

  }
