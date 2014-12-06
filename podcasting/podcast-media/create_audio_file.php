<?php
/**
 * Created by PhpStorm.
 * User: brad
 * Date: 11/18/14
 * Time: 3:25 PM
 */

header('access-control-allow-origin: *');

require_once('podcast-media-config.php');


date_default_timezone_set($timezone);

if(isset($_GET['start']) && isset($_GET['end']) && isset($_GET['show'])){

    $start = $_GET['start'];
    $end = $_GET['end'];
    $show = $_GET['show'];

    $start_date =  date('d-m-Y+G%3\Ai%3\As', $start);
    $end_date =  date('d-m-Y+G%3\Ai%3\As', $end);



    $archive_url = $archive_access_url.
        "&startTime=".
        $start_date.
//        "04-05-2014+01%3A02%3A03".
        "&endTime=".
        $end_date;
    //04-05-2014+01%3A12%3A03";


    $new_podcast_audio_file = file_get_contents($archive_url);

/*
    echo '<hr/>making URL request: '.$archive_url;
    echo '<hr/>start / end is <pre/>';
    print_r($start_date);
    echo '<br/>';
    print_r($end_date);
*/
    $audio_dir = 'audio/';
    $file_name = $show.'-'.$start.'-'.$end.'.mp3';

    $num_bytes = file_put_contents($audio_dir.$file_name,$new_podcast_audio_file);

    echo json_encode(['filename' => $file_name, 'size' => $num_bytes]);

    //'<hr/>done writing file!';
#echo $new_podcast_audio_file;

} else {

    echo "incorrect GET parameters have been supplied.  I need 'start', 'end', and 'show'.  'start' and 'end' must be unix timestamps";

}

