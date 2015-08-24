
<?php

require_once('../headers/db_header.php');


$q = 'SELECT id,name from shows';

$r = mysqli_query($db,$q);

$q2 = 'SELECT id,title from podcast_channels';

$r2 = mysqli_query($db,$q2);
?>
<html>
    <head>
        <link rel='stylesheet' href='../js/bootstrap/bootstrap.min.css'></script>
    </head>
    <body>
        <a href='api/podcasting/connect-playlists-with-episodes.php'>Connect Playlists with Episodes</a>
        <table class='table-condensed table-hover'>
            <th> Show Name </th><th> Matching</th> <th> Outcome </th>
<?php
//Populate Podcast Channels
$pod_chans = array();
while($pod_chan = mysqli_fetch_array($r2)){

    $pod_chans []= $pod_chan;

}
//Go through Each show looking for matches against podcast channels
while ($shows = mysqli_fetch_array($r) ){
    $tr_out = "<tr>";
    $out = '<td>'.$shows['name'].'....</td>';

        $found = false;
        foreach($pod_chans as $i => $pod){

            $pod_name_arr = explode('-- ', $pod['title']);

            $pod_name = $pod_name_arr[count($pod_name_arr)-1];

            if (!$found && (levenshtein(html_entity_decode($shows['name']), $pod_name) <4)){//> (strlen($shows['name'])-2) ){

                $out.='<td>is the same as: ('.$pod['id'].') '.$pod['title'].'</td>';

                $found = true;

                $update_q = 'UPDATE shows SET podcast_channel_id = "'.$pod['id'].'" WHERE name = "'.$shows['name'].'"';

                if ($up_res = mysqli_query($db,$update_q)){
                    $out.='<td>.. updated the db</td>';
                } else {

                }

            }

        }
        if (!$found){
            $out.="<td>No Match Found</td>";
            $show_str_lower = strtolower($shows['name']);
            if(strpos($show_str_lower,'fill-in') !== false){
                    $query = "UPDATE shows SET shows.podcast_channel_id = '61' WHERE name = '".$shows['name']."'";
                    if ($up_res = mysqli_query($db,$update_q)){
                    $out.='<td>.. Caught Fill-In!</td>';
                    }else{
                        $out .='<td><h4>no podcast automatically found: '.$shows['name'].'</h4></td>';
                        $tr_out = "<tr class=warning>";
                    }
            }else{
                $out .='<td><h4>no podcast automatically found: '.$shows['name'].'</h4></td>';
                $tr_out = "<tr class=danger>";
            }  

            
        }
        $out.= "</tr>";
        echo $tr_out.$out;
}
?>
</table>
</body>
</html>
