<?php
/**
 * Created by PhpStorm.
 * User: brad
 * Date: 4/28/15
 * Time: 3:32 PM
 */


require_once("../api_common_private.php");

$ads = $incoming_data['ads'];
$plays = $incoming_data['plays'];
$podcast = $incoming_data['podcast'];

unset($incoming_data['ads']);
unset($incoming_data['plays']);
unset($incoming_data['podcast']);

$podcast_id = $podcast['id'];
unset($podcast['id']);

$podcast['edit_date'] = date('Y-m-d H:i:s');
$podcast['date'] = date(DATE_RSS, strtotime($podcast['date']));

if ( $podcast_id != 0 && $podcast_id != '0'){
    update_row_in_table('podcast_episodes',$podcast, $podcast_id);

} else {
    $podcast_id = insert_row_in_table('podcast_episodes', $podcast);
}

$playlist = $incoming_data;

if(array_key_exists('id',$playlist)){
    $playlist_id = $playlist['id'];
    unset($playlist['id']);

    $delete_q = 'DELETE FROM playitems WHERE playsheet_id = '.$playlist_id;

    if($error=='' && $result = mysqli_query($db,$delete_q)){

    } else {
        $error .= 'could not delete plays before adding updated plays. ';
    }
} else {
    $playlist_id = -1;
}

$spokenword_hours = $playlist['spokenword_hours'];
$spokenword_min = $playlist['spokenword_minutes'];
unset($playlist['spokenword_hours']);
unset($playlist['spokenword_minutes']);
$playlist['spokenword_duration'] = 60*$spokenword_hours + $spokenword_min;

$playlist['start_time'] = date('Y-m-d H:i:s',strtotime($playlist['start_time'] ));
$playlist['end_time'] = date('H:i:s',strtotime($playlist['end_time'] ));

$playlist['podcast_episode'] = $podcast_id;


if($playlist_id == -1){
    $playlist['create_date'] = date("Y-m-d H:i:s", time());
    $playlist['create_name'] = array_key_exists('sv_username',$_SESSION)? $_SESSION['sv_username'] : 'unknown user';
    $playlist['edit_name'] = array_key_exists('sv_username',$_SESSION)? $_SESSION['sv_username'] : 'unknown user';
    $playlist_id = insert_row_in_table('playsheets',$playlist);
}
update_row_in_table('playsheets',$playlist,$playlist_id);

foreach($ads as $i => $ad){

    $ads[$i]['playsheet_id'] = $playlist_id;

    $ads[$i]['played'] = ($ad['played'])? 1 : 0;

    if (array_key_exists('id',$ad)) {
        $the_id = $ad['id'];
        unset($ads[$i]['id']);

        update_row_in_table('adlog', $ads[$i], $the_id);
    }
}

$plays = array_reverse($plays);

foreach($plays as $i => $play){

    foreach($play as $field => $value){

        if (strpos($field,'is_')===0 && ($value !== 1 && $value !== 0 && $value !== "0" && $value !== "1")){
            $play[$field] = ($value)? 1 : 0;
        }
    }

    if (array_key_exists('song', $play) && is_array($play['song'])){
        $song = $play['song'];
        unset($play['song']);

        if (array_key_exists('id', $song)){
            $songID = $song['id'];
            unset($song['id']);
        }

        if($song['composer'] =='') unset($song['composer']);

        if( $actual_song_id = getIDbyRow('songs', $song)){

            // song found, so use the existing ID instead!

            $play['song_id'] = $actual_song_id;

        } else {

            $play['song_id'] = insert_row_in_table('songs',$song);

        }
        if(array_key_exists('id',$play)) unset($play['id']);

        $play['playsheet_id'] = $playlist_id;

        if (array_key_exists('start', $play) ){
            unset($play['start']);
        }

        insert_row_in_table('playitems', $play);


    }
}

if ($error == ''){
    echo json_encode(array('playsheet_id' => $playlist_id, 'podcast_id' => $podcast_id));
} else {
    header('HTTP/1.0 400 '.json_encode(array('message' => $error)));
}

$episode_id = $episode_data['id'];