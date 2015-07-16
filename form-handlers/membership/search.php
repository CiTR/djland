<?php
/**
 * User: Evan
 * Date: 5/4/2015
 * Time: 7:09 PM
 */

require_once("../../headers/security_header.php");

//GET: Accepts search_by,value,paid,order
$request = $_SERVER['REQUEST_METHOD'];
if( permission_level() >= $djland_permission_levels['staff']) {
     switch($request){
        case "GET":
            $query = "SELECT m.id AS member_id, CONCAT(m.firstname,' ',m.lastname) AS name, m.email, m.primary_phone, m.member_type,m.comments FROM membership as m INNER JOIN membership_years as my ON my.member_id = m.id";  
            if(isset($_GET['search_by'])){
                switch($_GET['search_by']){
                    case 'name':
                        if($_GET['value'] != "" && isset($_GET['value'])){
                            $keywords = explode(" ",$_GET['value']);
                            $size = sizeof($keywords);
                            
                            //If only two words, assume it is first and last name.
                            if($size == 2){
                                $query.=" WHERE (m.firstname LIKE :value0 AND m.lastname LIKE :value1";
                            }else{
                            //Search for any combination of the words
                                for($i = 0; $i < $size; $i++){
                                    $query.= $i > 0 ? " OR" : " WHERE (";
                                       
                                    $query.= " m.lastname LIKE :value{$i} OR m.firstname LIKE :value{$i}";
                                }   
                            }
                            $query.=")";
                        }
                       
                        break;
                    case 'interest':
                        $query.="WHERE :value='1'";
                        break;
                    case 'member_type':
                        $query.="WHERE m.member_type=:value";
                        break;
                    default:
                        break;
                }
                $query.=" AND my.membership_year=:year ";
            }else{
                $query.=" WHERE my.membership_year=:year";

            }

           
            //Do we want all members, paid, or unpaid?
            if(isset($_GET['paid']) && ($_GET['paid'] != 'both')){
                $query.=" AND my.paid=:paid";
            }

            //How to order results   
            switch($_GET['order_by']){
                case 'firstname':
                    $query.=" ORDER BY m.firstname ASC";
                    break;
                case 'lastname':
                    $query.=" ORDER BY m.lastname ASC";
                    break;
                case 'member_type':
                    $query.=" ORDER BY m.member_type";
                    break;
                default:
                    $query.=" ORDER BY m.id DESC";
                    break;
            }

            //Prepare the statement
            $statement = $pdo_db->prepare($query);

            //Binding Variables
            if(isset($_GET['search_by']) && isset($_GET['value'])){
                switch($_GET['search_by']){
                    case 'name':
                        if($_GET['value'] != "" && isset($_GET['value'])){
                            $size = sizeof($keywords);
                            for($i = 0; $i<$size; $i++){
                                $statement->bindValue(':value'.$i, "%".$keywords[$i]."%");     
                            }
                        }
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
            $statement->bindValue(':year', $_GET['year']);
            if(isset($_GET['paid']) && ($_GET['paid'] != 'both')){
                $statement->bindValue(':value', $_GET['paid']);
            }
          
            //echo $statement->debugDumpParams();
            
            try {
                http_response_code(200);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($result);
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode($e->getMessage());
            }
            break;
        default:
            break;
    }
}else{
    http_response_code(401);
    echo json_encode("You do not have permission");
}
?>