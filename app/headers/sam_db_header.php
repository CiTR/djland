<?php

require_once("session_header.php");
require_once(dirname($_SERVER['DOCUMENT_ROOT']).'/config.php');

if($enabled['sam_integration']){
    global $sam_db;
    $sam_db['link'] = $mysqli_sam = new mysqli($sam_db['address'], $sam_db['username'], $sam_db['password'], $sam_db['database']);

    if (mysqli_connect_error()) {
        echo 'there is a connection error';
        die('Connect Error for sam db (' . mysqli_connect_errno() . ') '
                . mysqli_connect_error());
    }
}
