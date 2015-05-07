<?php
/**
 * Created by PhpStorm.
 * User: Evan
 * Date: 5/4/2015
 * Time: 7:09 PM
 */

session_start();
require_once("../../headers/security_header.php");
if( permission_level() >= $djland_permission_levels['volunteer']) {
    if (isset($_POST['id']) && isset($_POST['year'])) {
        $query = "SELECT * FROM membership_years WHERE member_id=:id AND membership_year=:year ORDER BY membership_year DESC";
        $statement = $pdo_db->prepare($query);
        $statement->bindValue(':id', $_POST['id']);
        $statement->bindValue(':year', $_POST['year']);
        try {
            $statement->execute();
            echo json_encode($statement->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            http_response_code(404);
            echo json_encode($e->getMessage());
        }
    } else {
        http_response_code(400);
        echo json_encode("Failed Post");
    }
}else{
    http_response_code(401);
    echo json_encode("You do not have permission");
}
?>