<?php
/**
 * Created by PhpStorm.
 * User: brad
 * Date: 3/27/15
 * Time: 4:50 PM
 */

session_start();

	require_once("../api_common_private.php");




  $show_data = $incoming_data;

$show_id = $show_data['show_id'];
unset($show_data['show_id']);

// TO DO: add host_id code

  $keys = array_keys($show_data);

  foreach ($keys as $i => $key){
    $keys[$i] .= ' =:'.$key;
  }

	$query = "UPDATE shows SET ".implode(', ',$keys);

	$query .= " WHERE id=".$show_id.";";

	$statement = $pdo_db->prepare($query);

	foreach($show_data as $key => $value){
    $statement->bindValue($key,$value);
  }
	try{
    $statement->execute();
  }catch(PDOException $e){
    echo $e->getMessage();
    header('HTTP/1.0 400 '.json_encode(array('message' => $e->getMessage())));
    return;
  }
echo json_encode(array('message' => 'show info saved successfully'));

?>