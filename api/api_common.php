<?php

//building api endpoints: just populate $error if there's an error
// populate $data to return
// call finish()

require_once('../../headers/db_header.php');


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

      if(!is_array($v)) $data[$i] = html_entity_decode($v, ENT_QUOTES); // TODO add ENT_HTML5 additional surrounding function when on server running modern php
    }


//    echo $query.'<hr>';
//    echo '<pre>';
    header("Content-Type:application/json; charset=utf-8");
    echo json_encode( $data );
  }

  mysqli_close($db);

}