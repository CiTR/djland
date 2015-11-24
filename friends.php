<html ng-app='djland.friends' >
	<?php require_once("headers/menu_header.php"); ?>
	<head>
		<link rel='stylesheet' href='js/bootstrap/bootstrap.min.css'></script>
		<link rel="stylesheet" href="css/style.css" type="text/css">
	</head>
	<body class='wallpaper' ng-controller='friendsController as friends'>
		<?php print_menu(); ?>
		<div class='grey loadingbar_container'>
			<div class='text-center' ng-show='friends.loading'><img class='rounded' width ='300' height='20' src='images/loading.gif'/></div>
		</div>
		<div class='grey wrapper'>
			<ul id='friends' class='clean-list'>
				<li ng-repeat='(id,friend) in friends.list track by $index'>
					<h3 class='text-left'>{{friend.name}}</h3>

					<div class='col1'><input ng-model='friend.name' placeholder='name'><button type='button' ng-click='friends.delete($index)'>Delete This Friend</button></div>
					<div class='col2'>
						<div class='col1 double-padded-top'>
							<div class='col2'>Name<input class='padded-left' ng-model='friend.address' placeholder='address'></div>
							<div class='col2'>Website<input class='padded-left' ng-model='friend.website' placeholder='website'></div>
						</div>
						<div class='col1 double-padded-top'>
							<div class='col2'>Phone<input class='padded-left' ng-model='friend.phone' placeholder='phone'></div>
							<div class='col2'>Discount<input class='padded-left' ng-model='friend.discount' placeholder='discount'></div>
						</div>
					</div>
					<div class='left'>
						<div ng-controller="FileUploadCtrl" class='text-center'>		      		                   
		                    <input type="file" ng-model-instant id="fileToUpload" multiple onchange="angular.element(this).scope().setFiles(this)" />

		                    <div  id="dropbox" class="friend_dropbox" ng-class="dropClass"><span>{{dropText}}</span></div>
		                    <button type="button" ng-click="uploadFile()">Upload</button>
		                    <div ng-show="files.length">
		                        <div ng-repeat="file in files.slice(0)">
		                            <span>{{file.webkitRelativePath || file.name}}</span>
		                            (<span ng-switch="file.size > 1024*1024">
		                            <span ng-switch-when="true">{{file.size / 1024 / 1024 | number:2}} MB</span>
		                            <span ng-switch-default>{{file.size / 1024 | number:2}} kB</span>
		                            </span>)
		                        </div>
	                        </div>
		                    <div ng-show="progressVisible">
		                        <div class="percent">{{progress}}%</div>
	                            <div class="progress-bar">
	                                <div class="uploaded" ng-style="{'width': progress+'%'}"></div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	                <div class='friend_image left'>
                    	<img src="{{friend.image_url}}" alt='Image'/>
                	</div>

				</li>
			</ul>
			<div class='col1 text-center double-padded-top grey'>
				<button type='button' ng-click='friends.add()'>Add a friend</button>
			</div>
		</div>
		<script type='text/javascript' src='js/angular.js'></script>
		<script type='text/javascript' src='js/jquery-1.11.3.min.js'></script>
		<script type='text/javascript' src='js/api.js'></script>
		<script type='text/javascript' src='js/friends/friends.js'></script>
	</body>
</html>