<?
require("../headers/db_header.php");
require("../headers/function_header.php");
$action = null;
$type = null;
$value = null;
$paid = null;
$year = null;
$default = false;
if(isset($_POST['action'])){
	$action = $_POST['action'];
	$type = $_POST['type'];
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
					$query = "SELECT * FROM membership INNER JOIN membership_years ON membership_years.member_id = membership.id WHERE membership.lastname LIKE '%".$value."%' OR membership.firstname LIKE '%".$value."%'";
					if($year != 'all'){
						if($paid == 'both'){
 							$query.=" and membership_years.membership_year='".$year."'";
						}else{
							$query.="and membership_years.membership_year='".$year."' and membership_years.paid='".$paid."'";
						}
					}
				}else{
					$query = "SELECT * FROM membership INNER JOIN membership_years ON membership_years.member_id = membership.id";
					if($year != 'all'){
						if($paid == 'both'){
							$query.=" WHERE membership_years.membership_year='".$year."'";
						}else{
							$query.=" WHERE membership_years.membership_year='".$year."' and membership_years.paid='".$paid."'";
						} 
					}
				}
				break;
			case interest:

				break;
			default: //by default select all members
				$query = "SELECT * FROM membership INNER JOIN membership_years ON membership.id = membership_years.member_id";
				break;
		}
		$query.=" GROUP BY membership.id ORDER BY lastname ASC";
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
				$query = "SELECT u.userid AS userid u.username AS username ,gm.member AS member,gm.dj AS dj,gm.administrator AS administrator,gm.adduser AS adduser,gm.addshow AS addshow,gm.editdj AS editdj,gm.library AS library,gm.membership AS membership,gm.editlibrary AS editlibrary FROM user AS u INNER JOIN membership AS m on u.member_id=m.id INNER JOIN group_members AS gm on u.userid=gm.userid WHERE m.id='".$value."'";
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