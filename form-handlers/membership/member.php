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
                
                //Create Query
                $query = "SELECT m.id AS member_id, m.firstname AS firstname, m.lastname AS lastname, m.address AS address,m.province AS province, m.city AS city, m.postalcode AS postalcode, m.canadian_citizen AS canadian_citizen, m.alumni AS alumni, m.since AS since, m.is_new AS is_new, m.member_type AS member_type, m.joined AS joined, m.faculty AS faculty, m.schoolyear AS schoolyear, m.student_no AS student_no, m.integrate AS integrate, m.has_show AS has_show, m.show_name AS show_name, m.email AS email, m.primary_phone AS primary_phone, m.secondary_phone AS secondary_phone, m.about AS about, m.skills AS skills, m.exposure AS exposure, u.username AS username";
                if(permission_level() >= $djland_permission_levels['staff']){
                    //Only staff can query staff comments
                    $query.= " , m.comments as comments";
                } 
                $query.=" FROM membership AS m INNER JOIN user AS u ON m.id = u.member_id WHERE m.id=:member_id";
                
                //Prepare statement
                $statement = $pdo_db->prepare($query);
                
                //Bind variables to values in query
                $statement->bindValue(':member_id', $_GET['member_id']);
                
                //Try to execute statement
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
                
                //Create associative array from membership year object passed  
                $member = json_decode($_POST['member'],true);
                
                //Create update_query
                $update_query = "UPDATE membership SET ";
                $keys = array_keys($member);
                $end = end($keys);
                foreach($member as $key => $var){
                    //ignore primary keys id and membership_year
                    if(($key != 'member_id' && $key != 'comments') || ($key == 'comments' && permission_level() >= $djland_permission_levels['staff'])) {
                        $update_query .= $key." =:".$key;
                        //no comma on last entry
                        if($key != $end){
                            $update_query .= ",";
                        }
                    }
                }
                $update_query.=" WHERE id=:member_id";
                
                //Prepare statement
                $statement = $pdo_db->prepare($update_query);
               
                //Bind variables to values in query
                $statement->bindValue(":member_id",$_POST['member_id']);
                foreach($member as $key => $value){
                        $statement->bindValue(":".$key,$member[$key]);     
                }

                //Try to execute statement
                try{
                    $statement->execute();
                    http_response_code(200);
                    echo json_encode(true);
                }catch(PDOException $pdoe){
                    http_response_code(404);
                    echo json_encode($pdoe->getMessage());
                }
            }else{
                http_response_code(400);
                echo json_encode("Missing member id or membership year");
            }
            break;
        case "DELETE":
            //PHP doesn't handle PUT or DELETE.
            parse_str(file_get_contents('php://input'), $_DELETE);
            //Only staff or above can delete a member
            if(permission_level() >= $djland_permission_levels['staff']){
                //Accepts List of member ID's to delete as an array
                if(isset($_DELETE['member_id'])){
                    
                    //Create Query
                    $query = "DELETE FROM membership WHERE id =:member_id";
                    
                    //Prepare statement
                    $statement = $pdo_db->prepare($query);
                    $result = true;

                    //Bind variables to values in query
                    $statement->bindValue(':member_id',$_DELETE['member_id']);
                    
                    //Try to execute statement
                    try{
                        //If statement fails, set result to false
                        if(!$statement->execute()){
                            $result = false;
                        }
                    }catch(PDOException $e){
                        //Return error message if error occurs
                        $result = $e->getMessage();
                    }
                    
                    echo json_encode($result); 
                }else{
                    http_response_code(400);
                    echo json_encode("Missing member id to delete");
                }
            }else{
                  http_response_code(401);
                echo json_encode("You do not have permission");
            }
            
            break;
        case "PUT":
            //PHP doesn't handle PUT or DELETE.
            parse_str(file_get_contents('php://input'), $_PUT);
            //Input member ID , and member info to up update    
            if(isset($_PUT['member'])){
                
                //Create associative array from membership year object passed  
                $member = json_decode($_PUT['member'],true);
                
                //Create Query
                $insert_query = "INSERT INTO membership SET ";
                $keys = array_keys($member);
                $end = end($keys);
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

                //Prepare statement
                $statement = $pdo_db->prepare($insert_query);
                
                //Bind variables to values in query
                foreach($member as $key => $value){
                        $statement->bindValue(":".$key,$member[$key]);     
                }
                
                //Try to execute statement
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