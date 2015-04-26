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

  </style>


</head>

<body>

<?php print_menu(); ?>

<div ng-app="djLand" id="mainleft">

  <div ng-controller="episodeList">
    <br/><br/>
    <p>{{status}}</p>

    <div ng-repeat="playlist in playlists track by playlist.ps_id">
      Title: {{playlist.title}}<br/>
      Subtitle: {{playlist.subtitle}}<br/>
      <a ng-href="playsheet.php?action=edit&id={{playlist.ps_id}}" target="_self">edit playsheet</a> |
      <a ng-click="edit_episode(playlist.ep_id);">edit podcast</a>
      <br/>
      <hr/>
    </div>

    <!--
    <div ng-controller="episodeCtrl" ng-repeat="episode in episodes track by episode.id">

      {{episodeData.title}} - {{episodeData.subtitle}}<br/>

    </div>-->

    <div id="popup" ng-controller="episodeCtrl" ng-repeat="episode in editing_episode" ng-show="editing">
      <p ng-click="editing = false;" id="closer"> X </p>

      Episode Title:<br/>
      <input ng-model="episodeData.title">
      </input><br/>

      Subtitle:<br/>
      <input  ng-model="episodeData.subtitle" >
      </textarea><br/>

      Episode Summary:<br/>
						<textarea ng-model="episodeData.summary" rows="25">
						</textarea><br/>

      Date:<br/>
      <input ng-model="episodeData.date">
      </input><br/>

      URL:<br/>
      <input ng-model="episodeData.url">
      </input><br/>

      message:{{message}}<br/>


      <button ng-click="save(episodeData);" >save info (tba)</button>
      {{episode}}

    </div>
  </div>

</div>

<script type="text/javascript" src="js/angular.js"></script>
<script type="text/javascript">
  var djland = angular.module('djLand', []);
</script>
<script type="text/javascript" src="js/angular-djland.js"></script>


