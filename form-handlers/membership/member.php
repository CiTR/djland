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
            }else{
                //Base multi member query
                $query = "SELECT m.id AS member_id, m.firstname, m.lastname, m.email, m.member_type FROM membership as m INNER JOIN membership_years as my ON m.id=my.member_id";
                //Check if filtering by year
                if(isset($_GET['year'])){
                    $query.=" WHERE my.membership_year=:year";
                }else{
                    $query.=" WHERE my.membership_year=(SELECT MAX(membership_year) FROM membership_years WHERE member_id = m.id)";
                } 
                //Group by member id so we don't have multiples of each member(per membership year)
                $query.=" GROUP BY m.id";
                //If chosen ordering is other than id, order by that. ie. Name, email etc.
                if(isset($_GET['order_by'])){
                    $query.=" ORDER BY :order_by DESC";
                }else{
                    $query.=" ORDER BY m.id DESC";
                }
            }
            $statement = $pdo_db->prepare($query);
            if(isset($_GET['id'])) {
                $statement->bindValue(':id', $_GET['id']);
            }
            if(isset($_GET['year'])){
                 $statement->bindValue(':year', $_GET['year']);
            }
            if(isset($_GET['order_by'])) {
                $statement->bindValue(':order_by',$_GET['order_by']);
            }
            try {
                $statement->execute();
                if(isset($_GET['id'])){
                    echo json_encode($statement->fetchObject());
                }else{
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                    echo json_encode($result);
                }
                
            } catch (PDOException $e) {
                http_response_code(500);
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
            http_response_code(400);
            echo json_encode($e->getMessage());
            break;
    }
}else{
    http_response_code(401);
    echo json_encode("You do not have permission");
}
?>