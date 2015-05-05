<?php
/**
 * Created by PhpStorm.
 * User: Evan
 * Date: 5/4/2015
 * Time: 1:09 PM
 */

session_start();
require_once("../../headers/security_header.php");
    if(isset($_POST['id'])){
        $query = "SELECT membership_year AS year FROM membership_years WHERE member_id=:id ORDER BY membership_year DESC";
        $statement = $pdo_db->prepare($query);
        $statement ->bindValue(':id',$_POST['id']);
        try {
            $statement->execute();
            $result=$statement->fetchAll(PDO::FETCH_NUM);
            foreach($result as $key=>$value){
                $values[]=$result[$key][0];
            }
            $years = new stdClass();
            $years->years=$values;
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