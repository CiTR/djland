<?php

include_once("headers/session_header.php");
require_once("headers/security_header.php");
require_once("headers/functions.php");
require_once("headers/menu_header.php");

error_reporting(E_ALL);
?>
    <html><head><meta name=ROBOTS content="NOINDEX, NOFOLLOW">
    <base href='podcasts.php'>
    <link rel="stylesheet" href='js/bootstrap/bootstrap.min.css'>
    <link rel="stylesheet" href="css/style.css" type="text/css">
</head>

<body class='wallpaper'>
 <script type='text/javascript' src="js/jquery-1.11.3.min.js"></script>
<?php print_menu();

if(!isset($_GET['id'])){
    ?>
    <script type="text/javascript">
        $(document).ready ( function() {
            $(".clickable-row").click(function() {
                window.document.location = $(this).data("href");
            });
        });
    </script>
    <div id='wrapper'>
            <?php
            $shows = getPodcasts($_SESSION['sv_id']);
            if(count($shows) > 0){
                echo "<table class='table-condensed table-hover'><th>Show Name</th><th>Number of Episodes</th>";
                foreach($shows as $show){
                    echo "<tr class='clickable-row' data-href='podcasts.php?id=".$show['id']."'><td>".$show['name']."</td><td>(".$show['num_episodes']." episodes)</td></tr>";
                }
                echo "</table></div>";
            }else{
                echo "You have no shows assigned to this account. Please ask a staff member to assign you to your show";
            }

}else{

?>

    <script type='text/javascript' src="js/jquery-ui-1.11.3.min.js"></script>
    <script type='text/javascript' src="js/angular.js"></script>
    <script type='text/javascript' src="js/soundmanager2.js"></script>
    <script type='text/javascript' src='js/bootstrap/bootstrap.js'></script>
    <script type='text/javascript' src='js/bootstrap/ui-bootstrap-tpls-0.12.0-withseconds.js'></script>
    <script type='text/javascript' src="js/api.js"></script>
    <script type='text/javascript' src="js/utils.js"></script>
    <script type='text/javascript' src="js/podcast/podcasts.js"></script>

    <script type="text/javascript">
        var show_id = <?php echo $_GET['id']; ?>;
        var member_id = <?php echo $_SESSION['sv_id']; ?>;
    </script>

    <div ng-app="djland.podcasts" id="mainleft" ng-cloak >

        <div ng-controller="episodeList as list">
            <!-- <div class='text-center'>{{list.status}}</div> -->
            <div class='text-center loading' ><img ng-show='list.loading' class='rounded' width ='300' height='20' src='images/loading.gif'/></div>
            <div id='wrapper'>

                <!-- Left Side Episode List Code -->
                <div class='scroll <?php if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'firefox') > 0 ){echo " firefox'"; }?>' scrolly='!list.loading ? list.load():""'>
                    <div ng-repeat="episode in list.episodes track by $index" class="podcast_list_entry" ng-class="{lit: episode.podcast.id === list.editing.podcast.id}"  >
                        <span class="podcast_date">{{episode.playsheet.start_time | date: "medium"}}</span>
                        <p>
                            <span ng-show="episode.playsheet.status == 1"> ( DRAFT ) <br/></span>
                            <span ng-show="episode.podcast.active == 0"> ( INACTIVE ) <br/></span>
                            <span class="title" >{{episode.podcast.title || episode.playsheet.title  ? episode.playsheet.title || episode.podcast.title : '(no title)'}}</span>
                            <br/>
                            <span class="subtitle" >{{episode.podcast.summary || episode.playsheet.summary ? episode.playsheet.summary || episode.podcast.summary: '(no summary)'}}</span>
                        </p>
                        <div audio source='episode.podcast.url'></div>
                        <br />
                        <div ng-show='episode.playsheet'><a ng-href="playsheet_angular.php?id={{episode.playsheet.id}}{{episode.playsheet.socan? '&socan=true':''}}" target="_self">go to playsheet</a>
                        <span >
                            <button ng-click="list.edit_episode(episode);" >edit podcast </button>
                        </span>
                        </div>
                        <hr/>
                    </div>
                </div>

                <!-- Right Side Editor Popup Code -->

                <div id="popup"  ng-show="list.editing">
                    <p ng-click="list.editing = false;" id="closer"> X </p>
                    <h4 class='text-left'>Episode Title</h4>
                    <input class='col1' ng-model="list.editing.playsheet.title"/>
                    <h4 class='text-left'>Episode Summary</h4>
                    <textarea ng-model="list.editing.playsheet.summary" rows="8">
                    </textarea>

                    <h4 class='text-left double-padded-top'>Broadcast Date</h4>

                    <div>
                        <h5>Start Time : {{list.editing.playsheet.start_time | date:'yyyy/MM/dd HH:mm:ss'}}</h5>
                        <div ng-controller='datepicker as date' >
                            <input class="date_picker" type="text" datepicker-popup="yyyy/MM/dd HH:mm:ss"
                               ng-model="list.editing.playsheet.start_time"  is-open="date.opened"
                               ng-required="true" close-text="Close" ng-hide="true"
                               ng-change="date.date_change();" />

                            <button ng-click="date.open($event)"  >Change Date</button>
                            h:<select ng-model="list.editing.start_hour" ng-options="n for n in [] | range:0:24"
                                      ng-change="list.updateStart()"></select>
                            m:<select ng-model="list.editing.start_minute" ng-options="n for n in [] | range:0:60"
                                      ng-change="list.updateStart()"></select>
                            s:<select ng-model="list.editing.start_second" ng-options="n for n in [] | range:0:60"
                                      ng-change="list.updateStart()"></select>
                        </div>


                    </div>
                    <div>
                        <h5>End Time: {{list.editing.playsheet.end_time | date:'yyyy/MM/dd HH:mm:ss'}}</h5>
                        <div ng-controller='datepicker as date' >
                            <input class="date_picker" type="text" datepicker-popup="yyyy/MM/dd HH:mm:ss"
                               ng-model="list.editing.playsheet.end_time"  is-open="date.opened"
                               ng-required="true" close-text="Close" ng-hide="true"
                               ng-change="date.date_change();" />
                            <button ng-click="date.open($event)" >Change Date</button>
                            h:<select ng-model="list.editing.end_hour" ng-options="n for n in [] | range:0:24 "
                                      ng-change="list.updateEnd()"></select>
                            m:<select ng-model="list.editing.end_minute" ng-options="n for n in [] | range:0:60"
                                      ng-change="list.updateEnd()"></select>
                            s:<select ng-model="list.editing.end_second" ng-options="n for n in [] | range:0:60"
                                      ng-change="list.updateEnd()"></select>
                        </div>
                    </div>


                    <h4 class='text-left double-padded-top'>Episode Duration</h4>
                    <b>{{list.Math.floor( list.editing.podcast.duration /60/60 )  | number:0 }}h:{{(list.editing.podcast.duration /60)%60 | pad:2}}m: {{(list.editing.podcast.duration )%60 | pad:2 }}s</b>

                    <div class='double-padded-top'>
		            <button ng-click="list.preview_start()">preview start</button>
		            <button ng-click="list.preview_end()">preview end</button>
		            <button ng-click="list.stop_sound()">stop playback</button>
		            <div id='elapsed' ng-show='list.playing'></div>
                    </div>

                    <h4 class='text-left double-padded-top'>Audio File Link</h4>
                    <input class='col1' ng-model="list.editing.podcast.url" readonly/>
                    
			<div class='col1'>Make this podcast inactive<input type='checkbox' ng-model="list.editing.podcast.active" ng-show="list.is_admin==true"/></div>
                    <span id="message">{{message}}</span><br/><br/>
                    <button ng-click="list.save(list.editing.podcast);" >Save Episode</button>
                    

                    <!--      <button class='large-button' ng-click="recreate_audio(editing.podcast);" > recreate audio </button> -->
                </div>
            </div>
        </div>
    </div>


<?php
}
