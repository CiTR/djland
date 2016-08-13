<?php

require_once("../api_common_private.php");

if(isset($_GET['playsheet'])){
  echo load_ads_from_saved_playsheet($_GET['playsheet']);

} else if (isset($_GET['timeblock'])){
  $unix = strtotime($_GET['timeblock']);
  $data = load_ads_from_time_block($unix);

  foreach ($data as $i => $v){

    if (is_numeric($v['name'])){
      $ad_info = singleRowByIDFromSam('songlist',$v['name']);
      $data[$i]['name'] = $ad_info['artist'].' '.$ad_info['title'];
    }
  }
  finish();

} else {
  header('HTTP/1.0 400 '.'This page expects either a Playsheet get parameter or a Timeblock get parameter');
}


function load_ads_from_saved_playsheet($ps_id){

  if ($data = dbLoad('adLog', ['playsheet_id'], [$ps_id])){
    return json_encode($data);
  } else {
    global $db;
    header('HTTP/1.0 400 '.mysqli_error($db));
  }

}

function load_ads_from_time_block($unix){

  if ($data = dbLoad('adLog', ['time_block'], [$unix])){
    return $data;
  } else {
    global $db;
    header('HTTP/1.0 400 '.mysqli_error($db));
  }

}

function dbLoad($table, $fields, $values) {

  $query = "SELECT * FROM ".$table." WHERE ";
  $where_clauses = [];

  foreach($fields as $i => $field){
    $where_clauses []= $field." = '".$values[$i]."' ";
  }

  $query .= implode(',',$where_clauses);

  global $db;
  if( $result = mysqli_query($db['link'], $query)){
    $arr = [];
    while ( $row = $result->fetch_assoc()){
      $arr []= $row;
    }
    return $arr;
  } else return false;



}