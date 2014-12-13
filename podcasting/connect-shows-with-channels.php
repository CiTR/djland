
<?php

require_once('../headers/db_header.php');


$q = 'SELECT id,name from shows';

$r = mysqli_query($db,$q);

$q2 = 'SELECT id,title from podcast_channels';

$r2 = mysqli_query($db,$q2);

$pod_chans = array();

while($pod_chan = mysqli_fetch_array($r2)){

    $pod_chans []= $pod_chan;

}


    while ($shows = mysqli_fetch_array($r) ){

        echo $shows['name'].'....';

            $found = false;
            foreach($pod_chans as $i => $pod){

                $pod_name_arr = explode('-- ', $pod['title']);

                $pod_name = $pod_name_arr[count($pod_name_arr)-1];

                if (!$found && (levenshtein(html_entity_decode($shows['name']), $pod_name) <4)){//> (strlen($shows['name'])-2) ){

                    echo 'is the same as: ('.$pod['id'].') '.$pod['title'].'<br/>';

                    $found = true;

                    $update_q = 'UPDATE shows SET podcast_channel_id = "'.$pod['id'].'" WHERE name = "'.$shows['name'].'"';

                    if ($up_res = mysqli_query($db,$update_q)){
                        echo '<br/>.. updated the db';
                    } else {

                    }

                } else {



                }


            }
            if (!$found){
                echo '<h2>no podcast automatically found: '.$shows['name'].'</h2><br/><br/><br/>';
            }


        echo '<hr/>';


    }
