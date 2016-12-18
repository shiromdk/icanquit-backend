<?php

require('../vendor/autoload.php');
use Firebase\JWT\JWT;

function login(){
  $username = filter_input(INPUT_POST,'username',FILTER_SANITIZE_STRING);
  $password = filter_input(INPUT_POST,'password',FILTER_SANITIZE_STRING);

  //Check if both username and password exist;
  if($username && $password){
    $config_file_path = '../config.php';
    $config = include($config_file_path);


    if($username ==='admin'){
      if(password_verify($password,$config['admin'])){

        $tokenId = base64_encode(mcrypt_create_iv(32));
        $issuedAt = time();
        $notBefore = $issuedAt+ 10;
        $expire = $issuedAt + +7200;
        $serverName = $config['serverName'];

        $data = [
          'iat' => $issuedAt,
          'jti' => $tokenId,
          'iss' => $serverName,
          'nbf' => $notBefore,
          'exp' => $expire,
          'data' =>[
              'userName'=>$username,
              'userType'=>'admin'
          ]
        ];
        header('Content-type: application/json');
        $secretKey = $config['jwt']['key'];
        $algorithm = $config['jwt']['algorithm'];

        $jwt = JWT::encode(
          $data,
          $secretKey,
          $algorithm
        );
        $unencodedArray = ['jwt' => $jwt];
        echo json_encode($unencodedArray);
      }else{
        header('HTTP/1.0 401 Unauthorized');
      }
    }

    if($username ==='user'){
      if(password_verify($password,$config['admin'])){

        $tokenId = base64_encode(mcrypt_create_iv(32));
        $issuedAt = time();
        $notBefore = $issuedAt+ 10;
        $expire = $issuedAt + +7200;
        $serverName = $config['serverName'];

        $data = [
          'iat' => $issuedAt,
          'jti' => $tokenId,
          'iss' => $serverName,
          'nbf' => $notBefore,
          'exp' => $expire,
          'data' =>[
              'userName'=>$username,
              'userType'=>'user'
          ]
        ];
        header('Content-type: application/json');
        $secretKey = $config['jwt']['key'];
        $algorithm = $config['jwt']['algorithm'];

        $jwt = JWT::encode(
          $data,
          $secretKey,
          $algorithm
        );
        $unencodedArray = ['jwt' => $jwt];
        echo json_encode($unencodedArray);
      }else{
        header('HTTP/1.0 401 Unauthorized');
      }
    }
  } else {
        header('HTTP/1.0 400 Bad Request');
      }

}
