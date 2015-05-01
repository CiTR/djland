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

    if ( is_array($data) && sizeof($data) == 1 ) $data = $data[0];

    $data = convertEntities($data);
/*
    foreach($data as $i => $v){

      if( defined('ENT_HTML5')){

        if(!is_array($v)) $data[$i] = html_entity_decode(html_entity_decode($v, ENT_QUOTES),ENT_HTML5);

      } else {

        if(!is_array($v)) $data[$i] = html_entity_decode($v, ENT_QUOTES);

      }
    }*/

    header('Access-Control-Allow-Origin: *',false);
    header("Content-Type:application/json; charset=utf-8",false);

    if( defined('JSON_PRETTY_PRINT') ){
      echo json_encode( $data, JSON_PRETTY_PRINT );

    } else {
      echo json_encode( $data );
    }
  }

  mysqli_close($db);

  return;
}

function convertEntities($data){
  if( is_string($data)){
    if( defined('ENT_HTML5')) {
       return html_entity_decode(html_entity_decode($data, ENT_QUOTES), ENT_HTML5);
    } else {
      return   html_entity_decode($data,ENT_QUOTES);
    }
  } else if(is_array($data)){
      foreach($data as $i => $v){
        $data[$i] = convertEntities($data[$i]);
      }
    }
  return $data;

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

function singleRowByID($table, $id){
  global $db;
  global $error;

  $q = 'SELECT * from '.$table.' where id ='.$id;
  if ($error == '' && $result = mysqli_query($db, $q)){
    return mysqli_fetch_assoc($result);
  } else {
    return false;
  }
}

function getIDbyRow($table,$array){
  global $db;
  global $error;

  $q = 'SELECT id from '.$table.' WHERE ';

  $q_values = array();

  foreach ($array as $key => $val ){
    $q_values [] = $key.' = "'.$val.'" ';
  }
  $q_values = implode(' AND ', $q_values);

  $q .= $q_values;

  if ($error == '' && $result = mysqli_query($db, $q)){
    if (mysqli_num_rows($result) == 0) {
      return false;
    }
    $row = mysqli_fetch_assoc($result);
    if (array_key_exists('id', $row)){
      return $row['id'];
    } else {
      $error .= 'key id not found ';
      return false;
    }

  }
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

