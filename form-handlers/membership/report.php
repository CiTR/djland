<?php
/**
 * User: Evan
 * Date: 5/25/2015
 */
    include_once("../../headers/session_header.php");
require_once("../../headers/security_header.php");

$request = $_SERVER['REQUEST_METHOD'];
if( permission_level() >= $djland_permission_levels['staff']['level'] ) {
    switch($request){
        case "GET":
            //Get Permissions for a user
            if(isset($_GET['year'])){

                //Create Query
                $total = "SELECT count(m.id) AS report_total, SUM(m.member_type='Student') AS report_student,
                        SUM(m.member_type='Community') AS report_community,
                        SUM(m.member_type='Staff') AS report_staff FROM membership AS m INNER JOIN membership_years as my ON m.id = my.member_id WHERE my.membership_year=:year";
                $unpaid = "SELECT count(m.id) AS report_unpaid FROM membership AS m INNER JOIN membership_years as my ON m.id = my.member_id WHERE my.membership_year=:year AND paid='0'";
                $query = "SELECT
                        count(m.id) AS report_paid,
                        SUM(m.alumni = '1') AS alumni";

                foreach($djland_interests AS $interest){
                    if($interest !='other') $query.=" ,CASE WHEN SUM(my.{$interest} = '1') IS NULL THEN 0 ELSE SUM(my.{$interest} = '1') END AS report_{$interest}";
                    else $query.=" ,CASE WHEN SUM(my.{$interest}) IS NULL THEN 0 ELSE SUM(my.{$interest} IS NOT NULL) END AS report_{$interest}";
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
                    $report->report_unpaid = $result_unpaid[0]['report_unpaid'];
                    foreach($result_query[0] AS $key=>$value){
                        $report->$key = $value;
                    }
                    echo json_encode($report);
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode($e->getMessage());
                    exit();
                }
            }else{
                http_response_code(400);
                echo json_encode("Missing member id");
                //exit();
            }
            break;
        case "POST":
            http_response_code(501);
            exit();
        case "PUT":
            http_response_code(501);
            exit();
        case "DELETE":
            http_response_code(501);
            exit();
        default:
            http_response_code(400);

            break;
            //exit();
    }
}else{
    http_response_code(401);
    echo json_encode("You do not have permission");
}
?>
