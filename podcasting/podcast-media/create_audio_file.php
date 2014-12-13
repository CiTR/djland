<?php
/**
 * Created by PhpStorm.
 * User: brad
 * Date: 11/18/14
 * Time: 3:25 PM
 */

header('access-control-allow-origin: *');

    error_reporting(1);

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

    $audio_dir = 'audio/';
    $file_name = $show.'-'.$start.'-'.$end.'.mp3';

    if(strlen($new_podcast_audio_file)>0){
        $num_bytes = file_put_contents($audio_dir.$file_name,$new_podcast_audio_file);
    
        if ($num_bytes > 0){
                echo json_encode(['filename' => $file_name, 'size' => $num_bytes, 'start' => $start_date, 'end' => $end_date]);
            } else {
                echo json_encode(['error' => 'could not write to the podcast directory']);
            }
    } else {
        echo json_encode(['error' => 'cannot retrieve audio from archiver']);
    }
    //'<hr/>done writing file!';
#echo $new_podcast_audio_file;

} else {

    echo "incorrect GET parameters have been supplied.  I need 'start', 'end', and 'show'.  'start' and 'end' must be unix timestamps";

}

