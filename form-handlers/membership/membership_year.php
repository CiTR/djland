<?php
/*
 * User: Evan
 * Date: 5/20/2015
 * Time: 7:09 PM
 */
include_once("../../headers/session_header.php");
require_once("../../headers/security_header.php");
$request = $_SERVER['REQUEST_METHOD'];
switch($request){
    case "GET":
        if (isset($_GET['member_id'])){
            //Create Query
            $query = "SELECT * FROM membership_years WHERE member_id=:member_id";  
            if(isset($_GET['year'])){
                $query.=" AND membership_year=:year";
            }
            $query.=" ORDER BY membership_year DESC";      
            
            //Prepare Statement 
            $statement = $pdo_db->prepare($query);
            
            //Bind variables to values in query
            $statement->bindValue(':member_id', $_GET['member_id']);
            if(isset($_POST['year'])){
                $statement->bindValue(':year', $_GET['year']);
            }

            //Try to execute statement
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
            echo json_encode("No member ID set");
        }
        break;
    case "POST":
        if(isset($_POST['member_id']) && isset($_POST['membership_year'])){

            //Create associative array from membership year object passed   
            $membership_year = json_decode($_POST['membership_year'],true);
            

            //Create Query
            $query = "UPDATE membership_years SET ";
            $keys = array_keys($membership_year);
            $end = end($keys);
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
            
            //Prepare Statement
            $statement = $pdo_db->prepare($query);
            
            //Bind variables to values in query
            $statement->bindValue(':member_id',$_POST['member_id']);   
            foreach($membership_year as $key => $value){
                    $statement->bindValue($key,$membership_year[$key]);     
            }

            //Try to execute statement
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
        //PHP doesn't handle PUT or DELETE.
        parse_str(file_get_contents('php://input'), $_PUT);
        if(isset($_PUT['member_id']) && isset($_PUT['membership_year'])){
            //Create associative array from membership year object passed
            $membership_year = json_decode($_PUT['membership_year'],true);
            
            //Create Query
            $query = "INSERT INTO membership_years SET ";
            $keys = array_keys($membership_year);
            $end = end($keys);
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
            
            //Prepare Statement
            $statement = $pdo_db->prepare($query);
            
            //Bind variables to values in query
            $statement->bindValue(":member_id",$_PUT['member_id']);
            foreach($membership_year as $key => $value){
                    $statement->bindValue($key,$membership_year[$key]);     
            }

            //Try to execute statement
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
?>