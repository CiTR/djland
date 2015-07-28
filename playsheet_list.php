
<center><FORM METHOD=\"GET\" ACTION=\"<?php echo $_SERVER['SCRIPT_NAME'] ?>\" name=\"the_form\">

<INPUT type=hidden name=action value=edit>

<?php
$get_playsheets = "SELECT p.start_time AS start_time,p.id AS id, s.name AS name, p.star AS star, p.status AS status FROM playsheets AS p INNER JOIN shows AS s ON s.id=p.show_id ORDER BY start_time DESC LIMIT 500";

$statement = $pdo_db->prepare($get_playsheets);
try{
	$statement->execute();
	echo "excecuted";
}catch(PDOException $pdoe){
	echo "PDO Exception:".$pdoe->getMessage();
}

if($result = $db->query($get_playsheets)){
	echo "<SELECT class='selectps' NAME=\"id\" SIZE=25>";
  	while($row = mysqli_fetch_array($result)){
	    $time = date( 'Y: M j, g:ia' ,strtotime($row['start_time']));
	    echo "<option value='".$row[id]."'>".$time." - ".$row[name].($row['status'] == 1 ? " - (draft)":"").($row["star"] == 1 ? " &#9733":"")."</option>";
    
  	}
  	echo "</SELECT><BR><button TYPE=submit VALUE='View Playsheet' class='bigbutton' >View Playsheet</button>";
	echo "<br/><br/><button type=submit name=socan value='true' >Load as SOCAN playsheet</button>";

}else{
	echo "failed to query";
}





if(permission_level() >= $djland_permission_levels['workstudy']){

  echo "<br/><br/><button type=delete name=delete value=delete>delete selected playsheet</button>";
  echo '<br/><br/><a href="setSocan.php">Set a Socan Period Here</a>';

}
?>
</FORM></center>

