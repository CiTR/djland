<?php
/**
 * Created by PhpStorm.
 * User: Evan
 * Date: 5/6/2015
 * Time: 4:30 PM
 */
require_once("../../headers/security_header.php");

//POST Updates membership year a member is required to have in their membership_years set (with paid set to 1) to have access
//GET Gets the current mandatory membership year
if(isset($_POST) && isset($_POST['year'])){
    $update_query = "UPDATE year_rollover SET membership_year=:year WHERE id='1'";
    $statement = $pdo_db->prepare($update_query);
    $statement->bindValue(':year',$_POST['year']);
    try{
        $statement->execute();
        http_response_code(201);
        echo json_encode(true);
    }catch(PDOException $pdoe){
        http_response_code(404);
        echo json_encode($pdoe->getMessage());
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
        echo json_encode($pdoe->getMessage());
    }
}else {
    http_response_code(401);
    echo json_encode("You do not have permission");
}
?>