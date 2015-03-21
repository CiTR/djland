<?php

//building api endpoints: just populate $error if there's an error
// populate $data to return
// call finish()

require_once('../../headers/db_header.php');

date_default_timezone_set('America/Vancouver');

function finish(){

  global $error;
  global $data;
  global $query;
  global $db;

  if($error != ''){
    echo $error;
//    header('HTTP/1.0 400 '.$error);
  } else {

    if ( is_array($data) && sizeof($data) == 1 ) $data = $data[0];

    foreach($data as $i => $v){

      if( defined('ENT_HTML5')){

        if(!is_array($v)) $data[$i] = html_entity_decode(html_entity_decode($v, ENT_QUOTES),ENT_HTML5);

      } else {

        if(!is_array($v)) $data[$i] = html_entity_decode($v, ENT_QUOTES);

      }
    }


//    echo $query.'<hr>';
//    echo '<pre>';
    header("Content-Type:application/json; charset=utf-8");

    if( defined('JSON_PRETTY_PRINT') ){
      echo json_encode( $data, JSON_PRETTY_PRINT );

    } else {
      echo json_encode( $data );
    }
  }

  mysqli_close($db);

}


// used to retreive podcast audio
$archive_tool_url = 'http://archive.citr.ca';
$archive_access_url = $archive_tool_url.
    "/py-test/archbrad/download?archive=%2Fmnt%2Faudio-stor%2Flog";

// use this to put files on freeNas - eg podcast audio (and xml??)
$ftp_url = '192.168.25.79';
$ftp_user = 'podcast';  $ftp_pass = 'podNAScast007';
//$ftp_user = 'root';  $ftp_pass = 'nas101.9';
$ftp_path = '/mnt/Audio/audio/';

