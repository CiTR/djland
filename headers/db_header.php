<?php
//DB HEADER
require_once('config.php');

//*******************************************
//*******************************************
//*******************************************

$db = new mysqli($djland_db_address, $djland_db_username, $djland_db_password, $djland_db_dbname);
			if (mysqli_connect_error()) {
	    		print('Connect Error for djland db (' . mysqli_connect_errno() . ') '
	            . mysqli_connect_error());
			}
			
// DJLAND's playsheet can be customized to link to a music library mySQL backend
// this provides the ability to easily add plays to a playsheet without typing
// actually, any digital media library / player that uses MySQL should work
// as long as the table names and column names are consistent with the code
// watch this space for a list of those table names in case you want to use a 
// different digital media player

if($using_sam){

    global $samDB_ip, $samDB_user, $samDB_pass, $samDB_dbname;
    $samDB_ip = 'ip address of computer running SAM mysql database';
    $samDB_user = 'mysql username of above mysql database with select, insert, etc priveleges';
    $samDB_pass = 'password for that user';
    $samDB_dbname = 'name of SAM table in the db (probably should be SAMDB)';


    $mysqli_sam = new mysqli($samDB_ip, $samDB_user, $samDB_pass, $samDB_dbname);

    if (mysqli_connect_error()) {
        echo 'there is a connection error';
        die('Connect Error for sam db (' . mysqli_connect_errno() . ') '
                . mysqli_connect_error());
    }
}
function mysqli_result_dep($res, $row, $field=0) {
//	echo 'called mysqli result';
//	echo '<br/>';
//	echo 'res:'.'<br/>';
//	print_r($res);
//	echo 'row:'.$row.'<br/>';
//	echo 'field:'.$field.'<br/>';
	if(is_object($res))    
		$res->data_seek($row); 
	else 	return false;
	
	$datarow = $res->fetch_array();
	
	if(is_array($datarow))
	    return $datarow[$field];
	else 	return false;
	        
} 

//END DB HEADER
?>