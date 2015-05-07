<?php
global $djland_permission_levels;
//Start session to ensure users have a session
session_start();
require_once("../headers/security_header.php");

//Only staff can delete members!
if(permission_level() >= $djland_permission_levels['staff']){
    //Accepts List of member ID's to delete as an array
    $ids = array();
    if (isset($_POST['ids'])) {
        $ids = json_decode($_POST['ids'],true);
    }
    //Prepare PDO statement
    $query = "DELETE FROM membership WHERE id =:id";
    $statement = $pdo_db->prepare($query);
    $result = true;

    //For each member we are deleting, bind their id to our statement and execute
    foreach($ids as $id){
        $statement->bindValue(':id',$id);
        try{
            //If a statement fails, set result to false
            if(!$statement->execute()){
                $result = false;
            }
        }catch(PDOException $e){
            //Return error message if error occurs
            $result = $e->getMessage();
        }
    }
    echo json_encode($result);
}else{
    echo "you do not have permission to access this page";
}