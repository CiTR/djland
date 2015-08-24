<?php
/**
 * Created by PhpStorm.
 * User: Evan
 * Date: 5/4/2015
 * Time: 1:09 PM
 */
include_once("../../headers/session_header.php");
require_once("../../headers/security_header.php");
$request = $_SERVER['REQUEST_METHOD'];

if( permission_level() >= $djland_permission_levels['member'] ) {
    switch($request){
        case "GET":
            
            // If a member ID is passed in, return the membership years associated with that member
            if(isset($_GET['member_id'])){
                //Create Query  
                $query = "SELECT membership_year AS year FROM membership_years WHERE member_id=:member_id ORDER BY membership_year DESC";
                
                //Prepare statement
                $statement = $pdo_db->prepare($query);

                //Bind variables to values in query
                $statement ->bindValue(':member_id',$_GET['member_id']);
            }
            //ELSE return all possible years between all members
            else{
                //Create Query  
                $query = "SELECT membership_year AS year FROM membership_years GROUP BY membership_year ORDER BY membership_year DESC";
                
                //Prepare statement
                $statement = $pdo_db->prepare($query);
            }        
            
            //Try to execute statement
            try {
                $statement->execute();
                $result=$statement->fetchAll(PDO::FETCH_NUM);
                foreach($result as $key=>$value){
                    $values[]=$result[$key][0];
                }
               
                //Create object and bind result to 'years' index
                $years = new stdClass();
                $years->years=$values;
                echo json_encode($years);
            }catch( PDOException $e){
                http_response_code(404);
                echo json_encode($e->getMessage());
            }
            break;
        case "POST":
            http_response_code(501);
            break;
        case "PUT":
            http_response_code(501);
            break;
        case "DELETE":
            http_response_code(501);
            break;
        default:
            http_response_code(400);
            echo json_encode("REQUEST METHOD INVALID");
            break;
    }
}else{
    http_response_code(401);
    echo json_encode("You do not have permission");
}
    if(isset($_POST)){
        
    }else{
        http_response_code(401);
        echo json_encode("You do not have permission");
    }
?>