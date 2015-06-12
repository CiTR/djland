<?php


require_once('api_common.php');
require_once('../../headers/security_header.php');

$incoming_data =  (array) json_decode(file_get_contents('php://input'));

