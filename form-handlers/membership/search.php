<?php
/**
 * User: Evan
 * Date: 5/4/2015
 * Time: 7:09 PM
 */

session_start();
require_once("../../headers/security_header.php");

//GET: Accepts search_by,value,paid,order
if( permission_level() >= $djland_permission_levels['staff']) {
    $query = "SELECT m.id AS member_id, CONCAT(m.firstname,' ',m.lastname) AS name, m.email, m.primary_phone, m.member_type,m.comments FROM membership as m INNER JOIN membership_years as my ON my.member_id = m.id";  

    //Are we search_bying results? By: Name, Interest, Member_type
    if(isset($_GET['search_by']) && isset($_GET['value'])){
        $query .= " WHERE";
        switch($_GET['search_by']){
            case 'name':
                $query.=" m.lastname LIKE :value OR m.firstname LIKE :value";
                break;
            case 'interest':
                $query.=" :value='1'";
                break;
            case 'member_type':
                $query.=" m.member_type=:value";
                break;
            default:
                break;
        }
    }

    //Are we looking at a specific year? If we are, search_by by that. If not return most recent.
    if(isset($_GET['year']) && isset($_GET['search_by']) && isset($_GET['value'])){
        $query.=" AND membership_year=:year";
    }else if(isset($_GET['year'])){
        $query.=" WHERE membership_year=:year";
    }else{
        $query.=" WHERE my.membership_year=(SELECT MAX(membership_year) FROM membership_years WHERE member_id = m.id)";
    }
    //Do we want all members, paid, or unpaid?
    if(isset($_GET['paid']) && ($_GET['paid'] != 'both')){
        $query.=" AND my.paid=:paid";
    }
    //How to order results
    if(isset($_GET['order_by'])){
        $query.=" ORDER BY :order_by DESC";
    }else{
        $query.=" ORDER BY m.id DESC";
    }   
    $statement = $pdo_db->prepare($query);
    //Binding Variables
    if(isset($_GET['search_by']) && isset($_GET['value'])){
        $statement->bindValue(':search_by', $_GET['search_by']);
        switch($_GET['search_by']){
            case 'name':
               $statement->bindValue(':value', "%".$_GET['value']."%");
                break;
            case 'interest':
                $statement->bindValue(':value', "my.".$_GET['value']);
                break;
            case 'member_type':
                $statement->bindValue(':value', $_GET['value']);
                break;
            default:
                break;
        }
    }
    if(isset($_GET['year'])){
        $statement->bindValue(':year', $_GET['year']);
    }
    if(isset($_GET['paid']) && ($_GET['paid'] != 'both')){
        $statement->bindValue(':value', $_GET['paid']);
    }
    if(isset($_GET['order_by'])){
        $statement->bindValue(':order_by', "m.".$_GET['order_by']);
    }
    try {
        http_response_code(200);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode($e->getMessage());
    }
}else{
    http_response_code(401);
    echo json_encode("You do not have permission");
}
?>