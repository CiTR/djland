<?php
/**
 * Created by PhpStorm.
 * User: Evan
 * Date: 5/12/2015
 * Time: 2:39 PM
 */
    include_once("../../headers/session_header.php");
require_once("../../headers/security_header.php");
$request = $_SERVER['REQUEST_METHOD'];
if( permission_level() >= $djland_permission_levels['member'] ) {
    switch($request){
        case "GET":
            //Create Query
            $select_query = "SELECT membership_year FROM year_rollover WHERE id='1'";
            
            //Prepare Statement
            $statement = $pdo_db->prepare($select_query);
           
            //Try to execute statement
            try{
                $statement->execute();

                //Create object and bind result to 'year' index
                $result = new stdClass();
                $result->year = $statement->fetchColumn(0);
                http_response_code(200);
                echo json_encode($result,JSON_UNESCAPED_SLASHES);
                exit();
            }catch(PDOException $pdoe){
                http_response_code(404);
                echo json_encode($e->getMessage());
                exit();
            }
            break;
        case "POST":
            if(isset($_POST['member_id']) && isset($_POST['membership_year'])){
                
                //Create associative array from membership year object passed  
                $membership_year = json_decode($_POST['membership_year'],true);
                
                //Create Query
                $query = "INSERT INTO membership_years SET member_id=:member_id,";
                $keys = array_keys($membership_year);
                $end = end($keys);
                foreach($membership_year as $key => $var){
                    //ignore primary keys id and membership_year
                    if($key != 'member_id') {
                        $query .= " ".$key." =:".$key;
                        //no comma on last entry
                        if($key != $end){
                            $query .= ",";
                        }
                    }
                }
                
                //Prepare Statement
                $statement = $pdo_db->prepare($query);


                //Bind variables to values in query
                $statement->bindValue(':member_id',$_POST['member_id']);
                foreach($membership_year as $key => $value){
                        $statement->bindValue(":".$key,$membership_year[$key]);     
                }

                //Try to execute statement
                try{
                    $statement->execute();
                    http_response_code(201);
                    echo json_encode(true);
                    exit();
                }catch(PDOException $pdoe){
                    http_response_code(404);
                    echo json_encode($pdoe->getMessage());
                    exit();
                }
            }else{
                http_response_code(400);
                echo json_encode("Missing member id or membership year");
                exit();
            }
            break;
        case "PUT":
            http_response_code(501);
            exit();           
        case "DELETE":
            http_response_code(501);
            exit();
       default:
            http_response_code(400);
            exit();
    }
}else{
    http_response_code(401);
    echo json_encode("You do not have permission");
}
?>