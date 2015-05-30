<?php
/**
 * Created by PhpStorm.
 * User: brad
 * Date: 5/22/15
 * Time: 1:06 PM
 */

// at this point, the latest podcast info will be in the episode list for this show
// so the only thing left to do is
// update the audio, record the audio url, then update the feed

require_once('../api_common_private.php');
require_once('create_audio_file.php');

if(array_key_exists('updateAudio', $incoming_data)){
  $update_audio = $incoming_data['updateAudio'];
  unset($incoming_data['updateAudio']);

} else {

  $update_audio = true;
}

$episode = $incoming_data;

if($episode['duration'] <= 0){
  $error .= ' invalid duration ';
  finish();
}
$channel_info = singleRowFromDB($db,'podcast_channels',$episode['channel_id']);

$start = strtotime($episode['date']);
$end = $start + $episode['duration'];
$slug = $channel_info['slug'];

$episode_id = $episode['id'];
unset($episode['id']);

if(array_key_exists('edit_date', $episode)){
  unset($episode['edit_date']);
}
// create audio file

if($update_audio){

  $tags = array(
      'title'         => array($episode['title']),
      'artist'        => array($channel_info['title']),
      'album'         => array('CiTR Podcasts'),
      'year'          => array(date('Y', strtotime($episode['date']))),
      'genre'         => array('CiTR'),
      'comment'       => array('citr.ca')
  );


  if($error == '') {

      $audio_result = make_audio($start, $end, $slug.'-'.$episode_id.'-', $tags);

    if($audio_result){
      $episode['url'] = $audio_path_online . $audio_result['filename'];
      $episode['length'] = $audio_result['size'];
    }

  }

    $episode['date'] = date(DATE_RSS,strtotime($episode['date']));
    update_row_in_table('podcast_episodes',$episode, $episode_id);


}



if ($error == ''){

  $query = "SELECT * FROM podcast_episodes WHERE channel_id = ".$channel_info['id']." order by date asc";

  $episodes = array();
  if ($result2 = mysqli_query($db, $query) ){

    while($row = mysqli_fetch_assoc($result2)) {
      $episodes []= $row;
    }

  } else {
    $error .= ' db problem. query is '.$query.'  ';
  }


  $xml_file_data = make_podcast($channel_info, $episodes);

  $result = writeFile($xml_path_local, $xml_file_data, $channel_info['slug'].'.xml');

}

if ($error == '') {

  $data = $result;

  if($update_audio){
    $data['new_audio_url'] = $audio_path_online . $audio_result['filename'];;
  }
  finish();

} else {

  finish();

}



function make_podcast($channel,$episodes){

  global $PREFIX, $error, $xml_path_local;

  foreach ($channel as $i => $v) {
    $channel[$i] = htmlspecialchars(html_entity_decode($v));
  }

  $xml_head = '<?xml version="1.0"  ?>

	<rss xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" version="2.0" >';

  $xml = '';

  $xml .= $xml_head;
  $xml .= '<channel>';
  $xml .= '<title>' . $PREFIX . $channel['title'] . '</title>';
  $xml .= '<description>' . $channel['summary'] . '</description>';
  $xml .= '<itunes:summary>' . $channel['summary'] . '</itunes:summary>';
  $xml .= '<itunes:author>' . $channel['author'] . '</itunes:author>';
  $xml .= '<itunes:subtitle>' . $channel['subtitle'] . '</itunes:subtitle>';
  $xml .= '<itunes:owner> ' .
      '<itunes:name>' . $channel['owner_name'] . '</itunes:name>' .
      '<itunes:email>' . $channel['owner_email'] . '</itunes:email>' .
      '</itunes:owner>';
  $xml .= '<itunes:image href="' . $channel['image_url'] . '"/>';

  $xml .= '<itunes:link rel="image" type="video/jpeg" href="' . $channel['image_url'] . '">' . $channel['title'] . '</itunes:link>';
  $xml .= '<image>' .
      '<link>' . $channel['link'] . '</link>' .
      '<url>' . $channel['image_url'] . '</url>' .
      '<title>' . $channel['title'] . '</title>' .
      '</image>';
  $xml .= '<link>' . $channel['link'] . '</link>';
  $xml .= '<generator> podcast mate 2000 </generator>';


  foreach ($episodes as $i => $episode) {

    if($episode['active']== 1) {

      foreach ($episode as $in => $val) {
        $episode[$in] = htmlspecialchars(html_entity_decode($val));
      }

      $xml .=
          '<item>' .
          '<title>' . $episode['title'] . '</title>' .
          '<pubDate>' . $episode['date'] . '</pubDate>' .
          '<description>' . $episode['summary'] . '</description>' .
          '<itunes:subtitle>' . $episode['subtitle'] . '</itunes:subtitle>';
      $xml .= ($episode['duration'] > 0) ? '<itunes:duration>' . $episode['duration'] . '</itunes:duration>' : '';

      $xml .=
          '<enclosure url="' . $episode['url'] . '" length="' . $episode['length'] . '" type="audio/mpeg"/>' .
          '<guid isPermaLink="true">' . $episode['url'] . '</guid></item>';
    }

  }


  $xml .= '</channel></rss>';

  return $xml;
}


function writeFile($local_path,$file_data, $file_name){
  global $ftp_url, $ftp_user, $ftp_pass, $error, $ftp_port;

  $ftp_connection = ftp_connect($ftp_url, $ftp_port);

  if(!$ftp_connection){
    $error .= 'cannot connect to ftp server. ('.$ftp_url.', port '.$ftp_port.')';
  }
  if ($error==''){
    $logged_in = ftp_login($ftp_connection, $ftp_user ,$ftp_pass);
    if(!$logged_in){
      $error .= 'could not login to ftp server. ';
    }
  }

  if ($error=='') {
    $fp = fopen('php://temp', 'r+');
    $num_bytes = fwrite($fp, $file_data);
    rewind($fp);

    if ($num_bytes < 16){
      $error .= 'Error writing file to temp  ('.$num_bytes.').  ';
      finish();
    }

    if (!$error){
      if (!ftp_fput($ftp_connection, $local_path.$file_name, $fp, FTP_BINARY)){
        $error .= ' problem writing file: '.$local_path.$file_name.'  ';
      } else {
//        ftp_chmod($ftp_connection, '444', $local_path . $file_name);
      }
      return array('filename' => $file_name,
          'size' => $num_bytes);
    }

    ftp_close($ftp_connection);
  }


}
