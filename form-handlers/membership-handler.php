<?
require("../headers/db_header.php");
require("../headers/function_header.php");
if(isset($_POST['submenu_value'])){
	$submenu = $_POST['submenu_value'];
}

$query = "SELECT * FROM membership WHERE last_paid >= '2011' ORDER BY lastname DESC" ;
if($result = $db->query($query)){
	$members=array();
	while($row = mysqli_fetch_array($result)){
		$members[] = $row;
	}
}
echo json_encode($members);
$result->close();
?>