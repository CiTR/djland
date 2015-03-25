<?php


printf("<br/><br/><center><FORM METHOD=\"GET\" ACTION=\"%s\" name=\"the_form\">\n", $_SERVER['SCRIPT_NAME']);

printf("<INPUT type=hidden name=action value=edit>");
printf("<SELECT class='selectps' NAME=\"id\" SIZE=25>\n");
$get_playlists = "SELECT p.start_time AS start_time,p.id AS id, s.name AS name, p.star AS star, p.status AS status FROM playlists AS p INNER JOIN shows AS s ON s.id=p.show_id   ORDER BY start_time DESC LIMIT 500";
if($result = $db->query($get_playlists)){

  while($row = mysqli_fetch_array($result)){
    $time = date( 'Y: M j, g:ia' ,strtotime($row['start_time']));
    echo "<option value='".$row[id]."'>".$time." - ".$row[name].($row['status'] == 1 ? " - (draft)":"").($row["star"] == 1 ? " &#9733":"")."</option>";
  }
}

echo "</SELECT><BR><button TYPE=submit VALUE='View Playsheet' class='bigbutton' >View Playsheet</button>";
echo "<br/><br/><button type=submit name=socan value='true' >Load as SOCAN playsheet</button>";



if((is_member("addshow"))){

  echo "<br/><br/><button type=delete name=delete value=delete>delete selected playsheet</button>";
  echo '<br/><br/><a href="setSocan.php">Set a Socan Period Here</a>';

}
echo "</FORM></center>";

