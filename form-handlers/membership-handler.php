<?
require_once("../headers/db_header.php");
require_once("../headers/function_header.php");
$action = null;
$type = null;
$value = null;
$paid = null;
$year = null;
$sort = null;
$to = null;
$from = null;
$default = false;
if(isset($_POST['action'])){
	$action = $_POST['action'];
	$type = $_POST['type'];
	if(isset($_POST['sort'])){
		$sort = $_POST['sort'];
	}
	if(isset($_POST['value']) && $_POST['value'] != null){
		$value = $_POST['value'];
	}
	if(isset($_POST['paid']) && $_POST['paid'] != null){
		$paid = $_POST['paid'];
	}
	if(isset($_POST['year']) && $_POST['year'] != null){
		$year = $_POST['year'];
	}
	if(isset($_POST['to']) && $_POST['to'] != null){
		$to = date("Y-m-d",strtotime($_POST['to']));
	}
	if(isset($_POST['from']) && $_POST['from'] != null){
		$from = date("Y-m-d",strtotime($_POST['from']));
	}
}

switch($action){
	case 'search':
		switch($type){
			case 'name':
				if($value !=  "" && $value != null){
					$query = "SELECT * FROM membership AS m INNER JOIN membership_years AS my ON my.member_id = m.id WHERE m.lastname LIKE '%".$value."%' OR m.firstname LIKE '%".$value."%'";
					if($year != 'all'){
						if($paid == 'both'){
 							$query.=" and my.membership_year='".$year."'";
						}else{
							$query.=" and my.membership_year='".$year."' and my.paid='".$paid."'";
						}
					}
				}else{
					$query = "SELECT * FROM membership AS m INNER JOIN membership_years AS my ON my.member_id = m.id";
					if($year != 'all'){
						if($paid == 'both'){
							$query.=" WHERE my.membership_year='".$year."'";
						}else{
							$query.=" WHERE my.membership_year='".$year."' and my.paid='".$paid."'";
						} 
					}
				}
				break;
			case 'interest':
				if($value != "" && $value != null){
					$query = "SELECT * FROM membership AS m INNER JOIN membership_years AS my ON m.id=my.member_id WHERE my.".$value."='1'";
					if($year != 'all'){
						if($paid == 'both'){
							$query .=" AND my.membership_year='".$year."'";
						}else{
							$query.= " AND my.membership_year='".$year."' AND my.paid='".$paid."'";
						}
					}
				}else{
					$query = "SELECT * FROM membership AS m INNER JOIN membership_years AS my ON m.id=my.member_id";
					if($year != 'all'){
						if($paid == 'both'){
							$query .=" AND my.membership_year='".$year."'";
						}else{
							$query.= " AND my.membership_year='".$year."' AND my.paid='".$paid."'";
						}
					}
				}
				if($from != null && $to != null){
						$query .=" AND m.joined >='".$from."' AND m.joined <='".$to."'";
					}
				break;
			default: //by default select all members
				$query = "SELECT * FROM membership AS m INNER JOIN membership_years AS my ON m.id = my.member_id";
				break;
		}
		if($sort == "id"){
			$query.=" GROUP BY m.id ORDER BY m.id DESC;";
		}else if($sort == 'lastname'){
			$query.=" GROUP BY m.id ORDER BY m.lastname ASC;";
		}else if($sort == 'email'){
			$query.=" GROUP BY m.id ORDER BY m.email ASC;";
		}
		
		
		break;
	case 'get':
		switch($type){
			case 'year': //get all possible years for all members
				$query = "SELECT DISTINCT membership_year FROM membership_years ORDER BY membership_year DESC";
				break;
			case 'member_year': //get all possible years for a member
				$query = "SELECT * FROM membership_years WHERE member_id='".$value."' ORDER BY membership_year DESC";
				break;
			case 'member_year_content': //get all possible years for a member
				$query = "SELECT * FROM membership_years WHERE member_id='".$value."' and membership_year='".$year."'ORDER BY membership_year DESC";
				break;
			case 'permission': //get permissions
				$query = "SELECT u.userid AS userid, u.username AS username ,gm.member AS member,gm.dj AS dj,gm.administrator AS administrator,gm.adduser AS adduser,gm.addshow AS addshow,gm.editdj AS editdj,gm.library AS library,gm.membership AS membership,gm.editlibrary AS editlibrary FROM user AS u INNER JOIN membership AS m on u.member_id=m.id INNER JOIN group_members AS gm on u.userid=gm.userid WHERE m.id='".$value."'";
				break;
			default:
				break;
		}
		break;
	case 'view': //View Member Page, get member information including password,username etc.
		switch($type){
			case 'init':
				$query = "SELECT * FROM membership INNER JOIN user ON membership.id = user.member_id WHERE membership.id='".$value."'";
				break;
			case 'year':
				$query = "SELECT * FROM membership_years WHERE member_id ='".$value."'";
				break;
			default:
				break;
		}
		break;
	case 'mail':
		switch($type){
			case 'interest':
				if($value != "" && $value != null){
					$query = "SELECT * FROM membership AS m INNER JOIN membership_years AS my ON m.id=my.member_id WHERE my.".$value."='1'";
					if($year != 'all'){
						if($paid == 'both'){
							$query .=" AND my.membership_year='".$year."'";
						}else{
							$query.= " AND my.membership_year='".$year."' AND my.paid='".$paid."'";
						}
					}
					
				}
			break;
		}	
	default:
		$default = true;
		break;
}
if(!$default){
	if($result = $db->query($query)){
		$members=array();
		while($row = mysqli_fetch_array($result)){
		$members[] = $row;
		}
	}
	echo json_encode($members);
	$result->close();
}

?>