<?
require_once("../headers/db_header.php");
require_once("../headers/function_header.php");
$action = null;
$type = null;
$value = null;
$paid = null;
$year = null;
$sort = null;
$default = false;
if(isset($_POST['action'])){
	$action = $_POST['action'];
	$type = $_POST['type'];
	if(isset($_POST['sort'])){
		$sort = $_POST['sort'];
	}
	if(isset($_POST['value'])){
		$value = $_POST['value'];
	}
	if(isset($_POST['paid'])){
		$paid = $_POST['paid'];
	}
	if(isset($_POST['year'])){
		$year = $_POST['year'];
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
				break;
			default: //by default select all members
				$query = "SELECT * FROM membership AS m INNER JOIN membership_years AS my ON m.id = my.member_id";
				break;
		}
		if($sort == "id"){
			$query.=" GROUP BY m.id ORDER BY m.id DESC;";
		}else if($sort == 'lastname'){
			$query.=" GROUP BY m.id ORDER BY m.lastname ASC;";
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