<?php

//building api endpoints: just populate $error if there's an error
// populate $data to return
// call finish()

error_reporting(0);

require_once('../../headers/db_header.php');

date_default_timezone_set('America/Vancouver');

$error = '';
$blame_request = false;

function finish(){

  global $error;
  global $blame_request;
  global $data;
  global $query;
  global $db;

  if($error != ''){
            mysqli_close($db);
            echo $error;
            if($blame_request){
              header('HTTP/1.0 400 '.$error);
            } else {
              header('HTTP/1.0 500' .$error);
            }
  } else {

    //if ( is_array($data) && sizeof($data) == 1 ) $data = $data[0];

    foreach($data as $i => $v){

      if( defined('ENT_HTML5')){

        if(!is_array($v)) $data[$i] = html_entity_decode(html_entity_decode($v, ENT_QUOTES),ENT_HTML5);

      } else {

        if(!is_array($v)) $data[$i] = html_entity_decode($v, ENT_QUOTES);

      }
    }

    header('Access-Control-Allow-Origin: *',false);
    header("Content-Type:application/json; charset=utf-8",false);

    if( defined('JSON_PRETTY_PRINT') ){
      echo utf8_json_encode( $data, JSON_PRETTY_PRINT );

    } else {
      echo utf8_json_encode( $data );
    }
  }

  mysqli_close($db);

  return;
}


function utf8_json_encode($arr, $option = false)
{
  //convmap since 0x80 char codes so it takes all multibyte codes (above ASCII 127). So such characters are being "hidden" from normal json_encoding
  array_walk_recursive($arr, function (&$item, $key) {
    if (is_string($item)) $item = mb_encode_numericentity($item, array(0x80, 0xffff, 0, 0xffff), 'UTF-8');
  });
  return mb_decode_numericentity(json_encode($arr, $option), array(0x80, 0xffff, 0, 0xffff), 'UTF-8');
}


function get_array($table, $idfield = 'id', $fields = 'basic'){
  global $_GET;
  global $db;
  global $error;

  if(isset($_GET['OFFSET'])) $offset = $_GET['OFFSET']; else $offset = 0;
  if(isset($_GET['LIMIT'])) $limit = $_GET['LIMIT']; else $limit = 100;

  if($fields == 'basic') {
    $query = 'SELECT ' . $idfield . ', edit_date FROM ' . $table . ' ORDER BY edit_date DESC limit ' . $limit . ' OFFSET ' . $offset;
  } else {
    $query = 'SELECT * FROM ' . $table . ' ORDER BY edit_date DESC limit ' . $limit . ' OFFSET ' . $offset;
  }
  $array = array();
  if ($result = mysqli_query($db, $query) ) {

    while ($row = mysqli_fetch_assoc($result)) {

      $array [] = $row;

    }
  } else {
    $error .= mysqli_error($db);
  }
  return $array;

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


$incoming_data =  (array) json_decode(file_get_contents('php://input'));

