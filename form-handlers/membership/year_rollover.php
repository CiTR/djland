<?php
/**
 * Created by PhpStorm.
 * User: Evan
 * Date: 5/6/2015
 * Time: 4:30 PM
 */
session_start();
require_once("../../headers/security_header.php");
if(isset($_POST['year'])){
    $year = $_POST['year'];
    //TODO: Possibly convert into a INSERT INTO xxxx SELECT statement?
    $select_query = "SELECT * FROM membership_years GROUP BY id ORDER BY membership_year DESC";
        $statement = $pdo_db->prepare($select_query);
    try {
        $statement->execute();
        $result=$statement->fetchAll(PDO::FETCH_ASSOC);
        foreach($result as $key=>$value){
            if($key =='membership_year'){
                $result[$key] = $year;
            }
        }
        $insert_query = "INSERT INTO membership_years (member_id, membership_year) VALUES (:member_id,:membership_year)";
        $statement2->$pdo_db->prepare($insert_query);
        foreach($result as $value){
            try {
                $statement2->execute(array($value.member_id,$year));
            }catch(PDOException $e){
                http_response_code(404);
                echo json_encode($e->getMessage());
            }
        }
        http_response_code(201);
        echo json_encode("true");


        echo json_encode($years);
    }catch( PDOException $e){
        http_response_code(404);
        echo json_encode($e->getMessage());
    }
}else{
    http_response_code(401);
    echo json_encode("You do not have permission");
}
?>