<?php
/**
 * Created by PhpStorm.
 * User: Evan
 * Date: 5/12/2015
 * Time: 2:39 PM
 */
session_start();
require_once("../../headers/security_header.php");

//POST Updates membership year a member is required to have in their membership_years set (with paid set to 1) to have access
//GET Gets the current mandatory membership year
if(isset($_POST) && isset($_POST['id']) && isset($_POST['membership_year'])){
    $membership_year = json_decode($_POST['membership_year'],true);
    $query = "INSERT INTO membership_years SET ";
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



    $update_query = "INSERT INTO membership_years SET membership_year=:year,  WHERE id=:id ON DUPLICATE KEY UPDATE";
    $statement = $pdo_db->prepare($update_query);
    $statement->bindValue(':year',$_POST['year']);
    $statement->bindValue(':id',$_POST['id']);
    try{
        $statement->execute();
        http_response_code(201);
        echo json_encode(true);
    }catch(PDOException $pdoe){
        http_response_code(404);
        echo json_encode($e->getMessage());
    }

}else if(isset($_GET)) {
    $select_query = "SELECT membership_year FROM year_rollover WHERE id='1'";
    $statement = $pdo_db->prepare($select_query);
    try{
        $statement->execute();
        $result = new stdClass();
        $result->year = $statement->fetchColumn(0);
        http_response_code(200);
        echo json_encode($result,JSON_UNESCAPED_SLASHES);


    }catch(PDOException $pdoe){
        http_response_code(404);
        echo json_encode($e->getMessage());
    }
}else {
    http_response_code(401);
    echo json_encode("You do not have permission");
}


?>