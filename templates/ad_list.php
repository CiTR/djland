<?php
	//This file is used with a POST request. It is not an angular style template

if(isset($_POST['ad_list']) ){
	$list = json_decode($_POST['ad_list']);
	$type = $_POST['type'];
	$value = $_POST['value'];
	$index = $_POST['index'];
	$num = $_POST['num'];
	
}else{
	http_response_code(400);
	return "Ad list object required.";
}
	if($type=='id') echo "<option value='You are listening to CiTR Radio 101.9FM, broadcasting from unceded Musqueam territory in Vancouver'>You are listening to CiTR Radio 101.9FM, broadcasting from unceded Musqueam territory in Vancouver</option>";
	else echo "<option value='Any ".ucfirst($type)."'>Any ".ucfirst($type)."</option>";
	
	foreach($list as $item){
		if($value == $item->title) echo "<option value='{$item->title}' selected>{$item->title}</option>";
		else echo "<option value='{$item->title}'>{$item->title}</option>";
	}
?>

