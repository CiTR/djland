<?php


require("../headers/db_header.php");
require("../headers/function_header.php");


if(isset($_POST['id'])){
 $id=$_POST['id'];
 
$delete_query = "DELETE FROM socan WHERE idSOCAN = ".$id;
 echo $delete_query;
	if(mysqli_query($db,$delete_query))
	{echo "row".$id."deleted";}
 }
 
 ?>