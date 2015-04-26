

<br/><br/>












<?php
echo "<div id=mainpodcast>
<a href='playsheet.php'><center>+ new podcast</center></a>
<br/><br/>
";

$get_playlists = "SELECT p.start_time AS start_time,
    p.id AS id,
    s.name AS name,
    p.star AS star,
    p.status AS status,
    p.podcast_episode as pe_id
    FROM playlists AS p
    INNER JOIN shows AS s
    ON s.id=p.show_id  ".
    " LEFT JOIN podcast_episodes as pe ON pe.id = p.podcast_episode ".
    is_numeric(users_show())?
    "WHERE show_id = ".users_show()." ORDER BY start_time DESC LIMIT 500"
    :
    " ORDER BY start_time DESC LIMIT 500"
    ;
if($result = $db->query($get_playlists)){

  while($row = mysqli_fetch_assoc($result)){
    $time = date( 'Y: M j, g:ia' ,strtotime($row['start_time']));
    echo "<a href='playsheet.php?action=edit&id=".$row[id]."'>".$time." - ".$row[name].($row['status'] == 1 ? " - (draft)":"").($row["star"] == 1 ? " &#9733":"")."</a>";
    if ($row['pe_id'] ){
      echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[<a href='podcast.php?id=".$row['pe_id']."'>edit podcast</a>]";
    }
    echo "<br/>";
  }
} else{
  echo mysqli_error($db)."<hr/>".$get_playlists;
}

if((is_member("addshow"))){

  echo "<br/><br/><button type=delete name=delete value=delete>delete selected playsheet</button>";
  echo '<br/><br/><a href="setSocan.php">Set a Socan Period Here</a>';

}
echo "</div>";

