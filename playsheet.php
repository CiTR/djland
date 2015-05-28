<?php
session_start();
require_once("headers/security_header.php");
require_once("headers/function_header.php");
require_once("headers/menu_header.php");

// Echos HTML head
echo "<html><head><meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">
<base href='shows.php'>
	<link rel='stylesheet' href='js/bootstrap/bootstrap.min.css'></script>
<link rel=\"stylesheet\" href=\"css/style.css\" type=\"text/css\">";

?>



<style type="text/css">

  ul, li {
    list-style-type: none;
    padding-left: 0
  }

  #totals {
    position: fixed;
    width: 100%;
    bottom: 0;
    background-color: beige;
    border-color: black;
    border-width: 2px;
    border-style: groove;
    text-align: center;
    padding: 3px;
  }

  #totals div {
    display: inline;
    padding-right: 20px;
  }

  #totals .req {
    font-weight: bold;

    color: darkgreen;
  }

  #totals .bad {
    background-color: black;
    color: red;
  }

  .crtc, .tools {
    cursor: pointer;
    background-color: lightblue;
    padding: 4px;
    padding-top: 2px;
    padding-bottom: 2px;
    round-clip: 2;
    border-style: outset;
    border-width: 2px;
    border-color: lightblue;
  }

  .crtc:hover {
    border-color: black;
  }

  .lang {
    width: 50px;
    font-size: 0.8em;
  }

  .music_row {

    max-width: 990px;
    /*
          background-color:rgba(150,150,150,0.8);*/
  }

  .music_row, .music_row * {

    font-size: 1.1em;
  }

  .music_row_heading {
    background-color: lightgrey;
  }

  .music_row_heading .crtc, .lang {
    font-size: 0.7em;
  }

  .music_row_heading input, .music_row_heading span {
    text-align: center;
    background: none;
    border-width: 0;
  }

  input {

    display: inline;
    width: auto;
  }

  .playsheet_block, .spokenword_block, .podcast_block,.save_block {
    max-width: 1000px;
    width:100%;
    min-height:200px;
    position:relative;
    margin-left: auto;
    margin-right:auto;
    display:block;
    float:left;

  }

  .spokenword_block{
    min-height:400px;
  }


  .dragzone {
    cursor: default;

  }

  button.box {
    display: inline-block;
    height: 27px;
    width: 27px;
    border-width: 1px;
    border-style: dotted;
    cursor: default;
    background-color: white;
    background-size: 100%;
    margin-bottom: 2px;
  }

  button.box.new:hover {
    background-image: url('images/pl.png');
    opacity: 0.5;
  }

  button.box.cancon:hover {
    background-image: url('images/CAN.png');
    opacity: 0.5;
  }

  button.box.femcon:hover {
    background-image: url('images/fe.png');
    opacity: 0.5;
  }

  button.box.instrumental:hover {
    background-image: url('images/inst.png');
    opacity: 0.5;
  }

  button.box.hit:hover {
    background-image: url('images/hit.png');
    opacity: 0.5;
  }

  button.box.partial:hover {
    background-image: url('images/part.png');
    opacity: 0.5;
  }


  button.box.background:hover {
    background-image: url('images/BACKGROUND.png');
    opacity: 0.5;
  }

  button.box.theme:hover {
    background-image: url('images/THEME.png');
    opacity: 0.5;
  }

  button.box.new.filled {
    background-image: url('images/pl.png');
    opacity: 1;
  }

  button.box.cancon.filled {
    background-image: url('images/cc.png');
    opacity: 1;
  }

  button.box.femcon.filled {
    background-image: url('images/fe.png');
    opacity: 1;
  }

  button.box.instrumental.filled {
    background-image: url('images/inst.png');
    opacity: 1;
  }

  button.box.hit.filled {
    background-image: url('images/hit.png');
    opacity: 1;
  }

  button.box.partial.filled {
    background-image: url('images/part.png');
    opacity: 1;
  }

  button.box.background.filled {
    background-image: url('images/BACKGROUND.png');
    opacity: 1;
  }

  button.box.theme.filled {
    background-image: url('images/THEME.png');
    opacity: 1;
  }

  button.box {
    background-color: transparent;
    background-image: none;;
  }

  li.socan{
width:1200px;
  }

  .socan select, .socan button, .socan input, .music_row_socan input, .music_row_socan button{
    font-size:0.75em;
    max-width:80px;
  }

  .socan_cue select{
    width:46px;

  }
  .playsheet_block_socan, .music_row_socan, .music_row_socan .music_row{
    max-width:1200px;
    margin-left:10px;
    padding-left:10px;
  }

  input[type=checkbox] {
    /* Double-sized Checkboxes */
    -ms-transform: scale(2); /* IE */
    -moz-transform: scale(2); /* FF */
    -webkit-transform: scale(2); /* Safari and Chrome */
    -o-transform: scale(2); /* Opera */
    padding: 20px;
  }

  .adPlay {
    /* Checkbox text */
    text-align: center;
  }

  #sam_title {
    color: black;
    font-size: 1.2em;
    position: fixed;
    padding: 5px;
    height: 40px;
  }

  #sam_title span {
    border: black 1px solid;
    cursor: default;
    padding: 6px;
    background-color: white;
  }

  #sam_title span:hover {
    border: red 1px solid;
    color: red;
    background-color: lightred;
  }

  div#sam_picker {
    position: fixed;
    top: 60px;
    right: 40px;
    height: 80%;
    overflow: scroll;
    width: 400px;
    background-color: beige;
  }

  #sam_range {
    color: black;
    text-align: right;
  }

  span.one_sam {
    overflow: hidden;
    display: inline-block;
    width: 350px;

  }

  div.floating {
    position: fixed;
    right: 0;
    top: 30px;
    width: auto;
    z-index:20;
  }

  .sam_row {
    color: black;

    border-top: black 1px solid;
  }

  .blocker{
    position:relative;
    bottom:30px;
    background-color:black;
    color:red;
  }
  .sam_row button {

    vertical-align: top;
    cursor: default;
  }

  .sam_row button:hover {

  }

  #totals{
    color:black;
  }

  button {
    color:black;
  }

  #message{
    padding:6px;
    color:black;
    position:fixed;
    top:0;
    left:40%;
    background-color:mintcream;
    font-size:1.2em;
  }

  #left, #right{
    width:48%;
    float:left;
  }

  .vars{
    position:absolute;
    top:5000px;
  }

  .tracklist_overlay{
    position:fixed;
    overflow:scroll;
    top:40px;
    left:10%;
    width:80%;
    background-color:lightsteelblue;
    z-index:21;
    padding:20px;
    color:darkblue;
  }
  .tracklist_overlay span{
    margin-left:40px;
  }
  .tracklist_overlay a{
    background-color:steelblue;
    padding:2px;
  }
  .tracklist_overlay a:hover{
    color:greenyellow;
  }

  .dark{
    position:fixed;
    top:0;
    left:0;
    height:100%;
    width:100%;
    opacity: 0.8;
    background-color:black;
  }

  th small{
    color:black;
  }

  .wrapper{
    margin-left:15px;
  }
</style>

</head>
<body>
<?php

    print_menu();


    $show_id = users_show();
    $channel_id = users_channel();

    if ( is_numeric($show_id) && $show_id >0 ){
      //good
    } else {

      echo "<br/><center>sorry, your user account doesn't own a show</center>";
      return;
    }
?>

<div class="wrapper">


<div ng-app="djLand" ng-cloak >

  <div ng-controller="playsheetCtrl" >

    <h2> Playsheet {{playsheet.status == 1 ? "(draft)" : ""}}</h2>


    <div >


      <div id="left">
        Playsheet Type: <select ng-model="playsheet.type" ng-change="loadIfRebroadcast()">
          <option value="Syndicated">Syndicated</option>
          <option value="Live">Live</option>
          <option value="Rebroadcast">Rebroadcast</option>
          <option value="Simulcast">Simulcast</option>
        </select>
        <span ng-show="playsheet.type == 'Rebroadcast'">
          <br/>
          <select ng-model="desired_playsheet"
                  ng-options="playsheet.playlist_id as playsheet.start_time + ' - ' + playsheet.show_name
                   for playsheet
                   in available_playsheets ">
          </select>

          <button ng-click="loadPlays(desired_playsheet)">{{available_playsheets.length > 1? '<-- load plays from this playsheet' : '...'}}</button>
          </span>
        <br/>Show: {{playsheet.show_id}}

        <!--    <br/>Date:   <button >{{date | date: 'mediumDate'}}</button> (click to change)
            <br/>Time:   <span ng-controller="timepicker" class="timepicker">
                                <timepicker ng-model="date" ></timepicker>
                        </span>-->
        <br/>Host: <input ng-model="playsheet.host"></input>
        <br/>Language: <input ng-model="playsheet.lang"></input>
        <br/>CRTC Category: <button class="crtc" ng-model="playsheet.crtc"
                                  ng-click="playsheet.crtc == 30? playsheet.crtc = 20 : playsheet.crtc = 30;">{{playsheet.crtc}}</button>
      </div>
      <div id="right">
        <div ng-controller="datepicker" >
        Start Time:
        [<select ng-model="start_hour" ng-options="n for n in [] | range:0:24"
                 ng-change="playsheet.start_time.setHours(start_hour);"></select> :
        <select ng-model="start_minute" ng-options="n for n in [] | range:0:60"
                ng-change="playsheet.start_time.setMinutes(start_minute);"></select>]

        End Time:
        [<select ng-model="end_hour" ng-options="n for n in [] | range:0:24 "
                 ng-change="playsheet.end_time.setHours(end_hour);"></select> :
        <select ng-model="end_minute" ng-options="n for n in [] | range:0:60"
                ng-change="playsheet.end_time.setMinutes(end_minute);"></select>]

          <input class="date_picker" type="text" datepicker-popup="{{format}}"
                 ng-model="playsheet.start_time"  is-open="opened"
                 ng-required="true" close-text="Close" ng-hide="true"
                 ng-change="$parent.date_change();" />
          <br/>
          {{playsheet.start_time | date:'EEE, MMM d, y'}}
        <button ng-click="open($event)"  >change date</button>
        </div>

      </div>
    </div>
    <br/>
    <br/>
    <br/>
    <br/>
    <button ng-click="socan=!socan;" >{{socan? 'Hide' : 'Show'}} Socan Fields</button><br/>

    <h2>Music </h2>

    <div class="playsheet_block" ng-hide="music_hidden" ng-class="{playsheet_block_socan: socan}">


      <div class="music_row_heading music_row" ng-class="{music_row_socan: socan}">
        <input value="artist" class="music" readonly tooltip="{{artistHelp}}"></input>
        <input value="album" class="music" readonly tooltip="{{albumHelp}}"></input>
        <input value="song" class="music" readonly tooltip="{{songHelp}}"></input>
        <input ng-show="socan" value="composer" class="music" readonly tooltip="{{compHelp}}"></input>
        <span ng-show="socan" class="socan_cue" ><span tooltip="{{timeHelp1}}">Time Start (H:M)&nbsp; </span>|&nbsp;<span tooltip="{{timeHelp2}}"> Duration (M:S)</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>

        <button class="box new filled">&nbsp;</button>
        <button class="box cancon filled" ">&nbsp;</button>
        <button class="box femcon filled">&nbsp;</button>
        <button class="box instrumental filled">&nbsp;</button>
        <button class="box partial filled">&nbsp;</button>
        <button class="box hit filled">&nbsp;</button>

        <button ng-show="socan" class="box theme filled">&nbsp;</button>
        <button ng-show="socan" class="box background filled">&nbsp;</button>

        <button class="crtc" tooltip="{{help_crtc}}"> crtc </button>
        <input class="lang" value="language">
        <button class="tools">+</button>
        <button class="tools">-</button>
      </div>

      <ul ui-sortable ng-model="playsheet.plays">
        <li ng-repeat="row in playsheet.plays track by $index" class="music_li" ng-class="{socan: socan}">
          <div class="music_row" ng-class="{music_row_socan: socan}">
            <input ng-model="row.song.artist" class="music"
                   required="true">
            <input ng-model="row.song.title"
                   class="music" required="true">
            <input ng-model="row.song.song" class="music"
                   required="true">
            <input ng-model="row.song.composer" class="music" ng-show="socan"></input>

            <span ng-show="socan" class="socan_cue">

                <select ng-model="row.insert_song_start_hour" ng-options="n for n in [] | range:0:24 "
                        ng-change="updateNow(row);"></select>:
                <select ng-model="row.insert_song_start_minute" ng-options="n for n in [] | range:0:60 "
                        ng-change="updateNow(row);"></select>
                <button ng-click="cue(row)">CUE</button>

                <select ng-model="row.insert_song_length_minute" ng-options="n for n in [] | range:0:60 "></select>:
                <select ng-model="row.insert_song_length_second" ng-options="n for n in [] | range:0:60 "></select>
                <button ng-click="end(row)">END</button>
            </span>
            <button class="box new" ng-model="row.is_playlist" ng-class="{filled: row.is_playlist}"
                  ng-click="row.is_playlist = !row.is_playlist;" >&nbsp;</button>
            <button class="box cancon" ng-model="row.is_canadian" ng-class="{filled: row.is_canadian}"
                  ng-click="row.is_canadian = !row.is_canadian;" >&nbsp;</button>
            <button class="box femcon" ng-model="row.is_fem" ng-class="{filled: row.is_fem}"
                  ng-click="row.is_fem = !row.is_fem;" >&nbsp;</button>
            <button class="box instrumental" ng-model="row.is_inst" ng-class="{filled: row.is_inst}"
                  ng-click="row.is_inst = !row.is_inst;" >&nbsp;</button>
            <button class="box partial" ng-model="row.is_part" ng-class="{filled: row.is_part}"
                  ng-click="row.is_part = !row.is_part;" >&nbsp;</button>
            <button class="box hit" ng-model="row.is_hit" ng-class="{filled: row.is_hit}"
                  ng-click="row.is_hit = !row.is_hit;" >&nbsp;</button>
            <span ng-show="socan">
            <span class="box theme" ng-model="row.is_theme" ng-class="{filled: row.is_theme}"
                  ng-click="row.is_theme = !row.is_theme;">&nbsp;</span>
              <span class="box background" ng-model="row.is_background" ng-class="{filled: row.is_background}"
                      ng-click="row.is_background = !row.is_background;">&nbsp;</span>
            </span>

            <button class="crtc" ng-model="row.crtc_category"
                  ng-click="row.crtc_category == '30'? row.crtc_category = '20' : row.crtc_category = '30';" >{{row.crtc_category}}</button>
            <input class="lang" ng-model="row.lang">
            <button class="tools" ng-click="add($index)" >&nbsp;+&nbsp;</button>
            <button class="tools" ng-click="remove($index)" >&nbsp;-&nbsp;</button>
            <span class="dragzone">:::</span><br/>
          </div>
        </li>
      </ul>

            <button class="tools right" ng-click="add(0)" ng-show="playsheet.plays.length == 0" >&nbsp;+&nbsp;music</button>
    </div>

    <div class="spokenword_block">
    <h2>Spoken Word</h2>
<span class="left" id="ads" >
  <h3>Scheduled Ads, PSAs, Station IDs</h3>

  <p>{{(!playsheet.ads) ? ad_message: '';}}</p>

  <div class="adHead" ng-show="playsheet.ads.length >0">
    <div class='adTime label'><h4>time</h4></div>
    <div class='adType label'><h4>type</h4></div>
    <div class='adName label'><h4>name</h4></div>
    <div class='adPlay label'><h4>played</h4></div>
  </div>
<!--  <button ng-hide="playsheet.ads.length >0" ng-click="loadAds()"> load your ads </button>-->
  <div class="adRow" ng-repeat="ad in playsheet.ads">
    <div class='adTime'>{{ad.time}}</div>
    <div class='adType'>{{ad.type}}</div>
    <div class='adName'>{{ad.name}}</div>
    <div class='adPlay'><input ng-model="ad.played" type="checkbox"></input></div>

  </div>
  </span>

    <span class="right" id="spokenword">
      <h3>Guests, Interviews, Topics</h3>
      <textarea ng-model="playsheet.spokenword" placeholder="description" rows="10"></textarea>
        <br/><h4>Total Overall Duration</h4>
        <select ng-model="playsheet.spokenword_hours" ng-options="n for n in [] | rangeNoPad:0:24"></select>Hours
      <br/>
        <select ng-model="playsheet.spokenword_minutes" ng-options="n for n in [] | rangeNoPad:0:60"></select>Minutes

      <br/>
    </span>
    </div>

      <div class="podcast_block" >
        <h2>Podcast</h2>
<center>

        <span >
          <p>podcast for show feed: {{playsheet.podcast.channel_id}}.<br/>
            audio file:
            <a ng-show="playsheet.podcast.url" ng-href="{{playsheet.podcast.url}}" target="_blank">{{playsheet.podcast.url}}</a>
            <span ng-show="!playsheet.podcast.url || playsheet.podcast.url == ''">not yet created</span>
            <br/>

            playsheet start time: {{playsheet.start_time | date: 'medium'}}<br/>
            playsheet end time: {{playsheet.end_time | date: 'medium'}}
          </p>

          <hr/>

            episode start time: {{playsheet.podcast.date | date: 'medium'}}<br/>
            episode duration: {{playsheet.podcast.duration }}<br/><br/>


          <hr/>
          <button ng-click="startPodcast()">Start Podcast</button>

          <button ng-click="endPodcast()">End Podcast</button>
          <br/>
          active: <input ng-model="playsheet.podcast.active"><br/>
          Title: <input ng-model="playsheet.podcast.title"><br/>
          Subtitle: <input ng-model="playsheet.podcast.subtitle" ><br/>
          Summary: <br/><textarea ng-model="playsheet.podcast.summary" rows="20" cols="90"></textarea>
        </span>
</center>
</div>

<div class="save_block">

  <center>
          <button class="large-button" ng-click="submit()" ng-hide="submitting">save</button>
          <p ng-show="submitting">Submitting...</p>
          <div class="blocker" ng-hide="songsComplete"> Music Incomplete: (<b>{{socan? 'composer, ':''}}artist</b>, <b>album / release title</b>, and <b>song</b>)
          <span ng-show="socan"><br/>Since it's socan period, you must also set the start time and duration of each track</span> </div>

        <br/>
            <div id="message" ng-show="message.text != '' && message.age < 6 " >{{message.text}}</div>
        </center>
  </div>




  <div class="floating">
  <input type="button" ng-click="samVisible = !samVisible;" value=" SAM "></input><br/>
  <input type="button"
         ng-click="saveDraft();"
         value="{{(saving)? 'saving....':'Save Draft'}}"
         ng-hide="playsheet.status == 2"
      ></input>
</div>

    <div id="sam_picker" ng-show="samVisible">
      <div id="sam_title"><span ng-click="samVisible = false;"> X </span>&nbsp;&nbsp;Sam Plays&nbsp;&nbsp;&nbsp;</div>
      <br/>
      <br/>
      <button ng-click="samRange()">add all plays from {{playsheet.start_time | date:'mediumTime'}} to {{playsheet.end_time |
        date:'mediumTime'}}
      </button>
      <br>

      <div ng-repeat="sam in samRecent" class="sam_row">
        <button ng-click="sam_add(sam);">&nbsp;+&nbsp;

        </button>
        <span class="one_sam">{{sam.song.artist}} - {{sam.song.song}}</span>
      </div>
    </div>


    <div id="totals">
      {{(playsheet.star)? '&#9733&#9733&#9733&#9733' : ''}}
      <div>Cancon 2:<span class='req' ng-class="totals.cancon2 > 35.00 ? 'good': 'bad'; "> {{totals.cancon2 | number : 0.00}}%</span>
        (min 35%)
      </div>
      <div>Cancon 3:<span class='req' ng-class="totals.cancon3 > 12.00 ? 'good': 'bad'; "> {{totals.cancon3 | number : 0.00}}%</span>
        (min 12%)
      </div>
      <div>Hits:<span class='req'
                      ng-class="totals.hits < 10.00 ? 'good': 'bad'; "> {{totals.hits | number : 0.00}}%</span> (max
        10%)
      </div>
      <div>Femcon:<span class='req' ng-class="totals.is_fem > 35.00 ? 'good': 'bad'; "> {{totals.is_fem | number : 0.00}}%</span>
        (min 35%)
      </div>
      <div>New:<span class='req' ng-class="totals.is_playlist > 15.00 ? 'good': 'bad'; "> {{totals.is_playlist | number : 0.00}}%</span>
        (min 15%)
      </div>
      {{(playsheet.star)? '&#9733&#9733&#9733&#9733' : ''}}
    </div>



      <pre class="vars" ng-hide="varshidden">

        {{playsheet}}
          live variables view: <button ng-click="varshidden=true;">go away</button>
              <hr/>
              type: {{playsheet.type}}                  start: {{playsheet.start_time | date:"medium"}}
              show_name: {{playsheet.show_name}}      end:  {{playsheet.end_time | date:"medium"}}
              show_id: {{playsheet.show_id}}
              host: {{playsheet.host}}
              language: {{playsheet.language}}
              crtc: {{playsheet.crtc}}
<br/><hr/>
        SOCAN:{{socan}}
        <br/><hr/>
  PLAYS:
              <span ng-repeat="play in playsheet.plays">{{play}}<br/><br/><br/></span>

      </pre>




    <div class="tracklist_overlay" ng-show="show_the_tracklist_overlay">

      <h3>thanks for submitting your playsheet</h3>

      <h3>If you're done, please <a href="index.php?action=logout" target="_self">click here to log out now</a> </h3>
      A podcast episode is now being created.

      <br/><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <center>{{podcast_status}}</center><br/><br/>
      To modify the episode timing, title, subtitle, or summary,
      <a ng-href="podcasts.php" target="_self">click here to visit your podcast editor page</a>
      <h4>Tracklist:</h4>
      <p>

      <span ng-repeat="play in playsheet.plays">
        {{play.song.artist}} - {{play.song.song}} ({{play.song.title}}) <br/>
      </span>
      </p>
      <br/><br/>
    </div>

    <div class="dark" ng-show="show_the_tracklist_overlay"></div>





  </div>
</div>

</div>



<script type='text/javascript' src="js/jquery-ui/external/jquery/jquery.js"></script>
<script type='text/javascript' src="js/angular.js"></script>
<script type='text/javascript' src="js/jquery-ui/jquery-ui.js"></script>
<script type='text/javascript' src="js/angular/sortable.js"></script>
<script type='text/javascript' src='js/bootstrap/bootstrap.js'></script>
<script type='text/javascript' src='js/bootstrap/ui-bootstrap-tpls-0.12.0-withseconds.js'></script>

<script type="text/javascript">
  var djland = angular.module('djLand', ['ui.bootstrap', 'ui.sortable']);
</script>

<script type="text/javascript" src="js/angular-djland.js"></script>

<script type="text/javascript">

  djland.value('show_id', <?php echo $show_id;?>);
  djland.value('channel_id', <?php echo $channel_id;?>);

</script>
<script src="js/angular/playsheet.js"></script>
</body>
</html>