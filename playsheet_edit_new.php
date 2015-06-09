<?php


require_once('adLib.php');
$adLib = new AdLib($mysqli_sam, $db);

// Existing Playsheet
if ($actionSet && $action == 'edit' || $action == 'datadump') {


  //LOADING A SAVED PS
  $ps_id = fas($_GET['id']);
  //echo " you are editing playsheet id number ".$ps_id;
  if ($result = mysqli_query($db, "SELECT *,UNIX_TIMESTAMP(start_time) AS good_date, HOUR(end_time) AS end_hour, MINUTE(end_time) AS end_min FROM playsheet WHERE id='$ps_id'")) {
    $curr_id = mysqli_result_dep($result, 0, "show_id");
    $currshow = $showlib->getShowByID($curr_id);
    $pl_date_year = date('Y', mysqli_result_dep($result, 0, "good_date"));
    $pl_date_month = date('m', mysqli_result_dep($result, 0, "good_date"));
    $pl_date_day = date('d', mysqli_result_dep($result, 0, "good_date"));
    $pl_date_hour = date('H', mysqli_result_dep($result, 0, "good_date"));
    $pl_date_min = date('i', mysqli_result_dep($result, 0, "good_date"));
    $end_date_hour = mysqli_result_dep($result, 0, "end_hour");
    $end_date_min = mysqli_result_dep($result, 0, "end_min");
    $unix_start_time = mktime($pl_date_hour, $pl_date_min, 0, $pl_date_month, $pl_date_day, $pl_date_year);
    $host_name = $fhost_name[mysqli_result_dep($result, 0, "host_id")];
    $show_name = $fshow_name[mysqli_result_dep($result, 0, "show_id")];
    $show_id = mysqli_result_dep($result, 0, "show_id");
    $loaded_spokenword = mysqli_result_dep($result, 0, "spokenword");
    $loaded_sw_duration = mysqli_result_dep($result, 0, "spokenword_duration");
    $loaded_status = mysqli_result_dep($result, 0, "status");
    if ($loaded_status == 1) $playsheet_is_draft = true; else $playsheet_is_draft = false;
    $loaded_crtc = mysqli_result_dep($result, 0, "crtc");

    $loaded_lang = mysqli_result_dep($result, 0, "lang");
    $loaded_type = mysqli_result_dep($result, 0, "type");
    $adTable = $adLib->loadTableForSavedPlaysheet($ps_id);
  } else {
    // db query didn't work :|
    $pl_date_year = date('Y', get_time());
    $pl_date_month = date('m', get_time());
    $pl_date_day = date('d', get_time());
    $pl_date_hour = date('H', get_time());
    $pl_date_min = date('i', get_time());
    $end_date_hour = date('H', get_time());
    $end_date_min = date('i', get_time());

    $host_name = "";
    $show_name = "";
    $show_id = "";

    $loaded_spokenword = "";
    $loaded_sw_duration = "";
    $loaded_crtc = "";
    $loaded_lang = "";
  }
} else {
  // making a new PS

  if (isset($_GET['time'])) {
    $unix_start_time = $_GET['time'];

    //check to see if this unix time already has a playsheet saved - if so, load that one with action=edit

    $check_query = "SELECT id FROM playsheets WHERE unix_time='" . $unix_start_time . "'";
    if ($check = mysqli_query($db, $check_query)) {
      $checked = mysqli_fetch_assoc($check);
      if ($yesnumber = $checked['id']) {
        header("Location: ./playsheet.php?action=edit&id=" . $yesnumber);
      }
    } else {
    }

    //MAKING A NEW PS THAT IS IN PAST (OR FUTURE)
    $currshow = $showlib->getShowByTime($unix_start_time);

    $pl_date_year = date('Y', $unix_start_time);
    $pl_date_month = date('m', $unix_start_time);
    $pl_date_day = date('d', $unix_start_time);
    $pl_date_hour = date('H', $unix_start_time);
    $pl_date_min = date('i', $unix_start_time);

    $show_end = strtotime($currshow->times[0]['end_time']);
    $end_date_hour = date('H', $show_end);
    $end_date_min = date('i', $show_end);

  } else {

    // MAKING NEW PS THAT IS RIGHT NOW (default)
    $currshow = $showlib->getCurrentShow();
    $showtime = $currshow->getMatchingTime($showlib->getCurrentTime());

    if (count($showtime)) {
      $pl_date_hour = date('H', strtotime($showtime['start_time']));
      $pl_date_min = date('i', strtotime($showtime['start_time']));
      $end_date_hour = date('H', strtotime($showtime['end_time']));
      $end_date_min = date('i', strtotime($showtime['end_time']));
      //	echo "  ".$pl_date_hour.":".$pl_date_min;
    }
    $pl_date_year = date('Y', get_time());
    $pl_date_month = date('m', get_time());
    $pl_date_day = date('d', get_time());

    $unix_start_time = mktime($pl_date_hour, $pl_date_min, 0, $pl_date_month, $pl_date_day, $pl_date_year);

  }
  $showtype = $currshow->showtype;
  $ps_id = 0;
  $host_name = $currshow->host;
  $show_name = $currshow->name;
  $show_id = $currshow->id;
  $lang_default = $currshow->lang_default;
  $crtc_default = $currshow->crtc_default;

  if ($lang_default == '') {
    $lang_default = 'eng';
  }
  if ($crtc_default == '') {
    $crtc_default = 20;
  }

  $loaded_spokenword = "";
  $loaded_sw_duration = "";
  $adTable = $adLib->generateTable($unix_start_time, 'dj', false);
}

if ($loaded_crtc)
  $crtc_pl = $loaded_crtc;
else $crtc_pl = $crtc_default;

if ($loaded_lang)
  $lang_pl = $loaded_lang;
else $lang_pl = $lang_default;
echo "<div id=loaded_crtc_test style='display:none'>" . $crtc_pl . "</div>";
if ($ps_id && $_GET['action'] != 'datadump') {
  // VIEW IS NOT RAW DATA
  printf("<br><div class=buttonContainer>");
  printf("<div class=nav><ul><li><a href=\"playsheet.php?action=datadump&id=%s\">&nbsp;View Tracklist&nbsp;</a></li></ul></div></div>", $ps_id);
} else if ($ps_id) {
  // VIEW IS RAW DATA
  printf("<br><div class=buttonContainer>");
  printf("<div class=nav><ul><li><a href=\"playsheet.php?action=edit&id=%s\">&nbsp;View Playsheet&nbsp;</a></li></ul></div></div>", $ps_id);
}


// WINDOWS INTERNET EXPLORER CHECK
preg_match('/MSIE (.*?);/', $_SERVER['HTTP_USER_AGENT'], $matches);

if (count($matches) > 1) {
  //Then we're using IE
  $version = $matches[1];

  switch (true) {
    case ($version <= 8):
      print(' 	<div align="right"><button id="SamTab" class="panel-button">SAM plays</button>
				<button id="buttonLoadTimes" class="panel-button">SAM period </button>
				<button id="autosaver" class="panel-button">save<br/>draft</button></div> ');
      break;

    default:
      print("");
  }
}


// Raw Data view
printf("<br>");
if ($SOCAN_FLAG) {
  printf("<div class=playsheetSOCAN>");
} else {
  printf("<div class=playsheet>");
}
if ($_GET['action'] == 'datadump') {


  if ($ps_id) {
    $result = mysqli_query($db, "SELECT * FROM playitems WHERE playsheet_id='$ps_id' ORDER BY id");
    $num_rows = mysqli_num_rows($result);
  } else {
    $num_rows = 0;
  }


  echo "<table >";
  echo "<tr><td colspan=2 ><br/>playsheet tracklist <br/>artist - song (album) <br/><br/></td></tr>";
  echo "<tr>";

  if ($ps_id) {
    $result = mysqli_query($db, "SELECT * FROM playitems WHERE playsheet_id='$ps_id' ORDER BY id");
    $num_rows = mysqli_num_rows($result);
  } else {
    $num_rows = 0;
  }
  for ($i = 0; $i < $num_rows; $i++) {

    $result2 = mysqli_query($db, "SELECT * FROM songs WHERE id='" . mysqli_result_dep($result, $i, "song_id") . "'");
    echo "<tr>";
    echo "<td class=\"rawdata\">";
    echo html_entity_decode(mysqli_result_dep($result2, 0, "artist"));
    echo " - ";
    echo html_entity_decode(mysqli_result_dep($result2, 0, "song"));
    echo " (";
    echo html_entity_decode(mysqli_result_dep($result2, 0, "title"));
    echo ")<br/>";
    if ($SOCAN_FLAG) {
      echo " - ";
      echo html_entity_decode(mysqli_result_dep($result2, 0, "composer"));
      echo "<br/>";
    }
    echo "</td></tr>";

  }
  echo "</table>";
}
else {
//
//
//
//
//
//
//              PLAYSHEET EDITING VIEW ( same for new playsheet or old playsheet )
//
//
//
//
//
//
//		echo ('playsheet edit view. ID is '.$ps_id.'<br/>timestamp: '.$unix_start_time);
//		echo '.  Date: '.date( 'D, M j, g:ia', $unix_start_time);


  if ($SOCAN_FLAG) {
    printf("<FORM METHOD=POST ACTION=\"%s?action=submit&socan=true\" name=\"playsheet\" id='playsheetForm' >", $_SERVER['SCRIPT_NAME']);
  } else {
    printf("<FORM METHOD=POST ACTION=\"%s?action=submit\" name=\"playsheet\" id='playsheetForm' >", $_SERVER['SCRIPT_NAME']);
  }
  ?>





<INPUT type=hidden id='psid' name=id value= <?php echo $ps_id; ?>>
<center><h1>DJ PLAYSHEET</h1></center>

  <span id='ps_header'>
<table border=0 align=center width=100%%>
  <tr>
  <td>
  Show: <select id='showSelector' name="showtitle">
  <?php
  if ($ps_id || $show_name)
    printf("<option value='%s' selected='selected'>%s", $show_id, $show_name);

  if (!$playsheet_is_draft) {
    $query = "SELECT id,name FROM shows WHERE active=1 ORDER BY name";
    if ($result = $db->query($query)) {
      while ($row = mysqli_fetch_array($result)) {
        echo "\n<option value='" . $row[id] . "'>" . $row[name] . "</option>";
      }
    }
    $result->close();
  }

  if ($playsheet_is_draft) {
    // Playlist Date (READONLY if draft)

    echo "<tr><td>Date: ";
    echo "(<SELECT id=playsheet-year NAME=pl_date_year  ><OPTION>" . $pl_date_year;
    echo "</SELECT>-";
    echo "<SELECT id=playsheet-month NAME=pl_date_month  >\n<OPTION>" . sprintf("%02d", $pl_date_month);
    echo "</SELECT>-";
    echo "<SELECT id=playsheet-day NAME=pl_date_day   >\n<OPTION>" . sprintf("%02d", $pl_date_day);
    echo "</SELECT>) ";

  } else {

    // Playlist Date (able to change causing ads and show info to load via ajax if NOT a draft)

    echo "<tr><td>Date: ";
    echo "(<SELECT id=playsheet-year NAME=pl_date_year  ><OPTION>" . $pl_date_year;
    for ($i = 2002; $i <= 2014; $i++) echo "<OPTION>" . $i;
    echo "</SELECT>-";
    echo "<SELECT id=playsheet-month NAME=pl_date_month  >\n<OPTION>" . sprintf("%02d", $pl_date_month);
    for ($i = 1; $i <= 12; $i++) echo "<OPTION>" . sprintf("%02d", $i);
    echo "</SELECT>-";
    echo "<SELECT id=playsheet-day NAME=pl_date_day  >\n<OPTION>" . sprintf("%02d", $pl_date_day);
    for ($i = 1; $i <= 31; $i++) echo "<OPTION>" . sprintf("%02d", $i);
    echo "</SELECT>) ";
    echo "<br/><i>^^ Please set the show name and date to initialize your playsheet</i>";
  }

  ?><br/><br/>
</td></tr>
<tr>
    <td>Show Type:
      <?php if (isset($loaded_type) && ($loaded_type != null)){ ?>
    <select id='type' name='type' value=".$loaded_type.">
    <option selected><?php echo $loaded_type; ?></option>
  <?php }
  else if (isset($showtype) && ($showtype != null)){ ?>
    <select id='type' name='type' value=".$showtype.">
    <option selected> <?php echo $showtype; ?> </option>
  <?php }
  else{ ?>
    <select id='type' name='type'>
    <option selected>Live</option>
  <?php
  }
  ?>
            <option>Live</option>
            <option>Syndicated</option>
            <option>Rebroadcast</option>
            <option>Simulcast</option>
            <option>Pre-Recorded</option>
            <option>Other</option>
          </select>
          <?php

  echo "<br/><select style='height:25px' class=invisible id='select-playsheet' >";
  $query = "SELECT s.id AS id, s.name AS name, p.id AS playsheet_id, p.start_time AS start_time  FROM shows AS s INNER JOIN playsheets AS p ON s.id = p.show_id order by start_time desc limit 3000";
  if ($result = $db->query($query)) {
    while ($row = mysqli_fetch_array($result)) {
      echo "\n<option value='" . $row['playsheet_id'] . "' data='" . $row['start_time'] . "'>" . $row['start_time'] . " - " . $row['name'] . "</option>";
    }
  }
  $result->close();

  echo '</select>';
  echo '<button id="load-playsheet" type="button" class="invisible">Select This Playsheet</button></tr>';



  /*foreach($fshow_name_active as $x => $var_name) {
    if($var_name != '!DELETED' || $ps_id) printf("<option "."value='".$x."'>%s", $var_name);
  }*/
  printf("</select></td></tr>");
  printf("<tr><td align=right>Host/Op: <input id='host' name=\"host\" type=text size=30 value=\"%s\"  ></td>", $host_name);



  printf("</td><td align=right>Start Time: [");
  printf("<SELECT id=pl_date_hour NAME=pl_date_hour  >\n<OPTION>%02d", $pl_date_hour);
  for ($i = 0; $i <= 23; $i++) printf("\n<OPTION value=%02d>%02d", $i, $i);
  printf("</SELECT>:");
  printf("<SELECT id=pl_date_min NAME=pl_date_min  >\n<OPTION >%02d", $pl_date_min);
  for ($i = 0; $i <= 59; $i++) printf("\n<OPTION value=%02d>%02d", $i, $i);
  printf("</SELECT>]");

  printf("</td><td align=right>End Time: [");
  printf("<SELECT id=end_date_hour NAME=end_date_hour  >\n<OPTION>%02d", $end_date_hour);
  for ($i = 0; $i <= 23; $i++) printf("\n<OPTION value=%02d >%02d", $i, $i);
  printf("</SELECT>:");
  printf("<SELECT id=end_date_min NAME=end_date_min  >\n<OPTION>%02d", $end_date_min);
  for ($i = 0; $i <= 59; $i++) printf("\n<OPTION value=%02d>%02d", $i, $i);
  ?>
        </SELECT>]
    </td>
  </tr>
  <tr align=center width=400px>
    <td>CRTC Category:<input type='text' id=pl_crtc name=pl_crtc value=<?php echo $crtc_pl; ?>>
    </td>

    <td align=right colspan=2>Language:<input type='text' id=pl_lang name=pl_lang value=<?php echo $lang_pl ?>>
    <td/>
  <tr/>
</table>

<img src='images/loading.gif' id='ps-loading-image'>
</span>

<!-- main interface table -->
<span id='draft'><?php if ($playsheet_is_draft): ?>(draft)<?php endif; ?></span>

<br>

  <input type='text' id='numberOfRows' name='numberOfRows' class='invisible' value='<?php echo $playlist_entries; ?>'>
  <input type='text' id='numberOfAdRows' name='numberOfAdRows' class='invisible'>
  <input type='text' id='unixTime' name='unixTime' class='invisible' value='<?php echo $unix_start_time; ?>'>
  <input type='text' id='status' name='status' class='invisible'>
  <input type='text' id='star' name='star' class='invisible'>

  <h2>Music</h2>

  <?php if ($SOCAN_FLAG): ?>
    <td>Time</td>
    <td>Duration</td>
    <td>Composer</td>
  <?php endif; ?>
  <!-- helpboxes declaration -->
  <div id='helpboxARTIST'></div>
  <div id='helpboxSONG'></div>
  <div id='helpboxALBUM'></div>
  <div id='helpboxPL'></div>
  <div id='helpboxCC'></div>
  <div id='helpboxFE'></div>
  <div id='helpboxINST'></div>
  <div id='helpboxPART'></div>
  <div id='helpboxHIT'></div>
  <div id='helpboxTHEME'></div>
  <div id='helpboxBACKGROUND'></div>
  <div id='helpboxCRTC'></div>
  <div id='helpboxLANG'></div>
  <div id='helpboxTOOLS'></div>
  <div id='helpboxGUEST'></div>
  <div id='helpboxAD'></div>
  <!--Banner with Icons-->



  <?php
  if ($SOCAN_FLAG) {
    print('<div class="bannerforsortSOCAN">');
  } else {
    print('<div class="bannerforsort">');
  }

  print('<div class="numbering"><span>#</span></div>');


  if ($SOCAN_FLAG) {
    print("<div class='inputboxesSOCAN'><span class=popup id=ppartist>Artist</span></div>");
    print("<div class='inputboxesSOCAN'><span class=popup id=ppalbum>Album</span></div>");
    print("<div class='inputboxesSOCAN'><span class=popup id=ppsong>Song</span></div>");
    print("<div class='inputboxesSOCAN'><span class=popup id=ppcomp>Composer</span></div>");
    print("<div class='timeBox'><div class='timeBoxHalf'><span class=popup id=pptime1>Time Start (H:M)</span></div>");
    print("<div class='timeBoxHalf'><span class=popup id=pptime2>Duration (M:S)</span></div></div>");
  } else {
    print("<div class='inputboxes'><span class=popup id=ppartist>Artist</span></div>");
    print("<div class='inputboxes'><span class=popup id=ppalbum>Album</span></div>");
    print("<div class='inputboxes'><span class=popup id=ppsong>Song</span></div>");
  }
  ?>
  <div class="CRTCicons"><span class=popup id=pppl><img src="images/pl.png"></span></div>
  <div class="CRTCicons"><span class=popup id=ppcc><img src="images/cc.png"></span></div>
  <div class="CRTCicons"><span class=popup id=ppfe><img src="images/fe.png"></span></div>
  <div class="CRTCicons"><span class=popup id=ppinst><img src="images/inst.png"></span></div>
  <div class="CRTCicons"><span class=popup id=pppart><img src="images/part.png"></span></div>
  <div class="CRTCicons"><span class=popup id=pphit><img src="images/hit.png"></span></div>
  <?php
  if ($SOCAN_FLAG) {
    echo '<div class="CRTCicons"><span class=popup id=pptheme ><img src="images/THEME.png"></span></div>';
    echo '<div class="CRTCicons"><span class=popup id=ppbackground ><img src="images/BACKGROUND.png"></span></div>';
  }
  ?>


  <div class="CRTCradios"><span class=popup id=ppcrtc>CRTC</span></div>
  <div class="CRTCtext"><span class=popup id=pplang>Lang</span></div>
  <div class="CRTCtools"><span class=popup id=pptools>Tools</span></div>
  </div>

  <?php
  if ($ps_id) {
    $result = mysqli_query($db, "SELECT * FROM playitems WHERE playsheet_id='$ps_id' ORDER BY id");
    $num_rows = mysqli_num_rows($result);
//			echo 'found a ps id, so did a query. here is the result:';
//			print_r($result);
  } else {
    $num_rows = 5;
  }
  if ($num_rows == 0) {
    $num_rows = 1;
  }


  if ($SOCAN_FLAG) {
    print('<ul id="sortable" list-styletype="none">');
  } else {
    print('<ul id="sortable" list-styletype="none">');
  }

  for ($i = 0; $i <= ($num_rows); $i++) {

    if ($ps_id) { // if $ps_id is set then its a loaded playsheet
//				$set_lang = htmlentities(mysqli_result_dep($result,$i,"lang"), ENT_QUOTES);
      $set_lang = mysqli_result_dep($result, $i, "lang");
    }
    $set_part = mysqli_result_dep($result, $i, "is_part") ? " checked" : "";
    $set_inst = mysqli_result_dep($result, $i, "is_inst") ? " checked" : "";
    $set_hit = mysqli_result_dep($result, $i, "is_hit") ? " checked" : "";
    $set_pl = mysqli_result_dep($result, $i, "is_playlist") ? " checked" : "";
    $set_cc = mysqli_result_dep($result, $i, "is_canadian") ? " checked" : "";
    $set_yo = mysqli_result_dep($result, $i, "is_yourown") ? " checked" : "";
    $set_indy = mysqli_result_dep($result, $i, "is_indy") ? " checked" : "";
    $set_fem = mysqli_result_dep($result, $i, "is_fem") ? " checked" : "";

    if ($SOCAN_FLAG) {
      $set_theme = mysqli_result_dep($result, $i, "is_theme") ? " checked" : "";
      $set_background = mysqli_result_dep($result, $i, "is_background") ? " checked" : "";

      $set_song_start_hour = mysqli_result_dep($result, $i, "insert_song_start_hour");
      $set_song_start_minute = mysqli_result_dep($result, $i, "insert_song_start_minute");
      $set_song_length_minute = mysqli_result_dep($result, $i, "insert_song_length_minute");
      $set_song_length_second = mysqli_result_dep($result, $i, "insert_song_length_second");

    }

    $crtc_num = mysqli_result_dep($result, $i, "crtc_category");

    if (!(isset($crtc_num) && ($crtc_num == "20" || $crtc_num == "30"))) {
      $crtc_num = $crtc_pl;
    }
    if (!isset($set_lang)) {
      $set_lang = $lang_pl;
    }


    $result2 = mysqli_query($db, "SELECT * FROM songs WHERE id='" . mysqli_result_dep($result, $i, "song_id") . "'");

    $set_artist = html_entity_decode(mysqli_result_dep($result2, 0, "artist"));
    $set_title = html_entity_decode(mysqli_result_dep($result2, 0, "title"));
    $set_song = html_entity_decode(mysqli_result_dep($result2, 0, "song"));
    if ($SOCAN_FLAG) {
      $set_composer = html_entity_decode(mysqli_result_dep($result2, 0, "composer"));
    }


    // last iteration counts as an invisible template row
    if ($i == ($num_rows)) {
      $i = "template";
      printf("<li id='row%s' name='row%s' class='invisible'>", $i, $i);
    } else {

      if ($SOCAN_FLAG) {
        printf("<li class='playsheetrow playsheetrowSOCAN' id='row%s' name='row%s'>", $i, $i);
      } else {
        printf("<li class='playsheetrow' id='row%s' name='row%s'>", $i, $i);
      }
    }


    print("<div class='numbering'><span class=rowLabel id='rowLabel" . $i . "'></span></div>");

    //SOCAN elements
    if ($SOCAN_FLAG) {
      print("<span class='inputboxesSOCAN'><input class='inputboxesinnerSOCAN req' id=artist" . $i . " name=artist" . $i . " type=text size=18 value='" . $set_artist . "'  ></span>");
      print("<span class='inputboxesSOCAN'><input class='inputboxesinnerSOCAN req' id=album" . $i . " name=album" . $i . " type=text size=18 value='" . $set_title . "'  ></span>");
      print("<span class='inputboxesSOCAN'><input class='inputboxesinnerSOCAN req' id=song" . $i . " name=song" . $i . " type=text size=18 value='" . $set_song . "'  ></span>");
      print("<span class='inputboxesSOCAN'><input class='inputboxesinnerSOCAN req' id=composer" . $i . " name=composer" . $i . " type=text size=18 value='" . $set_composer . "'  ></span>");
      print("<span class='timeBox'>");

      //start time
      print("<span class='timeBoxHalf'>");
      if (!$set_song_start_hour) {
        $set_song_start_hour = '00';
      }
      if (!$set_song_start_minute) {
        $set_song_start_minute = '00';
      }

      if (!$set_song_length_minute) {
        $set_song_length_minute = '00';
      }
      if (!$set_song_length_second) {
        $set_song_length_second = '00';
      }
      print("<SELECT class=timeInner id=set_song_start_hour" . $i . " name=set_song_start_hour" . $i . "  > <OPTION value='" . $set_song_start_hour . "'>" . $set_song_start_hour . "</OPTION>");
      for ($j = 0; $j <= 23; $j++) {
        print("<OPTION value='" . $j . "'>" . sprintf("%02d", $j) . "</OPTION>");
      }
      print("</SELECT>");
      print("<SELECT class=timeInner id=set_song_start_minute" . $i . " name=set_song_start_minute" . $i . "  ><OPTION value='" . $set_song_start_minute . "'>" . $set_song_start_minute . "</OPTION>");
      for ($j = 0; $j <= 59; $j++) {
        print("<OPTION value='" . $j . "'>" . sprintf("%02d", $j) . "</OPTION>");
      }
      print("</SELECT>");
      print("<SELECT style='display:none;' class=timeInner id=set_song_start_second" . $i . " name=set_song_start_second" . $i . "  ><OPTION value='" . $set_song_start_second . "'>" . $set_song_start_second . "</OPTION>");
      for ($j = 0; $j <= 59; $j++) {
        print("<OPTION value='" . $j . "'>" . $j . "</OPTION>");
      }
      print("</SELECT>");
      print("<button type=button id='current_time_start" . $i . "' name='current_time_start" . $i . "' class='nowButton getStartTime'><b>CUE</b></button>");
      print("</span>");

      //duration
      print("<span class='timeBoxHalf'>");
      print("<SELECT class=timeInner  id=set_song_length_minute" . $i . " name=set_song_end_minute" . $i . " ><OPTION value='" . $set_song_length_minute . "'>" . $set_song_length_minute . "</OPTION>");
      for ($j = 0; $j <= 59; $j++) {
        print("<OPTION value='" . $j . "'>" . sprintf("%02d", $j) . "</OPTION>");
      }
      print("</SELECT>");
      print("<SELECT class=timeInner id=set_song_length_second" . $i . " name=set_song_end_second" . $i . "  ><OPTION value='" . $set_song_length_second . "'>" . $set_song_length_second . "</OPTION>");
      for ($j = 0; $j <= 59; $j++) {
        print("<OPTION value='" . $j . "'>" . sprintf("%02d", $j) . "</OPTION>");
      }
      print("</SELECT>");
      print("<button type=button id='current_time_end" . $i . "' name='current_time_end" . $i . "' class='nowButton getEndTime'><b>END</b></button>");
      print("</span>");

      print("</span>");

    } else {
      print("<span class='inputboxes'><input class='inputboxesinner req' id=artist" . $i . " name=artist" . $i . " type=text size=18 value='" . $set_artist . "'  ></span>");
      print("<span class='inputboxes'><input class='inputboxesinner req' id=album" . $i . " name=album" . $i . " type=text size=18 value='" . $set_title . "'  ></span>");
      print("<span class='inputboxes'><input class='inputboxesinner req' id=song" . $i . " name=song" . $i . " type=text size=18 value='" . $set_song . "'  ></span>");
    }
    print("<span class='CRTCicons'><input class='mousedragclick' type=checkbox id=pl" . $i . " name=pl" . $i . $set_pl . "   ></span>");
    print("<span class='CRTCicons'><input class='mousedragclick' type=checkbox id=cc" . $i . " name=cc" . $i . $set_cc . "   ></span>");
    print("<span class='CRTCicons'><input class='mousedragclick' type=checkbox id=fem" . $i . " name=fem" . $i . $set_fem . "   ></span>");
    print("<span class='CRTCicons'><input class='mousedragclick' type=checkbox id=inst" . $i . " name=inst" . $i . $set_inst . "   ></span>");
    print("<span class='CRTCicons'><input class='mousedragclick' type=checkbox id=part" . $i . " name=part" . $i . $set_part . "   ></span>");
    print("<span class='CRTCicons'><input class='mousedragclick' type=checkbox id=hit" . $i . " name=hit" . $i . $set_hit . "   ></span>");
    if ($SOCAN_FLAG) {
      print("<span class='CRTCicons'><input class='mousedragclick' type=checkbox id=theme" . $i . " name=theme" . $i . $set_theme . "   ></span>");
      print("<span class='CRTCicons'><input class='mousedragclick' type=checkbox id=background" . $i . " name=background" . $i . $set_background . "   ></span>");
    }


    print("
					 <span class='CRTCradios'>" .
        "<span class='CRTCradios2'>" .
        "<label for='crtcTwo" . $i . "'class='CRTCicons3' >" .
        "2" .
        "</label>" .
        "<input class='radio mousedragclick CRTCicons3' type='radio' id='crtcTwo" . $i . "' name='crtc" . $i . "' value='20' " . ($crtc_num == '20' ? "checked='checked'" : " ") . " />" .
        "</span>" .
        "<span class='CRTCradios2'>" .
        "<label for='crtcThree" . $i . "' class='CRTCicons3' >" .
        "3" .
        "</label>" .
        "<input class='radio mousedragclick CRTCicons3' type='radio' id='crtcThree" . $i . "' name='crtc" . $i . "' value='30' " . ($crtc_num == '30' ? "checked='checked'" : " ") . "/>" .
        "</span></span>");
    print("<span class='CRTCtext'><input class='langInput' id=lang" . $i . " name=lang" . $i . " type=text size=3 value='" . $set_lang . "'></span>");
    print("<span class='CRTCicons2'> <button type=button id=del" . $i . " class=delRow><b>-&nbsp</b></button></span>&nbsp;&nbsp;");
    print("<span class='CRTCicons2'><button type=button id=add" . $i . " class=addRow><b>+</b></button></span>");
    //print("<span class='CRTCicons2'><button type=button id=copy".$i." class=copyRow> copy </button></span>");
    print("<span class='CRTCicons2'><span class='dragHandle'>&nbsp;&nbsp;&#x21D5;</span></span>");
    print("<span></span>");

    print('</li>');

    // print("<td id='move' class='dragHandle'>&#x21D5;</td>");
    //print("</tr>");

    if ($i === "template") $i = ($num_rows);
  }
  print('</ul>');

  echo "<span style='width:100%; display:inline-block;'><span id='addfive'> add 5 more rows </span> </span> <br/>";


  // ADS SECTION


  ?>

  <hr/>

  <div id='spokenword'>
    <h2>Spoken Word</h2>


<span class='left' id='ads'>
<span class='popup' id='ppAds'><b>Ads / PSA / IDs</b></span>
  <?php


  echo $adTable;

  echo "</span> <span class='right'  id='swcontent'>";

  echo "<span class='popup' id='ppGuests'><b>Guests, Interviews, Topics</b></span>";

  echo "<br/>Description:<br/>";
  echo "	<textarea id='spokenword' name='spokenword' >";
  echo $loaded_spokenword;
  echo "</textarea><br/>";
  echo "Total Overall Duration:<br/>";


  printf("<SELECT NAME='sw-time-hr' id='sw-time-hr'  >\n<OPTION>");

  if ($loaded_sw_duration > 0) {
    $hours = floor($loaded_sw_duration / 60);
    $minutes = $loaded_sw_duration % 60;
    echo $hours;
  } else echo "00";
  for ($i = 0; $i < 24; $i++) printf("<OPTION>%02d", $i);
  printf("</SELECT> Hours <br/>");
  printf("<SELECT NAME='sw-time-min' id='sw-time-min'  >\n<OPTION>");
  if ($loaded_sw_duration > 0) {
    echo $minutes;
  } else echo "00";
  for ($i = 0; $i < 60; $i++) printf("<OPTION>%02d", $i);
  printf("</SELECT> Minutes");
  ?>
  <!--		</td>

    </tr>

  </table> -->
</span>

  </div>
  <br/><br/><br/><br/><br/><br/>
  <hr/>

  <?php if ($enabled['podcast_tools']) { ?>


    <div id='podcast-tools'>
      <h2>Podcast Tools</h2>
      <center>
        <button id='podcastMarker' type='button' title='Add Time Marker'>Add Time Marker</button>
        <a href="http://playlist.citr.ca/podcasting/phpadmin/edit.php" target="_blank">link to podcast editor</a>
        <span id='podcastTime'></span></center>
      <hr>
    </div>

  <?php
  }// end of podcast tools creation block


  if (!$ps_id || is_member("editdj")) {
    echo "<center><br/><span id='submitMsg'>This is an incomplete playsheet. <br/>Please fill in all music fields:
<b>artist</b>, <b>album</b> (release title), and <b>song</b>. Also delete all empty rows by clicking the '-' button.<br/>
You may temporarily save a draft and resume at another time by clicking 'Save draft' in the top right corner</span><br/>
<button id=submit type=submit value=\"Save Playsheet\">Submit Playsheet</button></center><br/><br/><br/>
			<div></div>";
  } else {
    echo "<center> sorry, you don't have permissions to edit playsheets</center>";
  }
  echo "</FORM>";
  // echo'


  print("<div class='bugsAndTopChart'>");
  if (isset($station_info['tech_email'])) {
    echo "<div class='bugs'>For support, email:<br/> <a href='mailto:" . $station_info['tech_email'] . "'>" . $station_info['tech_email'] . "</a><br/><br/> Or visit the<a href='help.php' target='_blank'> Q&A </a>page</div>";
  }
  print("<div class='topChart'>");
  print("Note: a song is a 'hit' if it has ever been in the top 40 of any of these charts:<br/>");
  print("<a target='none' href='http://www.billboard.com/charts/hot-100'>Billboard Hot 100</a><br/>");
  print("<a target='none' href='http://www.billboard.com/charts/canadian-hot-100'>Billboard Canadian Hot 100</a><br/>");
  print("<a target='none' href='http://www.billboard.com/charts/country-songs'>Billboard Hot Country</a>");
  print("</div></div>");
  print("<br/><br/><br/>");
}

echo "
<div id='cancon'>
<span class='stars'></span>
<b>Cancon 2:</b> <!--<span id='CCType2Num'>0</span> /<span id='Type2Total'>0</span> =-->  <span id='CCType2Ratio' class='compliance'>0%</span>  (min 35%)


<b>Cancon 3:</b><!--<span id='CCType3Num'>0 </span> / <span id='Type3Total'>0</span> =-->   <span id='CCType3Ratio' class='compliance'>0%</span>  (min 12%)


<b>Hits:</b><!--<span id='hitNum'>0</span> / <span id='total'>0</span> =--> <span id='hitRatio' class='compliance'>0%</span>  (max 10%)

<b>Femcon:</b><span id='femRatio' class='compliance'>0%</span>   (min 35%)

<b>Playlist/New:</b><span id='plRatio' class='compliance'>0%</span>  (min 15%)

<span class='stars'></span>
</div>";

