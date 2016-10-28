<?php

function run_sql_file($location,$db){
    //load file
    $commands = file_get_contents($location);

    //delete comments
    $lines = explode("\n",$commands);
    $commands = '';
    foreach($lines as $line){
        $line = trim($line);
        if( $line && !startsWith($line,'--')){
            $commands .= $line . "\n";
        }
    }

    //convert to array
    $commands = explode(";", $commands);

	$response = array();
    //run commands
    foreach($commands as $command){
		if(strlen($command) > 1){
			$total = $success = 0;
			$error = array();
			$result = ($db->query($command)==false ? 0 : 1);
	        $success += $result;
	        $total += 1;
			if($result == 0){
				$error[] = $db->error;
			}
			$response[] = array(
		        "success" => $success,
		        "total" => $total,
				"command"=> $command,
				"error" => $error
		    );
		}


    }

    //return number of successful queries and total number of queries found
    return $response;
}


// Here's a startsWith function
function startsWith($haystack, $needle){
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

function isSQL($file){
    return substr($file,-4,4) == '.sql' ? true : false;
}

?>
