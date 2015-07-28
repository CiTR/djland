<?php
define('PODCAST_LIMIT_HOURS',8);

require_once('../api_common.php');

error_reporting(E_ERROR);

if ( isset($_GET['start']) && isset($_GET['end']) && isset($_GET['show']) ){
  $start = $_GET['start'];
  $end = $_GET['end'];
  $show = $_GET['show'];

  $result = make_audio($start,$end,$show);

  if($error != ''){
    header('HTTP/1.0 400 '.$error);
  } else {
    echo json_encode($result);
  }

} else {
    $error = "Incorrect GET parameters have been supplied.  I need 'start', 'end', and 'show'.  'start' and 'end' must be unix timestamps (PHP style - seconds, not milliseconds).  ";
  $blame_request = true;
}




function make_audio($start, $end, $file, $tags = false){
  global $archive_access_url,
         $ftp_url, $ftp_user, $ftp_pass, $ftp_port,
         $audio_path_local,$audio_path_online,
         $error;

  if($end == '' || !$end || $end == 0 || !is_numeric($end)){
    $error .= 'end value "'.$end.'" is invalid';
  }

  if($start == '' || !$start || $start == 0 || !is_numeric($start)){
    $error .= 'start value "'.$start.'" is invalid';
  }

  if($end <= $start){
    $error .= ' start time must be before end! ';
  }

  if($end - $start > (60*60*PODCAST_LIMIT_HOURS)){
    $error .= ' maximum duration of a podcast is '.PODCAST_LIMIT_HOURS.' hours! ';
  }

  if($end > time()){
    $error .= ' podcast is in the future ';

  }

  //$file_name = $file.'-'.$start.'-'.$end.'.mp3';

  $start_date =  date('d-m-Y+G%3\Ai%3\As', $start);
  $podcast_year = date('Y',$start);
  $podcast_day_month = date('Y-m-d',$start);


  if(isset($filename_override)){
    $file_name = $filename_override.'.mp3';
  } else {
    $file_name = $file.'-'.$podcast_day_month.'.mp3';
  }

  $end_date =  date('d-m-Y+G%3\Ai%3\As', $end);

  $archive_url = $archive_access_url.
      "&startTime=".
      $start_date.
      "&endTime=".
      $end_date;

  $ftp_connection = ftp_connect($ftp_url, $ftp_port);

    if(!$ftp_connection){
      $error .= 'cannot connect to ftp server. ';
    }

  if ($error==''){
    $logged_in = ftp_login($ftp_connection, $ftp_user ,$ftp_pass);

    if(!$logged_in){
      $error .= 'could not login to ftp server. ';
    }
  }

  if ($error==''){
    $new_podcast_audio_file = file_get_contents($archive_url); // use to test:  file_get_contents('http://maltinerecords.cs8.biz/111/03.mp3');

    if(strlen($new_podcast_audio_file) <=0 ){
      $error .=  'Cannot retrieve audio from archiver at '.$archive_url.'.  ';
    }

  }

  if ($error=='') {

//    $fp = fopen('php://temp', 'r+');
//    $num_bytes = fwrite($fp, $new_podcast_audio_file);
//    rewind($fp);

//    $temp_file = sys_get_temp_dir().'temp.mp3';

    $file_handle = tmpfile();

    $info = stream_get_meta_data($file_handle);

    $num_bytes = fwrite($file_handle,$new_podcast_audio_file);//file_put_contents($temp_file,$new_podcast_audio_file);

    if ($num_bytes < 16){
      $error .= 'Error writing file to temp:  ('.$num_bytes.' bytes written).  ';
    }

    if($tags && $error == '') {
      rewind($file_handle);
      write_tags($tags,$info['uri']);
      rewind($file_handle);
    }


    if ($error == ''){
      ftp_mkdir($ftp_connection, $audio_path_local.$podcast_year);
      ftp_fput($ftp_connection, $audio_path_local.$podcast_year.'/'.$file_name, $file_handle, FTP_BINARY);

      ftp_close($ftp_connection);

      return array('filename' => $podcast_year.'/'.$file_name,
              'size' => $num_bytes,
              'start' => $start_date,
              'end' => $end_date,
              'url' => $audio_path_online.$podcast_year.'/'.$file_name);
    } else {

      ftp_close($ftp_connection);
      return false;

    }

  }

}


function write_tags($tags,$file){
  global $error;
  $TextEncoding = 'UTF-8';

  require_once('../../headers/getid3/getid3/getid3.php');
  // Initialize getID3 engine
  $getID3 = new getID3;
  $getID3->setOption(array('encoding'=>$TextEncoding));

  require_once('../../headers/getid3/getid3/write.php');
  // Initialize getID3 tag-writing module
  $tagwriter = new getid3_writetags;
  //$tagwriter->filename = '/path/to/file.mp3';
  $tagwriter->filename = $file;

  //$tagwriter->tagformats = array('id3v1', 'id3v2.3');
  $tagwriter->tagformats = array('id3v2.4');

  // set various options (optional)
  $tagwriter->overwrite_tags = true;
  $tagwriter->tag_encoding = $TextEncoding;
  $tagwriter->remove_other_tags = true;

  // populate data array
  $TagData = $tags;
  $tagwriter->tag_data = $TagData;

  // write tags
  if ($tagwriter->WriteTags()) {

    if (!empty($tagwriter->warnings)) {
      $error .= 'There were some warnings:<br>'.implode('<br><br>', $tagwriter->warnings);
    }
  } else {
    $error .= 'Failed to write tags!<br>'.implode('<br><br>', $tagwriter->errors);
  }

}
// write audio file name, length to database url field
// update podcast's xml feed
?>
