<?php
/**
 * User: Evan
 * Date: 5/20/2015
 * Time: 7:09 PM
 */
session_start();
require_once("../../headers/security_header.php");

$request = $_SERVER['REQUEST_METHOD'];
if( permission_level() >= $djland_permission_levels['member'] ) {
    switch($request){
        case "GET":
            //If it is a general "get" return all users. Else return info for a user.
            if(isset($_GET['id'])){
                $query = "SELECT * FROM membership WHERE id=:id";
            }else{

                $query = "SELECT m.id, m.firstname, m.lastname, m.email, m.member_type FROM membership as m INNER JOIN membership_years as my ON m.id=my.member_id GROUP BY m.id";
                if(isset($_GET['order_by'])){
                    $query.=" ORDER BY :order_by DESC";
                } 
            }
            $statement = $pdo_db->prepare($query);
            if(isset($_GET['id'])) {
                $statement->bindValue(':id', $_GET['id']);
            }
            if(isset($_GET['order_by'])) {
                $statement->bindValue(':order_by',$_GET['order_by']);
            }
            try {
                $statement->execute();
                echo json_encode($statement->fetchAll(PDO::FETCH_ASSOC));
            } catch (PDOException $e) {
                http_response_code(404);
                echo json_encode($e->getMessage());
            }
            break;
        case "POST":
        case "DELETE":
        case "PUT":
            http_response_code(405);
            echo json_encode("Not Allowed");
            break;
        default:
            http_response_code(404);
            echo json_encode($e->getMessage());
            break;
    }
}else{
    http_response_code(401);
    echo json_encode("You do not have permission");
}
?>