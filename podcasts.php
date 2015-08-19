<?php

include_once("headers/session_header.php");
require_once("headers/security_header.php");
require_once("headers/function_header.php");
require_once("headers/menu_header.php");

error_reporting(E_ALL);
?>

<html><head><meta name=ROBOTS content="NOINDEX, NOFOLLOW">
  <base href='podcasts.php'>
  <link rel="stylesheet" href='js/bootstrap/bootstrap.min.css'>
  <link rel="stylesheet" href="css/style.css" type="text/css">

  <style type="text/css">

    #popup{
      position:fixed;
      bottom:0;
      right:0;
      width:700px;
      height:100%;
      background-color: #88d2ba;
      overflow:scroll;
      z-index:20;
    }

    #closer{
      position:fixed;
      top:30px;
      right:700px;
      background-color:lightsteelblue;
      color:black;
      cursor:default;
      font-size:2em;
      line-height: 2em;
    }
    #closer:hover{
      color:red;
    }

    .title{
      font-size:1.2em;
    }

    .subtitle{
      font-size:0.9em;
      font-style: italic;
    }
    .podcast_date{
      margin-left:-20px;
      font-color:lightgrey;
      font-size:0.8em;
    }

    .podcast_list_entry{
      padding-left: 35px;
    }

    .button{
      cursor:default;
    }

    .tiny{
      font-size:0.7em;
      width:100%;
      background-color:black;
      overflow-wrap: break-word;
    }

    .lit{
      background-color:darkblue;


    }
    button{
      color:black;
    }

    .large-button{
      height:40px;
      font-size: 1.5em;
    }

    #mainleft a{
      background-color:steelblue;
      padding:2px;
    }
    #mainleft  a:hover{
      color:greenyellow;
    }
    #message{
      font-size:2em;
    }

  </style>


</head>

<body class='wallpaper'>

<?php print_menu();


if (!isset($_GET['id']) && permission_level() >= $djland_permission_levels['staff']){

  echo '<center> <br/><br/>since you are staff, you can edit any show\'s podcasts <br/><br/><br/> ';

  foreach($fshow_name as $i => $v){
    echo '<a href=podcasts.php?id='.$i.'>'.$v.'</a><br/>';
  }

} else {
    echo "choose a podcast to edit";

}

?>


<div ng-app="djLand" id="mainleft" ng-cloak>

  <div ng-controller="episodeList as list">
    <br/><br/>
    <p>{{status}}</p>
    <p>
<!--      <button ng-click='deferred();'> defer me </button> --></p>

    <div >

      <br/>
    </div>

    <div ng-repeat="plodcast in plodcasts track by plodcast.playlist.id" class="podcast_list_entry"
         ng-class="{lit: plodcast.playlist.id === editing.playlist.id}"  >



      <span class="podcast_date">{{plodcast.playlist.start_time | date: "medium"}}</span>


      <p >
        <span ng-show="plodcast.playlist.status == 1"> ( DRAFT ) <br/></span>
        <span ng-show="plodcast.podcast.active == 0"> ( INACTIVE ) <br/></span>

        <span class="title" >{{plodcast.podcast.title? plodcast.podcast.title : '(no title)'}}</span>
          <br/>
        <span class="subtitle" >{{plodcast.podcast.subtitle? plodcast.podcast.subtitle : '(no subtitle)'}}</span>
      </p>
      <br />
      <a ng-href="playsheet.php?action=edit&id={{plodcast.playlist.id}}" target="_self">go to playsheet</a>
      <span >
      <button ng-click="edit_episode(plodcast);" >edit podcast </button>
      </span>
      <br/>


<!--
      <p class="tiny">PLAYLIST:{{plodcast.playlist}}<br>PODCAST:{{plodcast.podcast}}</p>
-->


      <hr/>
    </div>
    <div id="popup"  ng-show="editing">

      <p ng-click="editing = false;" id="closer"> X </p>

      Title:<br/>
      <input ng-model="editing.podcast.title">
      </input><br/>

      Subtitle:<br/>
      <input  ng-model="editing.podcast.subtitle" >
      </textarea><br/>

      Episode Summary:<br/>
      <textarea ng-model="editing.podcast.summary" rows="8">
      </textarea><br/>

	<div ng-controller='datepicker' >

          <input class="date_picker" type="text" datepicker-popup="{{format}}"
                 ng-model="editing.podcast.date"  is-open="opened"
                 ng-required="true" close-text="Close" ng-hide="true"
                 ng-change="$parent.date_change();" />
          <br/>
	<i>broadcasted on <br/></i><b>{{editing.podcast.date | date:'mediumDate'}}</b>
    <button ng-click="open($event)" ng-model="editing.podcast.date"  >change date</button>
    <br/>


  </div>

      Start Time:
      h:<select ng-model="editing.start_hour" ng-options="n for n in [] | range:0:24"
               ng-change="editing.podcast.date.setHours(editing.start_hour);"></select>
      m:<select ng-model="editing.start_minute" ng-options="n for n in [] | range:0:60"
              ng-change="editing.podcast.date.setMinutes(editing.start_minute);"></select>
      s:<select ng-model="editing.start_second" ng-options="n for n in [] | range:0:60"
              ng-change="editing.podcast.date.setSeconds(editing.start_second);"></select>


      End Time:
      h:<select ng-model="editing.end_hour" ng-options="n for n in [] | range:0:24 "
               ng-change="editing.end_time.setHours(editing.end_hour);"></select>
      m:<select ng-model="editing.end_minute" ng-options="n for n in [] | range:0:60"
              ng-change="editing.end_time.setMinutes(editing.end_minute);"></select>
      s:<select ng-model="editing.end_second" ng-options="n for n in [] | range:0:60"
              ng-change="editing.end_time.setSeconds(editing.end_second);"></select>
<br/>
      <br/>
      <i> duration: </i>
      <b>{{Math.floor( editing.podcast.duration /60/60 )  | number:0 }}:{{(editing.podcast.duration /60)%60 | number:0 | pad:2}} , {{(editing.podcast.duration )%60 | pad:2 }}s</b>

      <br/>

      <button ng-click="preview_start()">preview start</button>
      <button ng-click="preview_end()">preview end</button>
      <button ng-click="stop_sound()">stop playback</button>

      <br/><br/>audio file:<br/>
      <input ng-model="editing.podcast.url" readonly>
      </input><br/>

      <span id="message">{{message}}</span><br/><br/>


      <button class='large-button' ng-click="save(editing.podcast);" > save </button>
      <button ng-show="{{adminStatus}}" ng-click="deactivate(editing.podcast);"> deactivate </button>
<!--      <button class='large-button' ng-click="recreate_audio(editing.podcast);" > recreate audio </button> -->
    </div>
  </div>

</div>

<script type='text/javascript' src="js/jquery-ui/external/jquery/jquery.js"></script>
<script type='text/javascript' src="js/angular.js"></script>
<script type='text/javascript' src="js/jquery-ui/jquery-ui.js"></script>
<script type='text/javascript' src="js/soundmanager2.js"></script>
<script type='text/javascript' src="js/angular/sortable.js"></script>
<script type='text/javascript' src='js/bootstrap/bootstrap.js'></script>
<script type='text/javascript' src='js/bootstrap/ui-bootstrap-tpls-0.12.0-withseconds.js'></script>

<script type="text/javascript">
  var djland = angular.module('djLand', ['ui.bootstrap']);

</script>
<script type="text/javascript" src="js/angular-djland.js"></script>
<script type="text/javascript" >
djland.value('timezone_offset',<?php echo date_offset_get(new DateTime); ?>); //timezone offset is in seconds


djland.controller('episodeList', function($scope, apiService, $location, $filter, archiveService, MAX_PODCAST_DURATION_HOURS, adminStatus){
// GET id FROM list provider...
    $scope.Math = window.Math;

    $scope.plodcasts = [];

    $scope.editing  = false;

    $scope.deferred = function(){
      $scope.status = 'this will take a while...';
      apiService.def()
          .then(function(result){
            $scope.status = 'done:'+result.data;
          }.catch(function(result){
                $scope.status = 'did not work';
              }))
    }

  $scope.alerts = function(){
    alert($scope.editing.start_hour);
  }
    $scope.load = function() {

      if (show_id = $location.search().id){

        $scope.status = 'loading playlists and podcasts...';

        apiService.getPlodcasts(show_id)
            .then(function (response) {

              $scope.plodcasts = [].concat(response.data);

              $scope.status = 'select a plodcast';

            }).catch(function (response) {
              $scope.status = response.data;
            });
      }

    }

    $scope.load();

    $scope.edit_episode = function (plodcast){
      $scope.editing = angular.copy(plodcast);
      $scope.editing.podcast.date = new Date($scope.editing.podcast.date);

      $scope.editing.start_hour = $filter('pad')($scope.editing.podcast.date.getHours(),2);
      $scope.editing.start_minute = $filter('pad')($scope.editing.podcast.date.getMinutes(),2);
      $scope.editing.start_second = $filter('pad')($scope.editing.podcast.date.getSeconds(),2);

      var calculate_end_from_start_and_duration = function(){
        $scope.editing.end_time = new Date($scope.editing.podcast.date.getTime() + $scope.editing.podcast.duration*1000);

        $scope.editing.end_hour = $filter('pad')($scope.editing.end_time.getHours(),2);
        $scope.editing.end_minute = $filter('pad')($scope.editing.end_time.getMinutes(),2);
        $scope.editing.end_second = $filter('pad')($scope.editing.end_time.getSeconds(),2);

      }

      calculate_end_from_start_and_duration();

      $scope.date_change = function(){
        calculate_end_from_start_and_duration();

      }


      var recalculate_duration = function(){
        $scope.editing.podcast.duration = ($scope.editing.end_time.getTime() - $scope.editing.podcast.date.getTime())/1000 ;

        if ($scope.editing.podcast.duration < 0){

          $scope.editing.podcast.duration += 24*60*60;
          calculate_end_from_start_and_duration();

        } else if ($scope.editing.podcast.duration > MAX_PODCAST_DURATION_HOURS*60*60){
          var diff = $scope.editing.podcast.duration - MAX_PODCAST_DURATION_HOURS*60*60;

          $scope.editing.podcast.duration -= diff;
          calculate_end_from_start_and_duration();

        }

        if($scope.editing.podcast.duration == MAX_PODCAST_DURATION_HOURS*60*60){
          $scope.message = 'maximum duration of a podcast is '+MAX_PODCAST_DURATION_HOURS+' hours.';
        } else {
          $scope.message = '';
        }
      }


      $scope.$watch('editing.podcast.date', function(){
        recalculate_duration();
      }, true);

      $scope.$watch('editing.end_time', function(){
        recalculate_duration();
      }, true);
    }

    $scope.save = function(podcast){

      $scope.message = 'saving...';

      apiService.saveEpisodeData(podcast)
          .then(function(response){
            $scope.message = 'saved. now updating your feed...';

            apiService.updatePodcast($scope.editing.podcast,true)
                .then(function(result){

                  $scope.message = 'done updating the podcast'//result;

                  $scope.editing.podcast.url = result.data.new_audio_url;
                  $scope.load();




                }).catch(function(result){
                  $scope.message = 'error:' + result.data;
                });
          }).catch(function(response){
            console.error(response.data);
            $scope.message = 'sorry, saving did not work';
          });
    };

  $scope.deactivate = function(podcast){
    podcast.active = 0;

    apiService.saveEpisodeData(podcast)
        .then(function(response){
          $scope.message = 'podcast deactivated. now updating feed...';

          apiService.updatePodcast(podcast, false)
              .then(function(){
                $scope.message = ' feed updated ';

                $scope.load();

              })
        })

  }


$scope.adminStatus = adminStatus;



  var basic_sound_options = {
    debugMode:false,
    useConsole:false,
    autoLoad:true,
    multiShot:false,
    volume:70,
    stream:true
  };

  sm = new SoundManager();

  sm.setup({
    debugMode:false
  });

  $scope.load_and_play_sound = function(url){
    var the_scope = this;
    if(typeof($scope.sound) != 'undefined') {
      $scope.sound.destruct();
    }
//    sm.stopAll();

    $scope.message = 'playing ...';
    $scope.sound = sm.createSound(
        angular.extend(basic_sound_options,{
          autoPlay:true,
          url:url,
          onfinish:function(){
            $scope.message = '';
          },

          whileplaying: function() {
            $scope.message = 'playing ...';
            if (this.duration == 0){
              $scope.message = 'sorry, preview not available.';
            }
          }
        })
    );

  };

  $scope.preview_start = function(){

    var start_prev_end = new Date($scope.editing.podcast.date);
    start_prev_end.setSeconds(start_prev_end.getSeconds() + 8);
    var sound_url = archiveService.url($scope.editing.podcast.date, start_prev_end);


    $scope.load_and_play_sound(sound_url);
  };

  $scope.preview_end = function(){
    var end_date = $scope.editing.podcast.date.setMilliseconds(0) + $scope.editing.podcast.duration*1000;
    var end_prev_start = new Date(end_date);
    end_prev_start.setSeconds(end_prev_start.getSeconds() - 8);
    var sound_url = archiveService.url(end_prev_start, end_date);
    $scope.load_and_play_sound(sound_url);

  }

  $scope.stop_sound = function(){
    sm.stopAll();

    $scope.message = '';
  }

  });

  djland.factory('archiveService', function($filter) {
    return {
      url: function(date, end) {

        return 'http://archive.citr.ca/py-test/archbrad/download?'+
            'archive=%2Fmnt%2Faudio-stor%2Flog'+
            '&startTime='+$filter('date')(date,'dd-MM-yyyy HH:mm:ss')+
            '&endTime='+$filter('date')(end,'dd-MM-yyyy HH:mm:ss');

      }
    };
  })

      <?php if (permission_level() >= $djland_permission_levels['staff']){
        echo "djland.value('adminStatus',true);";
      } else {
        echo "djland.value('adminStatus',false);";

      }?>


</script>


