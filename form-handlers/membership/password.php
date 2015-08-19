<?php
/**
 * User: Evan
 * Date: 5/20/2015
 * Time: 7:09 PM
 */
require_once("../../headers/security_header.php");
require_once("../../headers/password.php");

$request = $_SERVER['REQUEST_METHOD'];
if( permission_level() >= $djland_permission_levels['staff'] ) {
    switch($request){
        case "POST":
            //If it is a general "get" return all members. Else return info for a member.
            if(isset($_POST['member_id']) && isset($_POST['password'])){
            	if(sizeOf(trim($_POST['password'])) > 0){
                        
                    //Create Query
                    $insert_query = "UPDATE user SET password=:password WHERE member_id=:member_id";

                    //Prepare statement
                    $statement = $pdo_db->prepare($insert_query);
                    
                    //Bind variables to values in query
                    $statement->bindValue(":password",password_hash($_POST['password'],PASSWORD_DEFAULT));     
                    $statement->bindValue(":member_id",$_POST['member_id']);
                    //Try to execute statement
                    try{
                        $statement->execute();
                        http_response_code(201);
                        echo json_encode(true);
                        exit();
                    }catch(PDOException $pdoe){
                        http_response_code(400);
                        echo json_encode($pdoe->getMessage());
                        exit();
                    }
                }else{
                    http_response_code(204);
                    echo json_encode("No password Sent");
                    exit();
                }

            }else{
            	http_response_code(400);
                echo json_encode("Missing member id or password");
                exit();
            }
         	break;
        case "POST":
            http_response_code(501);
            exit();
        case "PUT":
            http_response_code(501);
            exit();           
        case "DELETE":
            http_response_code(501);
            exit();
        default:
            http_response_code(400);
            exit();
       	}
}