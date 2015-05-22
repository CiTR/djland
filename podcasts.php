<?php

session_start();
require_once("headers/security_header.php");
require_once("headers/function_header.php");
require_once("headers/menu_header.php");

?>

<html><head><meta name=ROBOTS content="NOINDEX, NOFOLLOW">
  <base href='podcasts.php'>
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
      top:0;
      right:700px;
      background-color:grey;
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
      background-color:lightblue;

    }

  </style>


</head>

<body>

<?php print_menu(); ?>

<div ng-app="djLand" id="mainleft">

  <div ng-controller="episodeList as list">
    <br/><br/>
    <p>{{status}}</p>

    <div >

      <br/>
    </div>

    <div ng-repeat="plodcast in plodcasts track by plodcast.playlist.id" class="podcast_list_entry"
         ng-class="{lit: plodcast.playlist.id === editing.playlist.id}"  >



      <span class="podcast_date">{{plodcast.playlist.start_time | date: "medium"}}</span>

      <p >
        <span ng-show="plodcast.playlist.status == 1">( DRAFT )<br/></span>
        <span class="title" >{{plodcast.podcast.title? plodcast.podcast.title : '(no title)'}}</span>
          <br/>
        <span class="subtitle" >{{plodcast.podcast.subtitle? plodcast.podcast.subtitle : '(no subtitle)'}}</span>
      </p>
      <br />
      <a ng-href="playsheet.php?action=edit&id={{plodcast.playlist.id}}" target="_self">go to playsheet</a>
      <span >
      <a ng-click="edit_episode(plodcast);" class="button">edit podcast</a>
      </span>
      <br/>



      <p class="tiny">PLAYLIST:{{plodcast.playlist}}<br>PODCAST:{{plodcast.podcast}}</p>



      <hr/>




    </div>


    <div id="popup"  ng-show="editing">

      <p ng-click="editing = false;" id="closer"> X </p>

      Episode Title:<br/>
      <input ng-model="editing.podcast.title">
      </input><br/>

      Subtitle:<br/>
      <input  ng-model="editing.podcast.subtitle" >
      </textarea><br/>

      Episode Summary:<br/>
      <textarea ng-model="editing.podcast.summary" rows="25">
      </textarea><br/>

      Date:<br/>
      <input ng-model="editing.podcast.date">
      </input><br/>

      URL:<br/>
      <input ng-model="editing.podcast.url">
      </input><br/>

      message:{{message}}<br/>


      <button ng-click="save(editing.podcast);" >save info (tba)</button>



      {{episode}}

    </div>
  </div>

</div>

<script type="text/javascript" src="js/angular.js"></script>
<script type="text/javascript">
  var djland = angular.module('djLand', []);

</script>
<script type="text/javascript" src="js/angular-djland.js"></script>
<script type="text/javascript" >


  djland.controller('episodeList', ['$scope','apiService','$location', function($scope, apiService, $location){
// GET id FROM list provider...

    $scope.status = 'loading playlists and podcasts...';
    $scope.plodcasts = [];

    $scope.editing  = false;

    $scope.load = function(){

      apiService.getPlodcasts()
          .then(function(response){

            $scope.plodcasts = [].concat(response.data);

            $scope.status = 'select a plodcast';

          });



    }

    $scope.load();

    $scope.edit_episode = function (plodcast){
        $scope.editing = angular.copy(plodcast);
    }


    $scope.save = function(data){

      $scope.message = 'saving...';

      apiService.saveEpisodeData(data)
          .then(function(response){
            $scope.message = 'saved. now creating audio...';
            var audioData = {};
            audioData.start = new Date(data.date);
            audioData.start = audioData.start.getTime()/1000;
            audioData.end = audioData.start + 60 * 60 + 1;
            audioData.show = 'testing';
            apiService.createPodcastAudio(audioData)
                .success(function(result){
                  $scope.message = result;

                });
            $scope.message = response.data.message;
            $scope.load();
          }).catch(function(response){
            console.error(response.data);
            $scope.message = 'sorry, saving did not work';
          });
    };

  }]);



  djland.controller('episodeCtrl', ['$scope','apiService', function($scope, apiService){

    $scope.closeEditor = function(){

      $scope.$parent.$parent.editing = false;
    }

    $scope.episodeData = $scope.$parent.episode;





  }]);

</script>


