<?php
/**
 * User: Evan
 * Date: 6/10/2015
 */
session_start();
require_once("../../headers/security_header.php");

$request = $_SERVER['REQUEST_METHOD'];
if( permission_level() >= $djland_permission_levels['staff'] ) {
    switch($request){
        case "GET":
            //Get Permissions for a user
            if(isset($_GET['type']) && isset($_GET['value'])){
                $query = isset($_GET['from']) && isset($_GET['to']) ? "SELECT m.email FROM membership AS m INNER JOIN membership_years AS my ON m.id = my.member_id WHERE "
                

                switch($_GET['type']){
                    $query = "SELECT email"

                    case 'interest':
                        $query = 
                        break;
                    case 'member_type':
                        
                        break;
                    default:
                        http_response_code(400);
                        echo json_encode("Incorrect value given");
                        exit();
                        break
                }
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
                http_response_code(400);
                echo json_encode("Missing type of email list, or value");
            }
            break;
        case "POST":
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
}else{
    http_response_code(401);
    echo json_encode("You do not have permission");
}
?>