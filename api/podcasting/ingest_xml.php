<head>

</head>

<?php

// switch off if in future we find catastrophically missing data because of incorrect duplicate cleaning

$CLEANING_FOR_DUPLICATES = true;


require_once('../api_common.php');

    $extension = 'xml';
    $maximum_results = 100000000;
    $big_count = 0;

error_reporting(E_ALL & ~ E_NOTICE);

  $directory_html = file_get_contents('http://playlist.citr.ca/podcasting/xml/');

  preg_match_all('/podcasting\/xml\/[a-zA-Z0-9_-]*.xml/', $directory_html, $xml_urls);

  $xml_urls = $xml_urls[0];
$channel_count = 0;

    foreach ($xml_urls as $key => $xml_url) {

      $channel_count = $channel_count +1;

      $xml_url = 'http://playlist.citr.ca/'.$xml_url;
      $continue = true;
      $check_q = "SELECT * FROM podcast_channels WHERE xml = '".$xml_url."'";

      if ($check_r = mysqli_query($db, $check_q)){
        if (mysqli_num_rows($check_r) >= 1){
          $continue = false;
          echo "<br/> already a podcast channel for ".$xml_url;
        }
      }

        if ($continue && ($key <= $maximum_results) ){

            if (file_extension($xml_url) == $extension) {

              $p = xml_parser_create();

              usleep(500000);
              $xml_string = file_get_contents($xml_url);

              if ($xml_string != false) {


                xml_parse_into_struct($p, $xml_string, $values, $index);

                $channel_info = array();

                $target_index = $index['TITLE'][0];
  //                echo $values[$target_index]['value'];

                $channel_info['title'] = $values[$index['TITLE'][0]]['value'];
                $channel_info['subtitle'] = isset($index['ITUNES:SUBTITLE'][0]) && isset($values[$index['ITUNES:SUBTITLE'][0]]['value']) ? $values[$index['ITUNES:SUBTITLE'][0]]['value'] : '';


                $channel_info['summary'] = intval($values[$index['DESCRIPTION'][0]]['level']) <= 3 ? $values[$index['DESCRIPTION'][0]]['value'] : '';
                $channel_info['author'] = $values[$index['ITUNES:AUTHOR'][0]]['value'];
                $channel_info['keywords'] = isset($index['ITUNES:KEYWORDS'][0]) ? $values[$index['ITUNES:KEYWORDS'][0]]['value'] : '';
                $channel_info['owner_name'] = $values[$index['ITUNES:NAME'][0]]['value'];
                $channel_info['owner_email'] = $values[$index['ITUNES:EMAIL'][0]]['value'];
                $channel_info['default_episode_title'] = '';
                $channel_info['default_episode_subtitle'] = '';
                $channel_info['default_episode_author'] = '';
                $channel_info['link'] = $values[$index['LINK'][0]]['value'];
                $channel_info['image'] = isset($index['ITUNES:LINK'][0]) ? $values[$index['ITUNES:LINK'][0]]['attributes']['HREF'] : '';
                $channel_info['xml'] = $xml_url;

                $channel_q = "INSERT into podcast_channels (title, subtitle, summary, author, keywords, owner_name, owner_email, episode_default_title, episode_default_subtitle, episode_default_author, link, image_url, xml) ";
                $channel_q .= "VALUES ('" . htmlentities(addslashes($channel_info['title'])) . "','" .
                    htmlentities(addslashes($channel_info['subtitle'])) . "','" .
                    htmlentities(addslashes($channel_info['summary'])) . "','" .
                    htmlentities(addslashes($channel_info['author'])) . "','" .
                    htmlentities(addslashes($channel_info['keywords'])) . "','" .
                    htmlentities(addslashes($channel_info['owner_name'])) . "','" .
                    htmlentities(addslashes($channel_info['owner_email'])) . "','" .
                    htmlentities(addslashes($channel_info['default_episode_title'])) . "','" .
                    htmlentities(addslashes($channel_info['default_episode_subtitle'])) . "','" .
                    htmlentities(addslashes($channel_info['default_episode_author'])) . "','" .
                    htmlentities(addslashes($channel_info['link'])) . "','" .
                    htmlentities(addslashes($channel_info['image'])) . "','" .
                    htmlentities(addslashes($channel_info['xml'])) . "');";

  //                $channel_q = mysqli_escape_string($db,$channel_q);
                $channel_id = -1;

                $exists_q = "SELECT id FROM podcast_channels WHERE " .
                    "xml ='" . htmlentities(addslashes($channel_info['xml'])) . "' ";
                $exists_r = mysqli_query($db, $exists_q);

                if (mysqli_num_rows($exists_r) == 1) {
                  echo "\n there is already a podcast with this xml." . htmlentities(addslashes($channel_info['xml'])) . "  updating episodes...";
                  $channel_id = mysqli_fetch_assoc($exists_r);
                  $channel_id = $channel_id['id'];
                } else {

                  if ($result = mysqli_query($db, $channel_q)) {
                    $channel_id = mysqli_insert_id($db);
  //                    echo '<h2>channel inserted! (id is '.$channel_id.')</h2>';
                  } else {
                    echo '<h2>could not insert this show into the db. query:' . $channel_q . '</h2>';
                  }
                }


  //IMAGE
                if (isset($index['ITUNES:IMAGE'])) {
  //                    echo '<h3>channel image:</h3>';
                  $target_index = $index['ITUNES:IMAGE'][0];
  //                    echo '<img src="' . $values[$target_index]['attributes']['HREF'] . '"/>';
                }


                $item_indexes = array();

                if (isset($index['ITEM'])) {
                  foreach ($index['ITEM'] as $i => $v) {
                    if ($values[$v]['type'] == 'open') {
                      $item_indexes [] = $v;
                    }
                  }

                  $episodes = array();
                  foreach ($item_indexes as $i => $v) {

                    $one_episode = array();

                    $searching = true;
                    $more = 1;
                    while ($searching) {

                      if ($values[$v + $more]['tag'] == 'ITEM' && $values[$v + $more]['type'] == 'close') {
                        $searching = false;
                      }
                      if ($values[$v + $more]['tag'] != 'ITEM') {

                        if (isset($values[$v + $more]['value'])) {
                          $word_index = $values[$v + $more]['tag'];
                          $one_episode[$word_index] = $values[$v + $more]['value'];
                        } else {

                          if (isset($values[$v + $more]['attributes'])) {
                            foreach ($values[$v + $more]['attributes'] as $attr_name => $attr_val) {
                              $one_episode[$attr_name] = $attr_val;
                            }
                          }
                        }
                      }

                      $more++;
                    }

                    //process duration...
                    $times_arr = explode('/', $episode['url']);
                    $times_string = $times_arr[5];
                    $times_arr = explode('-to-', $times_string);

                    $start_time_string = $times_arr[0];
                    $end_time_string = $times_arr[1];

                    $episodes [] = $one_episode;
                  }

                  if ($channel_id >= 0) {
                    ingest_episodes($episodes, $channel_id, $db);
                  } else {
  //                    echo '<br/>no channel id found<br/>';
                  }

                } else {
                  echo '<br/>no episodes in this channel ('.$xml_url.')<br/>';
                }

                xml_parser_free($p);


            } else {
              echo "<br/> xml loading failed so skipped that channel. refresh to get it. <br/>";
            }

            }

    }
    }

echo "<br/>".$big_count.' episodes inserted. '.$channel_count.' channels scanned.';

function file_extension($xml_url){
    $array = explode('.',$xml_url);
    return $array[count($array)-1];
}

function ingest_episodes($episodes,$channel_id, $db){
  global $big_count;
  global $CLEANING_FOR_DUPLICATES;



  if($CLEANING_FOR_DUPLICATES) {
    $episodes = remove_same_url($episodes);
    $episodes = remove_same_day($episodes);
  }

    foreach($episodes as $i => $episode){
        $episode_insert = "INSERT into podcast_episodes (title,subtitle,summary,date,channel_id,url,duration,length) ";
        $episode_insert .= "VALUES ('".
            htmlentities(addslashes($episode['TITLE']))."','".
            htmlentities(addslashes($episode['ITUNES:SUBTITLE']))."','".
            htmlentities(addslashes($episode['ITUNES:SUMMARY']))."','".
            htmlentities(addslashes($episode['PUBDATE']))."','".
            htmlentities(addslashes($channel_id))."','".
            htmlentities(addslashes($episode['URL']))."',".
//            htmlentities(addslashes($episode['duration']))."','".
			"0,'".
            $episode['LENGTH']."');";

        if ($result = mysqli_query($db,$episode_insert)){
//            echo '<br/>episode inserted<br/>';
            $big_count = $big_count +1;
        } else {
            echo '<br/>problem inserting episode. Query:<br/>'.$episode_insert;
        }

    }
}

function remove_same_url($episodes){

    foreach($episodes as $j => $episode){

      if($episode['URL'] == 'http://playlist.citr.ca/podcasting/audio/20140722-113000-to-20140722-130200.mp3'){
        xdebug_break();
      }

      foreach($episodes as $k => $otherepisode){

        if( ( $j != $k) && ($episode['PUBDATE'] == $otherepisode['PUBDATE']) ){

          // found two episodes with same URL...

          // first delete one of they are completely identical in every field
          $identical = true;
          foreach($episode as $key => $value){
            if( ($value != $otherepisode[$key]) ) $identical = false;
          }
          if($identical && (isset($episodes[$j])) && (isset($episodes[$k]))) {
            unset($episodes[$j]);
          }

          // now check to see if one has more characters then the other (bias towards larger description / summary )
          $chars_in_j = 0; $chars_in_k = 0;

          foreach($episode as $key => $value){
            $chars_in_j += strlen($value);
            $chars_in_k += strlen($otherepisode[$key]);
          }

          if ($chars_in_j > $chars_in_k ){
            if( (isset($episodes[$j])) && (isset($episodes[$k]))) {
              unset($episodes[$k]);
            }
          } else {

            if( (isset($episodes[$j])) && (isset($episodes[$k]))) {
              unset($episodes[$j]);
            }
          }
        }
      }
    }

  return $episodes;
}

function remove_same_day($episodes){


    foreach($episodes as $j => $episode){

      if($episode['URL'] == 'http://playlist.citr.ca/podcasting/audio/20140722-113000-to-20140722-130200.mp3'){
        xdebug_break();
      }

      foreach($episodes as $k => $otherepisode){

        if( ( $j != $k) &&
            (date('Y-m-d',strtotime($episode['PUBDATE']))  == date('Y-m-d',strtotime($otherepisode['PUBDATE'])) ) &&
            ($episode['ITUNES:SUMMARY']  == $otherepisode['ITUNES:SUMMARY'] ) &&
//            (strlen($episode['ITUNES:SUMMARY']) >1 ) &&
            ($episode['ITUNES:SUBTITLE'] == $otherepisode['ITUNES:SUBTITLE'])
//            (strlen($episode['ITUNES:SUBTITLE']) >1)
        ){
//          print_r($episode);
//          echo "\n is same day as \n";
//          print_r($otherepisode);
//          echo "\n\n\n\n~~~\n\n\n\n";
            if( (isset($episodes[$j])) && (isset($episodes[$k]))) {
              unset($episodes[$k]);
            }

        }
      }
    }


  return $episodes;
}

