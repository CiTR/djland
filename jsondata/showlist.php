<?php

//header('content-type: application/json; charset=utf-8');
//header('Access-Control-Allow-Origin: http://www.citr.ca');

//require_once('/headers/db_header');
//require_once('/headers/function_header');
//print_r($fshow_name_active);
$db = new mysqli('p:192.168.25.73', 'playlist', '79bananas2013CAPS4evr', 'citr_live');
			if (mysqli_connect_error()) {
	    		print('Connect Error for citr db (' . mysqli_connect_errno() . ') '
	            . mysqli_connect_error());
			}
			
			
$shows = array();
$query = "SELECT id, name, active FROM shows ORDER BY name ASC";
if( $result = $db->query($query) ){
	while ($row = $result->fetch_assoc()){
	
		$shows []= $row;
	}

echo json_encode($shows);

} else {
echo 'error';
}