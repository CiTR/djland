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
                $query = "SELECT m.id AS member_id, m.firstname AS firstname, m.lastname AS lastname, m.address AS address,m.province AS province, m.city AS city, m.postalcode AS postalcode, m.canadian_citizen AS canadian_citizen, m.alumni AS alumni, m.since AS since, m.is_new AS is_new, m.member_type AS member_type, m.joined AS joined, m.faculty AS faculty, m.schoolyear AS schoolyear, m.student_no AS student_no, m.integrate AS integrate, m.has_show AS has_show, m.show_name AS show_name, m.email AS email, m.primary_phone AS primary_phone, m.secondary_phone AS secondary_phone, m.about AS about, m.skills AS skills, m.exposure AS exposure, u.username AS username";
                if(permission_level() >= $djland_permission_levels['staff']){
                    $query.= " , m.comments as comments";
                    } 
                $query.=" FROM membership AS m INNER JOIN user AS u ON m.id = u.member_id WHERE id=:id";
                $statement = $pdo_db->prepare($query);
                $statement->bindValue(':id', $_GET['id']);
                
                try {
                    $statement->execute();
                        echo json_encode($statement->fetchObject());
                    
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode($e->getMessage());
                }
            }
           
            break;
        case "POST":
        case "DELETE":
        case "PUT":
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