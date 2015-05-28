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
            //If it is a general "get" return all members. Else return info for a member.
            if(isset($_GET['member_id'])){
                $query = "SELECT m.id AS member_id, m.firstname AS firstname, m.lastname AS lastname, m.address AS address,m.province AS province, m.city AS city, m.postalcode AS postalcode, m.canadian_citizen AS canadian_citizen, m.alumni AS alumni, m.since AS since, m.is_new AS is_new, m.member_type AS member_type, m.joined AS joined, m.faculty AS faculty, m.schoolyear AS schoolyear, m.student_no AS student_no, m.integrate AS integrate, m.has_show AS has_show, m.show_name AS show_name, m.email AS email, m.primary_phone AS primary_phone, m.secondary_phone AS secondary_phone, m.about AS about, m.skills AS skills, m.exposure AS exposure, u.username AS username";
                if(permission_level() >= $djland_permission_levels['staff']){
                    $query.= " , m.comments as comments";
                    } 
                $query.=" FROM membership AS m INNER JOIN user AS u ON m.id = u.member_id WHERE m.id=:member_id";
                $statement = $pdo_db->prepare($query);
                $statement->bindValue(':member_id', $_GET['member_id']);
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
            //Input member ID , and member info to up update    
            if(isset($_POST['member_id']) && isset($_POST['member'])){
                $member = json_decode($_POST['member'],true);
                $query = "UPDATE membership SET ";
                $end = end(array_keys($member));
                foreach($member as $key => $var){
                    //ignore primary keys id and membership_year
                    if(($key != 'member_id' && $key != 'comments') || ($key == 'comments' && permission_level() >= $djland_permission_levels['staff'])) {
                        $query .= $key." =:".$key;
                        //no comma on last entry
                        if($key != $end){
                            $query .= ",";
                        }
                    }
                }
                $query.=" WHERE member_id=:member_id";
                $statement = $pdo_db->prepare($update_query);
                $statement->bindValue(":member_id",$_POST['member_id']);
                foreach($membership_year as $key => $value){
                        $statement->bindValue(":".$key,$member[$key]);     
                }
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
                echo json_encode("Missing member id or membership year");
            }
        case "DELETE":
            //Delete is its own API entry 
            break;
        case "PUT":
            //Input member ID , and member info to up update    
            if(isset($_POST['member'])){
                $member = json_decode($_POST['member'],true);
                $insert_query = "INSERT INTO membership SET ";
                $end = end(array_keys($member));
                foreach($member as $key => $var){
                    //ignore primary key
                    if( $key!='member_id'){
                        $query .= $key."=:".$key;
                        //no comma on last entry
                        if($key != $end){
                            $query .= ",";
                        }
                    }
                }
                $statement = $pdo_db->prepare($insert_query);
                foreach($member as $key => $value){
                        $statement->bindValue(":".$key,$member[$key]);     
                }
                try{
                    $statement->execute();
                    $member_id = $pdo_db->lastInsertId();
                    http_response_code(201);
                    echo json_encode($member_id);
                }catch(PDOException $pdoe){
                    http_response_code(404);
                    echo json_encode($e->getMessage());
                }
            }else{
                http_response_code(400);
                echo json_encode("Missing member information");
            }
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