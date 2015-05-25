<?php
/**
 * User: Evan
 * Date: 5/4/2015
 * Time: 7:09 PM
 */

session_start();
require_once("../../headers/security_header.php");
if( permission_level() >= $djland_permission_levels['volunteer']) {
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
    } else {
        http_response_code(400);
        echo json_encode("No ID set");
    }
}else{
    http_response_code(401);
    echo json_encode("You do not have permission");
}
?>