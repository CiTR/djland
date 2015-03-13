
<?php

require_once('../api_common.php');

$q = 'SELECT id,name, active from shows';

$r = mysqli_query($db,$q);

$q2 = 'SELECT id,title from podcast_channels';

$r2 = mysqli_query($db,$q2);

$pod_chans = array();
$shows = array();

error_reporting(~E_WARNING);
while($pod_chan = mysqli_fetch_assoc($r2)){

    $pod_chans []= $pod_chan;

}
echo count($pod_chans).' podcast channels found.<br/>';


    while ($show_row = mysqli_fetch_assoc($r) ){
        $is_fillin = strpos($show_row['name'],'Fill-in')!==false;

        if(!$is_fillin)
        $shows []= $show_row;
    }
//    $shows = array_reverse($shows);
    foreach($shows as $i => $show){

        $found = false;



        foreach($pod_chans as $j => $pod){

            if($pod['id'] == 43 && $show['id'] == '51'){
                xdebug_break();
            }

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
                        (levenshtein($this_show_name, $pod_name) <3)
                        || $pos_1!==false || $pos_2!==false
//                        (strpos($this_show_name,$pod_name)) ||
//                        (strpos($pod_name,$this_show_name))
                    )
                ){

                echo '<br/>'.$this_show_name.'(show # '.$show['id'].')  equals  '.$pod['title'].'(pod#'.$pod['id'].')<br/>';

                $found = true;

                $update_q = 'UPDATE shows SET podcast_channel_id = "'.$pod['id'].'" WHERE name = "'.$show['name'].'"';

                unset($pod_chans[$j]);
                unset($shows[$i]);

                if ($up_res = mysqli_query($db,$update_q)){} else {}

            } else {



            }


        }
        if (!$found){
//                echo '<h2>no podcast automatically found: '.$show['name'].'</h2><br/><br/><br/>';
            echo $show['name'].' - not found.(show #'.$show['id'].')...<br/>';
            if($show['active'] == 0) {
                unset($shows[$i]);
            }

        }




    }

echo '</hr><h3>';


echo count($shows).' currently active shows need to have a podcast channel id set manually: (discorder radio is also wrong)<br/></h3><pre>';
print_r($shows);


echo count($pod_chans).' podcast channels need to have a show set manually:<br/><pre>';
print_r($pod_chans);
