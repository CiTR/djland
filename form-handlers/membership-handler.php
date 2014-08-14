<?
require("../headers/db_header.php");
require("../headers/function_header.php");
$value = null;
$filter = null;

if(isset($_POST['value'])){
	$value = $_POST['value'];
	if(isset($_POST['filter'])){
		$filter = $_POST['filter'];
	}
}

switch($value){
	case 'search':
		if($filter!= null){
			$query = "SELECT * FROM membership WHERE lastname LIKE '%".$filter."%' OR firstname LIKE '%".$filter."%' ORDER BY lastname DESC";
		}
		else{
			$query = "SELECT * FROM membership ORDER BY lastname DESC";
		}
		break;
	default:
		break;
}




if($result = $db->query($query)){
	$members=array();
	while($row = mysqli_fetch_array($result)){
		$members[] = $row;
	}
}
echo json_encode($members);
$result->close();
?>