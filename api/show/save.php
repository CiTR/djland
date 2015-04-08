<?php
/**
 * Created by PhpStorm.
 * User: brad
 * Date: 3/27/15
 * Time: 4:50 PM
 */

	require_once("../api_common.php");

  session_start();

  $show_id = $show_data['show_id'];
  $show_data = $incoming_data;

  $show_data['genre'] = $show_data['secondary_genre_tags'];
  unset($show_data['secondary_genre_tags']);

  unset($show_data['show_id']);
  unset($show_data['last_show']);
  unset($show_data['show_id']);

// TO DO: add host_id code

  $keys = array_keys($show_data);

  foreach ($keys as $i => $key){
    $keys[$i] .= ' =: '.$key;
  }

	$query = "UPDATE shows SET ".implode(',',$keys);

	$query .= " WHERE id=".$show_id.";";

	$statement = $pdo_db->prepare($query);

	foreach($show_data as $key => $value){
    echo "\n statement->bind_param(".$key.",'".$value."'); Length of value = ".strlen($value);
    $statement->bindValue($key,$value);
  }
	try{
    $statement->execute();
  }catch(PDOException $e){
    echo $e->getMessage();
  }

?>

echo json_encode(array('message' => 'thanks for saving'));

?>