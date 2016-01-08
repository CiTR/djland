<?php 

include_once('../headers/db_header.php');

$query = "SELECT id FROM playsheets ORDER BY id";
$statement = $pdo_db->prepare($query);
$statement->execute();

$query_2 = "SELECT id,playsheet_id,position FROM playitems WHERE playsheet_id = :playsheet_id ORDER BY position ASC";
$statement_2 = $pdo_db->prepare($query_2);

$update_position = "UPDATE playitems SET position = :position WHERE id = :id";
$statement_3 = $pdo_db->prepare($update_position);

foreach($statement->fetchAll(PDO::FETCH_ASSOC) as $key=>$value){
	$statement_2->bindValue(":playsheet_id",$value['id']);
	$statement_2->execute();
	$position = 0;
	echo $value['id']." being updated <br/>";
	foreach($statement_2->fetchAll(PDO::FETCH_ASSOC) as $key=>$playitem){
		if($playitem['position'] == null){
			$statement_3->bindValue(":position",$position);
			$statement_3->bindValue(":id",$playitem['id']);
			try{
				$statement_3->execute();
			}catch(PDOexception $pdoe){
				echo $pdoe->getMessage()."<br/>";
			}
		}
		$position++;
	}
}