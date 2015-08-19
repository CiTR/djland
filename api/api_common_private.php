<?php
include_once("../../headers/session_header.php");
require_once('api_common.php');
require_once('../../headers/security_header.php');

$incoming_data =  (array) json_decode(file_get_contents('php://input'));
$incoming_data = json_decode(json_encode($incoming_data), true);

function update_row_in_table($tablename, $data, $id){
  global $pdo_db;
  global $error;

  if(array_key_exists('id',$data)) unset($data['id']);

  $keys = array_keys($data);

  foreach ($keys as $i => $key){
    $keys[$i] .= ' =:'.$key;
  }

  $query = "UPDATE ".$tablename." SET ".implode(', ',$keys);

  $query .= " WHERE id=".$id.";";

  $statement = $pdo_db->prepare($query);

  foreach($data as $key => $value){
    $statement->bindValue($key,$value);
  }
  try{
    $statement->execute();
  }catch(PDOException $e){
    $error .= $e->getMessage();
    return $error;
  }

  return 'save was successful';

}

function insert_row_in_table($tablename,$data){
  global $pdo_db;
  global $error;

  if(array_key_exists('id',$data)){
    $error .= ' key already exists in '.$tablename;
  }
  $keys = array_keys($data);

  $keys_prefixed = array();
  foreach($keys as $i => $key){
    $keys_prefixed []= ' :'.$key;
  }

  $query = 'INSERT INTO '.$tablename.'('.implode(', ',$keys).')'
        .' VALUES ('.implode(', ',$keys_prefixed).');';

  $statement = $pdo_db->prepare($query);

  foreach($data as $key => $value){
    $statement->bindValue($key,$value);
  }

  if ($error == ''){
    try {
      $statement->execute();
    } catch (PDOException $e){
      $error .= $e->getMessage();
      return $error;
    }
    return  $pdo_db->lastInsertId();
  } else {
    return false;
  }

}
