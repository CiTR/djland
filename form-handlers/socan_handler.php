<?php

	session_start();
require_once("../headers/db_header.php");
require_once("../headers/function_header.php");

 

if(isset($_POST['from'])){
$from = $_POST['from'];
$to = $_POST['to'];

// echo "This is the value 'from' from _POST is: ".$from."<br>";
// echo "This is the value of 'to' from _POST is: ".$to."<br>";

$insert_from = date("Y-m-d H:i:s",strtotime($from));
$insert_to = date("Y-m-d H:i:s",strtotime($to));

//SELECT MAX(article) AS article FROM shop;

// $socan_query ="SELECT idSOCAN FROM socan ORDER BY idSOCAN DESC LIMIT 1";
// $result=mysqli_query($db,$socan_query);
$query="SELECT MAX(idSOCAN) FROM socan";
$result = mysqli_query($db,$query);
$row = mysqli_fetch_row($result);
$highest_id = $row[0];


// echo "Max ID value is: ".$highest_id."<br>";
$newID=$highest_id+1; 
$insert_query = "INSERT INTO socan (idSOCAN,socanStart,socanEnd) VALUES ('$newID','$insert_from','$insert_to')";
if(mysqli_query($db,$insert_query))
{ echo "New Socan Period Created! :) ";
echo "This period will Start: ".$insert_from." and End: ".$insert_to."<br><br>(Refresh the page to see it)"; }
else
{ echo "Something went wrong! :( Contact Technical Services"; }
}


?>