<?php

  $show_id = $_POST['showtitle'];
  $host_id = htmlentities(fget_id($_POST['host'], "hosts", true));
  $create_name = get_username();
  $create_date = date('Y-m-d H:i:s', get_time());
  $edit_name = get_username();
  $show_date = fas($_POST['pl_date_year'] . "-" . $_POST['pl_date_month'] . "-" . $_POST['pl_date_day']);
  $start_time = fas($_POST['pl_date_year'] . "-" . $_POST['pl_date_month'] . "-" . $_POST['pl_date_day'] . " " . $_POST['pl_date_hour'] . ":" . $_POST['pl_date_min'] . ":" . "00");
  $end_time = fas($_POST['end_date_hour'] . ":" . $_POST['end_date_min'] . ":" . "00");
  $spokenword = htmlentities($_POST['spokenword']);
  $spokenword_h = $_POST['sw-time-hr'];
  $spokenword_m = $_POST['sw-time-min'];
  $unix_time = $_POST['unixTime'];
  $status = $_POST['status'];
  $star = $_POST['star'];
  $pl_crtc = $_POST['pl_crtc'];
  $pl_lang = $_POST['pl_lang'];
  $type = $_POST['type'];


  $spokenword_duration = 60 * $spokenword_h + $spokenword_m;

  if ($newPlaysheet) { // submitting a new playsheet

    $ps_query = "INSERT INTO `playsheets` (id, create_date, create_name) VALUES (null, '$create_date', '$create_name')";
    if (mysqli_query($db, $ps_query))
      $ps_id = mysqli_insert_id($db);
    else
      echo "create playsheet unsuccessful :(<br/>";
  } else {  // submitting a previously loaded playsheet (editing)

    //Delete all play items and logged ads
    $ps_id = $_POST['id'];
    mysqli_query($db, "DELETE FROM `playitems` WHERE playsheet_id='$ps_id'");
    //	mysqli_query($db,"DELETE FROM adlog WHERE playsheet_id='$ps_id'");

  }

  if (!$unix_time) {
    $unix_time = 'NULL';
  }
  $error_occurred = 'false';
  $query = "UPDATE `shows` SET last_show='$start_time' WHERE id='$show_id' AND last_show < '$start_time'";
  $result = $db->query($query);

  if ($result) {
    $update_show_query = "UPDATE `playsheets` SET show_id='$show_id', host_id='$host_id', edit_name='$edit_name', start_time='$start_time', end_time='$end_time', spokenword='$spokenword', spokenword_duration='$spokenword_duration', unix_time=" . $unix_time . ", status='$status', star='$star', crtc='$pl_crtc', lang='$pl_lang', type='$type' WHERE id='$ps_id'";
    $result2 = $db->query($update_show_query);
    if ($result2) {
      echo "<h3>thanks for submitting a playsheet!  Here is the music you played:</h3>";
    } else {
      $error[0] = mysqli_error($db);
      echo mysqli_error($db);
      $error[1] = $update_show_query;
      $error_occurred = 'true';
    }
  } else {
    $error[0] = mysqli_error($db);
    $error[1] = $update_show_query;
    $error_occurred = 'true';
  }
  if (error_occurred == 'true') {
    $log_me = "<hr/> Error Logged at: " . $today . "<br> Occured on page: " . $_SERVER['HTTP_REFERER'] . " <br/> Error: " . $error[0] . "<br/>Query: " . $error[1] . "<br/>Data: " . json_encode($_POST, true);
    $log_file = 'logs/log.html';
    if (file_put_contents('logs/log.html', $log_me, FILE_APPEND)) {
      echo "<br/>The Error was Logged Sucessfully";
    } else {
      echo "<br/>The error could not be logged";
    }
  }


  if ($SOCAN_FLAG) {
    echo "<div class=playsheetSOCAN>";
  } else {
    echo "<div class=playsheetSOCAN>";
  }

  // NEED TO KNOW HOW MANY ROWS SOMEHOW!


  if (!isset($show_id)) {
    $show_id = 0;
  }

  for ($i = 0; $i < $playsheet_entries; $i++) {
    //		mysqli_query($db, "INSERT INTO `playitems` (playsheet_id, show_id, song_id, format_id, is_playsheet, is_canadian, is_yourown, is_indy, is_fem, show_date) VALUES ('$ps_id', '$show_id', '".fget_song_id($_POST['artist'.$i], $_POST['title'.$i], $_POST['song'.$i])."', '".$fformat_id[$_POST['format'.$i]]."', '".(isset($_POST['pl'.$i])?1:0)."', '".(isset($_POST['cc'.$i])?1:0)."', '".(isset($_POST['yo'.$i])?1:0)."', '".(isset($_POST['indy'.$i])?1:0)."', '".(isset($_POST['fem'.$i])?1:0)."', '$show_date')");
    /*abcd*/
    $cat = 12;
    if ($SOCAN_FLAG) {
      //$insert_song_start_day = $_POST['set_song_start_day'.$i];
      $insert_song_start_hour = $_POST['set_song_start_hour' . $i];
      $insert_song_start_minute = $_POST['set_song_start_minute' . $i];
      //$insert_song_start_second = $_POST['set_song_start_second'.$i];
      $insert_song_length_minute = $_POST['set_song_length_minute' . $i];
      $insert_song_length_second = $_POST['set_song_length_second' . $i];
      $insert_background = isset($_POST['background' . $i]) ? 1 : 0;
      $insert_theme = isset($_POST['theme' . $i]) ? 1 : 0;
    }


    $insert_artist = $_POST['artist' . $i];
    $insert_album = $_POST['album' . $i];
    $insert_song = $_POST['song' . $i];
    $insert_songID = fget_song_id($insert_artist, $insert_album, $insert_song);
    $insert_pl = isset($_POST['pl' . $i]) ? 1 : 0;
    $insert_cc = isset($_POST['cc' . $i]) ? 1 : 0;
    $insert_fem = isset($_POST['fem' . $i]) ? 1 : 0;
    $insert_crtc = $_POST['crtc' . $i];
    $insert_lang = addslashes($_POST['lang' . $i]);
    $insert_part = isset($_POST['part' . $i]) ? 1 : 0;
    $insert_inst = isset($_POST['inst' . $i]) ? 1 : 0;
    $insert_hit = isset($_POST['hit' . $i]) ? 1 : 0;

    if ($SOCAN_FLAG) {
      $insert_composer = $_POST['composer' . $i];
      $update_query = "UPDATE songs SET composer = '$insert_composer' WHERE id='$insert_songID'";
      if (mysqli_query($db, $update_query)) {
      } else
        echo 'update composer unsuccessful<br/>';
    }

    if (isset($_POST['artist' . $i]) && isset($_POST['album' . $i]) && isset($_POST['song' . $i])) {
      if ($SOCAN_FLAG) {
        $insert_query = "INSERT INTO `playitems` " .
            "(playsheet_id, show_id, song_id, is_playsheet, is_canadian, is_fem, show_date, crtc_category, lang, is_part, is_inst, is_hit, is_background, is_theme, insert_song_start_hour, insert_song_start_minute,  insert_song_length_minute, insert_song_length_second)" .
            "VALUES ('$ps_id', '$show_id', '$insert_songID', '$insert_pl', '$insert_cc', '$insert_fem','$show_date', '$insert_crtc', '$insert_lang', '$insert_part', '$insert_inst', '$insert_hit', '$insert_background','$insert_theme','$insert_song_start_hour',  '$insert_song_start_minute', '$insert_song_length_minute',  '$insert_song_length_second')";
      } else {
        $insert_query = "INSERT INTO `playitems` " .
            "(playsheet_id, show_id, song_id, is_playsheet, is_canadian, is_fem, show_date, crtc_category, lang, is_part, is_inst, is_hit)" .
            "VALUES ('$ps_id', '$show_id', '$insert_songID', '$insert_pl', '$insert_cc', '$insert_fem','$show_date', '$insert_crtc', '$insert_lang', '$insert_part', '$insert_inst', '$insert_hit')";
      }
      $insert_result = $db->query($insert_query);
      if ($insert_result) {
        if ($insert_cc == 1) {
          echo "<font color=red>";
        } else {
          echo "<font color=white>";
        }
        if ($SOCAN_FLAG) {
          echo html_entity_decode($insert_artist) . " - " . html_entity_decode($insert_song) . "-" . html_entity_decode($insert_album) . "-" . html_entity_decode($insert_composer);
        } else {
          echo html_entity_decode($insert_artist) . " - " . html_entity_decode($insert_song) . "-" . html_entity_decode($insert_album);
        }
        echo "</font><br/>";
      } else {
        echo "An Error Occurred!";
        $error[0] = mysqli_error($db);
        $error[1] = $insert_query;
        $log_me = "<hr/> Error Logged at: " . $today . "<br> Occured on page: " . $_SERVER['HTTP_REFERER'] . " <br/> Error: " . $error[0] . "<br/>Query: " . $error[1] . "<br/>Data: " . json_encode($_POST, true);
        $log_file = 'logs/log.html';
        if (file_put_contents('logs/log.html', $log_me, FILE_APPEND)) {
          echo "<br/>The Error was Logged Sucessfully";
        } else {
          echo "<br/>The error could not be logged";
        }
      }
    }

  }


  $ad_entries = $_POST["numberOfAdRows"];


  $ad_query = "UPDATE adlog SET playsheet_id = '" . $ps_id . "', played='0' WHERE time_block = '" . $_POST['unixTime'] . "'"; // assume the ad is not played - set to 0
  if (mysqli_query($db, $ad_query)) {
  } else {
    echo "ad query didn't work: " . $ad_query . "<br/>";
    $log_me = 'playsheet.php - there was a problem with the ad update query ' . date('D, d M Y', get_time()) . ' - <b>' . date(' g:i:s a', get_time()) . '</b>';
    $log_me .= '<br/>POST: ' . print_r($_POST, true) . '<br>ad_query:' . $ad_query . '<hr>';
    $log_file = 'logs/log.html';
    file_put_contents('logs/log.html', $log_me, FILE_APPEND);
  }

  foreach ($_POST as $postID => $postVal) {
    if (substr($postID, 0, 10) == "adplaydbid") {
      $brian = explode("_", $postID);
      $ad_row_db_id = $brian[1];
      $ad_query = "UPDATE adlog SET played = '1', playsheet_id = '" . $ps_id . "' WHERE id='" . $ad_row_db_id . "'"; // set the row to played
      if (mysqli_query($db, $ad_query)) {

      } else {
        echo "ad query didn't work: <br/>" . $ad_query . "<br/>";
        $log_me = 'playsheet.php - there was a problem with the ad update query ' . date('D, d M Y', get_time()) . ' - <b>' . date(' g:i:s a', get_time()) . '</b>';
        $log_me .= '<br/>POST: ' . print_r($_POST, true) . '<br>ad_query:' . $ad_query . '<hr>';
        $log_file = 'logs/log.html';
        file_put_contents('logs/log.html', $log_me, FILE_APPEND);
      }
    }
  }

  echo "</div>";
  echo "<br/><br/>format:<br/> artist - title (album) <br/> <font color=red>red means cancon</font> <br/><br/> feedback? email technicalservices@citr.ca<br/><br/>";


//