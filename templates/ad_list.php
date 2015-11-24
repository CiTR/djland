<?php
	//This file is used with a POST request. It is not an angular style template

if(isset($_POST['ad_list']) ){
	$list = json_decode($_POST['ad_list']);
	$index = $_POST['index'];
	$num = $_POST['num'];
	$type = $_POST['type'];
}else{
	http_response_code(400);
	return "Ad list object required.";
}
	echo "<option value='any'>Any ".$type."</option>";
foreach($list as $item){
	echo "<option value='{$item->ID}'>{$item->title}</option>";
}
?>

