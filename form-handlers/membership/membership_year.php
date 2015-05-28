<?php
/*
 * User: Evan
 * Date: 5/20/2015
 * Time: 7:09 PM
 */
session_start();
require_once("../../headers/security_header.php");
$request = $_SERVER['REQUEST_METHOD'];
if(permission_level() >= $djland_permission_levels['member']) {
    switch($request){
        case "GET":
            if (isset($_GET['id'])){
                $query = "SELECT * FROM membership_years WHERE member_id=:id";  
                if(isset($_GET['year'])){
                    $query.=" AND membership_year=:year";
                }
                $query.=" ORDER BY membership_year DESC";      
                $statement = $pdo_db->prepare($query);
                $statement->bindValue(':id', $_GET['id']);
                if(isset($_POST['year'])){
                    $statement->bindValue(':year', $_GET['year']);
                }
                try {
                    $statement->execute();
                    http_response_code(200);
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                    $years = new stdClass();
                    foreach($result AS $key=>$value){
                        $years->$value['membership_year'] = $value;
                    }
                    echo json_encode($years);
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode($e->getMessage());
                }
            }else{
                http_response_code(400);
                echo json_encode("No ID set");
            }
            break;
        case "POST":
            if(isset($_PUT['member_id']) && isset($_PUT['membership_year'])){
                //Input member ID , and membership year to up update    
                $membership_year = json_decode($_POST['membership_year'],true);
                $query = "UPDATE membership_years SET ";
                $end = end(array_keys($membership_year));
                foreach($membership_year as $key => $var){
                    //ignore primary keys id and membership_year
                    if($key != 'id' && $key != 'membership_year') {
                        $query .= $key." =:".$key;
                        //no comma on last entry
                        if($key != $end){
                            $query .= ",";
                        }
                    }
                }
                $query .= " WHERE member_id=:id AND membership_year=:membership_year;";
                $statement = $pdo_db->prepare($query);
                foreach($membership_year as $key => $value){
                        $statement->bindValue($key,$membership_year[$key]);     
                }
                try{
                    if($statement->execute()) echo json_encode(true);
                }catch(PDOException $e){
                    echo $e->getMessage();
                }
            }else{
                http_response_code(400);
                echo json_encode("Missing member id or membership year");
            }
            break;
        case "DELETE":
            http_response_code(501);
            
            break;
        case "PUT":
            if(isset($_PUT['member_id']) && isset($_PUT['membership_year'])){
                $membership_year = json_decode($_POST['membership_year'],true);
                $query = "INSERT INTO membership_years SET ";
                $end = end(array_keys($membership_year));
                foreach($membership_year as $key => $var){
                    //ignore primary keys id and membership_year
                    if($key != 'membership_year') {
                        $query .= $key." =:".$key;
                        //no comma on last entry
                        if($key != $end){
                            $query .= ",";
                        }
                    }
                }
                $query .= " WHERE member_id=:member_id AND membership_year=:membership_year;";
                $statement = $pdo_db->prepare($query);
                $statement->bindValue(":member_id",$_PUT['member_id']);
                foreach($membership_year as $key => $value){
                        $statement->bindValue($key,$membership_year[$key]);     
                }
                try{
                    if($statement->execute()) echo json_encode(true);
                }catch(PDOException $e){
                    echo $e->getMessage();
                }    
            }else{
                http_response_code(400);
                echo json_encode("Missing member id or membership year");
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