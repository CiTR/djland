<?php
/**
 * User: Evan
 * Date: 5/20/2015
 * Time: 7:09 PM
 */
session_start();
require_once("../../headers/security_header.php");
require_once("../../headers/password.php");

$request = $_SERVER['REQUEST_METHOD'];
if( permission_level() >= $djland_permission_levels['staff'] ) {
    switch($request){
        case "POST":
            //If it is a general "get" return all members. Else return info for a member.
            if(isset($_POST['member_id']) && isset($_POST['password'])){
            	if(trim($_POST['password']).length != 0){
                        
                    //Create Query
                    $insert_query = "UPDATE user SET password=:password WHERE id=(SELECT id FROM user WHERE member_id=:member_id)";

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
                    }catch(PDOException $pdoe){
                        http_response_code(404);
                        echo json_encode($e->getMessage());
                    }
                }else{
                http_response_code(204);
                    echo json_encode("No password Sent");
                }

            }else{
            	http_response_code(400);
                echo json_encode("Missing member id or password");
            }
         	break;
        case "GET":
        case "PUT":
        case "DELETE":
            http_response_code(405);
            echo json_encode("Not Allowed");
       		break;
       	default:
       		http_response_code(400);
            echo json_encode($e->getMessage());
       		break;
       	}
}