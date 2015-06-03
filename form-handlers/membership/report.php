<?php
/**
 * User: Evan
 * Date: 5/25/2015
 */
session_start();
require_once("../../headers/security_header.php");

$request = $_SERVER['REQUEST_METHOD'];
if( permission_level() >= $djland_permission_levels['staff'] ) {
    switch($request){
        case "GET":
            //Get Permissions for a user
            if(isset($_GET['year'])){
                
                //Create Query
                $total = "SELECT count(m.id) AS total, SUM(m.member_type='Student') AS student, 
                        SUM(m.member_type='Community') AS community,
                        SUM(m.member_type='Staff') AS staff FROM membership AS m INNER JOIN membership_years as my ON m.id = my.member_id WHERE my.membership_year=:year";
                $unpaid = "SELECT count(m.id) AS unpaid FROM membership AS m INNER JOIN membership_years as my ON m.id = my.member_id WHERE my.membership_year=:year AND paid='0'";
                $query = "SELECT 
                        count(m.id) AS paid, 
                        SUM(m.alumni = '1') AS alumni";

                foreach($djland_interests AS $interest){
                    if($interest !='other') $query.=" ,SUM(my.{$interest} = '1') AS report_{$interest}";
                    else $query.=" ,SUM(my.{$interest} = '1') AS report_{$interest}";
                }
                $query .= " FROM membership AS m INNER JOIN membership_years as my ON m.id = my.member_id WHERE my.membership_year=:year AND paid='1'";
                        

               

                $get_total = $pdo_db->prepare($total);
                $get_total->bindValue(':year',$_GET['year']);

                $get_unpaid = $pdo_db->prepare($unpaid);
                $get_unpaid->bindValue(':year',$_GET['year']);
                
                $statement = $pdo_db->prepare($query);
                $statement->bindValue(':year',$_GET['year']);
               
                //Try to execute statement
                try {
                    $get_total->execute();
                    $result_total = $get_total->fetchAll(PDO::FETCH_ASSOC);

                    $get_unpaid->execute();
                    $result_unpaid = $get_unpaid->fetchAll(PDO::FETCH_ASSOC);

                    $statement->execute();
                    $result_query = $statement->fetchAll(PDO::FETCH_ASSOC);
                
                    http_response_code(200);
                    
                    $report = new stdClass();
                    foreach($result_total[0] AS $key=>$value){
                        $report->$key = $value;
                    }
                    $report->unpaid = $result_unpaid[0]['unpaid'];
                    foreach($result_query[0] AS $key=>$value){
                        $report->$key = $value;   
                    }
                    echo json_encode($report);
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode($e->getMessage());
                }
            }else{
                http_response_code(400);
                echo json_encode("Missing member id");
            }
            break;
        case "POST":
        case "PUT":            
        case "DELETE":
            http_response_code(405);
            echo json_encode("Not Allowed");
            break;
        default:
            http_response_code(400);
            echo json_encode($e->getMessage());
            break;
    }
}else{
    http_response_code(401);
    echo json_encode("You do not have permission");
}
?>