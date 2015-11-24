<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/headers/session_header.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/headers/security_header.php');



	$response = new stdClass();
if( !( isset($_POST['friend_name']) ) ){
	//Check if Show Name Set
	$response->response = 'No Friend Name Set';
	$response->ok = false;
	echo json_encode($response);
	http_response_code(400);
	exit();
}else if(!permission_level() >= $djland_permission_levels['dj']){
	//Check if user has permission to update show
	$response->response = 'You do not have permission';
	$response->ok = false;
	echo json_encode($response);
	http_response_code(401);
	exit();
}else{
	$base_dir = $_SERVER['DOCUMENT_ROOT']."/images/friends/";
	if(!file_exists($base_dir)){
		mkdir($base_dir,0755);
	}

	switch($_FILES['friendFile']['type']){
		case 'image/png':
		case 'png':
			$imageFileType = '.png';
			break;
		case 'image/jpeg':
		case 'jpeg':
			$imageFileType = '.jpeg';
			break;
		case 'image/jpg':
		case 'jpg':
			$imageFileType = '.jpg';
			break;
		case 'image/gif':
		case 'gif':
			$imageFileType = '.gif';
			break;
		default:
			$imageFileType = 'null';
			break;
	}

	$uploadOk = 0;
	//If Friend Directory doesn't exist make it and set permissions
	
	$strip = array('(',')',"'",'"','.',"\\",'/',',',':',';','@','#','$','%','&');
	$target_dir = str_replace(' ','_',$_SERVER['DOCUMENT_ROOT']."/images/friends/".str_replace($strip,'',$_POST['friend_name'])."/");
	if(!file_exists($target_dir)){
		mkdir($target_dir,0755);
	}

	//Create new filename (Friend Name + Todays Date)
	$today = date('Y-m-d-H-i-s');
	$target_file = $target_dir.str_replace(' ','_',str_replace($strip,'',$_POST['friend_name']))."-".$today.$imageFileType;
	$target_file_web_path = str_replace($_SERVER['DOCUMENT_ROOT'],"http://".$_SERVER['SERVER_NAME'],$target_file);
	// Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["friendFile"]["tmp_name"]);

    if($check !== false) {
        $response->response = "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        $response->response = "File is not an image.";
        $response->ok = false;
        http_response_code(415);
    }
	// Allow certain file formats
	if($imageFileType != ".jpg" && $imageFileType != ".png" && $imageFileType != ".jpeg" && $imageFileType != ".gif" ) {
	    $response->response = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
	    $response->ok = false;
	    http_response_code(415);
	}

	if($uploadOk == 0){
		//Return Reason for Error, Response Code should already set.
		echo json_encode($response);
		exit();
	}else{
		//Move File to directory 
		if (move_uploaded_file($_FILES["friendFile"]["tmp_name"], $target_file)) {
			//Attempt to set permissions to allow Apache Group to access it.
			if(chmod($target_file,0661)){
				$response->response = "The file ". basename( $_FILES["friendFile"]["name"]). " has been uploaded.";
				$response->ok = true;
                $response->path = $target_file;
                $response->web_path = $target_file_web_path;
			}else{
				$response->response = "Could not set permissions for file";
				$response->ok = false;
			}
	        
	    } else {
	        $response->response ="Sorry, there was an error uploading your file.";
	        $response->ok = false;
	    }
	    echo json_encode($response);
	}
}
?>