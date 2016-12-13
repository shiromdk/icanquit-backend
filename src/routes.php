<?php
//This file contains the routes the web app uses
require 'functions.php';
require 'videostream.php';

Flight::route('/', function()
{
    Flight::render('index.php');
});

Flight::route('GET /management/documents', function(){
  $filepath = '../resources/json/documentList.json';
  $string   = file_get_contents($filepath);
  echo $string;
});

Flight::route('POST /management/documents', function(){
  upload_file(1);
});


Flight::route('GET /management/videos', function()
{
    $filepath = '../resources/json/videoList.json';
    $string   = file_get_contents($filepath);
    echo $string;
});

Flight::route('PUT /management/videos',function(){

});

Flight::route('POST /management/videos', function()
{
  upload_file(0);
});

Flight::route('GET /watch/@id', function($id){
  $file_path = '../resources/videos/' . $id . '.mp4';
  $stream = new VideoStream($file_path);
  $stream->start();
});
