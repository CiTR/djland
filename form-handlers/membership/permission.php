<?php
/**
 * User: Evan
 * Date: 5/25/2015
 */
session_start();
require_once("../../headers/security_header.php");

$request = $_SERVER['REQUEST_METHOD'];
if( permission_level() >= $djland_permission_levels['staff'] ) {
    switch($request){
        case "GET":
            //Get Permissions for a user
            if(isset($_GET['member_id'])){
                $query = "SELECT u.userid AS userid, u.username AS username";
                foreach($djland_permission_levels as $level=>$value){
                    $query.=", gm.{$level} AS {$level}";
                }
                $query.=" FROM group_members AS gm INNER JOIN user AS u ON u.userid = gm.userid INNER JOIN membership AS m ON u.member_id = m.id WHERE m.id=:member_id";
                $statement = $pdo_db->prepare($query);
                $statement->bindValue(':member_id', $_GET['member_id']);
                
                try {
                    $statement->execute();
                    http_response_code(200);
                    echo json_encode($statement->fetchObject());
                    
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode($e->getMessage());
                }
            }else{
                http_response_code(400);
                echo json_encode("Missing member id");
            }
            break;
        case "POST":
            //Update Existing permissions
            if(isset($_POST['member_id'])){
                $query = "UPDATE group_members SET ";
                $end = end(array_keys($djland_permission_levels));
                foreach($djland_permission_levels as $level => $value){
                    $query .= $key." =:".$key;
                    //no comma on last entry
                    if($key != $end){
                        $query .= ",";
                    }
                }
                $query.=" WHERE user_id=(SELECT u.id FROM user AS u INNER JOIN membership as m ON m.id = u.member_id WHERE m.id=:member_id)";
                $statement = $pdo_db->prepare($query);
                $statement->bindValue(':user_id', $_POST['user_id']);
                foreach($djland_permission_levels as $level=>$value){
                    $statement->bindValue(':{$level}',$_POST[$level]);
                }
                
                try {
                    $statement->execute();
                    http_response_code(203);
                    echo json_encode(true);
                    
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode($e->getMessage());
                }
            }
            else{
                http_response_code(400);
                echo json_encode("Missing user id");
            }
            break;
        case "PUT":
            //Insert Permissions
            if(isset($_PUT['member_id'])){

                $query = "INSERT INTO group_members SET user_id=(SELECT u.id FROM user AS u INNER JOIN membership as m ON m.id = u.member_id WHERE m.id=:member_id)";
                foreach($djland_permission_levels as $level=>$value){
                    $query.=", {$level}=:{$level}";
                }
                $statement = $pdo_db->prepare($query);
                $statement->bindValue(':member_id', $_PUT['member_id']);
                foreach($djland_permission_levels as $level=>$value){
                    $statement->bindValue(':{$level}',$_PUT[$level]);
                }
                
                try {
                    $statement->execute();
                    http_response_code(201);
                    echo json_encode(true);
                    
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode($e->getMessage());
                }
            }else{
                http_response_code(400);
                echo json_encode("Missing user id");
            }
            break;
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