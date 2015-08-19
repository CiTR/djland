<?php

session_start();
require_once("headers/security_header.php");
require_once("headers/function_header.php");
require_once("headers/menu_header.php");

?>

<html>
	<head>
		<meta name=ROBOTS content="NOINDEX, NOFOLLOW">
		<base href='shows.php'>
		<link rel="stylesheet" href="css/style.css" type="text/css">

		<style type="text/css">

			#mainpodcast {
				font-size:1.10em;
				line-height:1.3em;
			}

			#mainpodcast input, #mainpodcast textarea{
				width:100%;
				margin: 5px;
				padding:5px;
			}

			#mainpodcast input{
				font-size: 1em;
			}
			#mainpodcast textarea{
				font-size:0.77em;
			}
		</style>
	</head>

	<body>

		<?php print_menu(); ?>

		<div ng-app="djLand" id="mainpodcast" ng-cloak>

			<div ng-controller="episodeSingle" >
				{{status}}<hr/>
				{{episode}}<hr/>

				<div ng-controller="episodeCtrl" ng-repeat="episode in episodes" class="form_wrap show_form">

					<h3>editing podcast episode</h3>

					<h3>{{episodeData.title}}</h3>

					Episode Title:<br/>
					<input ng-model="episode.title">
					</input><br/>

					Subtitle:<br/>
					<input  ng-model="episode.subtitle" >
					</textarea><br/>

					Episode Summary:<br/>
					<textarea ng-model="episode.summary" rows="25">
					</textarea><br/>

					Date:<br/>
					<input ng-model="episode.date">
					</input><br/>

					URL:<br/>
					<input ng-model="episode.url">
					</input><br/>

					message:{{message}}<br/>


					<button ng-click="save(episode);" >save info (tba)</button>
					<textarea cols="100" rows="20">{{episode}}</textarea>
				</div>
			</div>
		</div>
			<script src="js/angular.js"></script>
			<script type="text/javascript">
				var djland = angular.module('djLand', []);
			</script>
			<script src="js/angular-djland.js"></script>
	</body>
</html>
