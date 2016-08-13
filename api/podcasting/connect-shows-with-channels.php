
<?php

require_once('../api_common.php');

$q = 'SELECT id,name, active from shows';

$r = mysqli_query($db['link'],$q);

$q2 = 'SELECT id,title from podcast_channels';

$r2 = mysqli_query($db['link'],$q2);
?>
<html>
    <head>
        <link rel='stylesheet' href='../../../js/bootstrap/bootstrap.min.css'></script>
    </head>
    <body>
        <a href='api/podcasting/connect-playsheets-with-episodes.php'>Connect Playlists with Episodes</a>
        <table class='table-condensed table-hover'>
            <th> Show Name </th><th> Matching</th> <th> Outcome </th>

<?php
$pod_chans = array();
$shows = array();

error_reporting(~E_WARNING);
while($pod_chan = mysqli_fetch_assoc($r2)){

    $pod_chans []= $pod_chan;

}
echo count($pod_chans).' podcast channels found.<br/>';


    while ($show_row = mysqli_fetch_assoc($r) ){


        $is_fillin = strpos(strtolower($show_row['name']),'fill-in')!==false;

        if(!$is_fillin)
        $shows []= $show_row;
    }
//    $shows = array_reverse($shows);
    foreach($shows as $i => $show){
        $tr_out = "<tr>";
        $out = '<td>'.$show['name'].'....</td>';

        $found = false;



        foreach($pod_chans as $j => $pod){


            $pod_name_arr = explode('-- ', $pod['title']);

            $pod_name = $pod_name_arr[count($pod_name_arr)-1];

            $this_show_name = html_entity_decode($show['name'], ENT_QUOTES);
            $this_show_name = str_replace("&amp;", '', $this_show_name);
            $this_show_name = str_replace("'", '', $this_show_name);
            $this_show_name = str_replace("the", '', $this_show_name);
            $this_show_name = str_replace("The", '', $this_show_name);
            $this_show_name = str_replace("The", '', $this_show_name);
            $this_show_name = str_replace(" ", '', $this_show_name);
            $this_show_name = str_replace("radio", '', $this_show_name);
            $this_show_name = str_replace("Radio", '', $this_show_name);
            $this_show_name = str_replace("and", '', $this_show_name);
            $this_show_name = str_replace("And", '', $this_show_name);
            $this_show_name = str_replace("&", '', $this_show_name);
            $this_show_name = str_replace("!", '', $this_show_name);

            $pod_name = html_entity_decode($pod_name, ENT_QUOTES);
            $pod_name = str_replace("&amp;", '', $pod_name);
            $pod_name = str_replace("'", '', $pod_name);
            $pod_name = str_replace("the", '', $pod_name);
            $pod_name = str_replace("The", '', $pod_name);
            $pod_name = str_replace("The", '', $pod_name);
            $pod_name = str_replace(" ", '', $pod_name);
            $pod_name = str_replace("Radio", '', $pod_name);
            $pod_name = str_replace("radio", '', $pod_name);
            $pod_name = str_replace("and", '', $pod_name);
            $pod_name = str_replace("And", '', $pod_name);
            $pod_name = str_replace("&", '', $pod_name);
            $pod_name = str_replace("!", '', $pod_name);


            if(isset($pod_name) && isset($this_show_name)) {
                $pos_1 = strpos($this_show_name, $pod_name);
                $pos_2 = strpos($pod_name, $this_show_name);
            }


            if (!$found && (
                        (levenshtein($this_show_name, $pod_name) < 3)|| $pos_1!==false || $pos_2!==false
//                        (strpos($this_show_name,$pod_name)) || (strpos($pod_name,$this_show_name))
                    )
                ){
                $out.='<td>is the same as: ('.$pod['id'].') '.$pod['title'].'</td>';

                //echo '<br/>'.$this_show_name.'(show # '.$show['id'].')  equals  '.$pod['title'].'(pod#'.$pod['id'].')<br/>';

                $found = true;

                $update_q = 'UPDATE podcast_channels SET show_id = "'.$show['id'].'" WHERE id = "'.$pod['id'].'"';

                unset($pod_chans[$j]);
                unset($shows[$i]);

                if ($up_res = mysqli_query($db['link'],$update_q)){
                    $out.='<td>.. updated the db</td>';
                } else {

                }

            } else {

            }



        }
        if (!$found){
           $out.="<td>No Match Found</td>";
           //echo '<h2>no podcast automatically found: '.$show['name'].'</h2><br/><br/><br/>';
            //echo $show['name'].' - not found.(show #'.$show['id'].')...<br/>';

            $show_str_lower = strtolower($show['name']);
            if(strpos($show_str_lower,'fill-in') !== false){
                    $query = "UPDATE podcast_channels SET podcast_channel.show_id = '284' WHERE id = '".$pod['id']."'";
                    if ($up_res = mysqli_query($db['link'],$query)){
                        $out.='<td>.. Caught Fill-In!</td>';
                    }else{
                         $out .='<td><h4>no podcast automatically found: '.$show['name'].' Setting show_id to 1</h4></td>';
                       $query = "Update podcast_channels SET show_id = 1  where show_id = 0";
                        if(mysqli_query($db['link'],$query)){
                            $tr_out = "<tr class=warning>";
                        }else{
                            $tr_out = "<tr class=danger>";
                        }
                    }
            }else{
                $out .='<td><h4>podcast not automatically found: '.$show['name'].' Setting show_id to 1</h4></td>';
                $query = "Update podcast_channels SET show_id = 1  where show_id = 0";
                if(mysqli_query($db['link'],$query)){
                    $tr_out = "<tr class=warning>";
                }else{
                    $tr_out = "<tr class=danger>";
                }
            }
            if($show['active'] == 0) {
                unset($shows[$i]);
            }
        }
        $out.= "</tr>";
        echo $tr_out.$out;
    }
    echo "</table>";
  /* $final_query = "
    UPDATE `podcast_channels` SET `show_id`='284' WHERE `id`='294';
    UPDATE `podcast_channels` SET `show_id`='154' WHERE `id`='290';
    UPDATE `podcast_channels` SET `show_id`='14' WHERE `id`='425';
    UPDATE `podcast_channels` SET `show_id`='284' WHERE `id`='320';
    UPDATE `podcast_channels` SET `show_id`='343' WHERE `id`='331';
    UPDATE `podcast_channels` SET `show_id`='294' WHERE `id`='393';
    UPDATE `podcast_channels` SET `show_id`='233' WHERE `id`='411';
    UPDATE `podcast_channels` SET `show_id`='183' WHERE `id`='442';";

    if(mysqli_query($db['link'],$final_query)){
        echo "<p>Successfully Updated Manually</p>";
    }else{
        echo "<p> Manual update failed </p>";
    }*/

?>

</body>
</html>
