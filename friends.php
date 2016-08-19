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
			<h2>Friends of CiTR</h2>
			<button class='center' id='save_friends' type='button' ng-click='friends.save()'>Save</button>
			<ul id='friends' class='clean-list'>
				<li ng-repeat='(id,friend) in friends.list track by $index'>
					<hr/>
					<h3 class='text-left'>{{friend.name}}</h3>

					<div class='col1'>Name<input ng-model='friend.name' placeholder='name'><button type='button' ng-click='friends.delete($index)'>Delete This Friend</button></div>
					<div class='friend_info'>
						<div class='col1 double-padded-top'>
							<div class='col2'>Address<input class='padded-left' ng-model='friend.address' placeholder='address'></div>
							<div class='col2'>Website<input class='padded-left' ng-model='friend.website' placeholder='website'></div>
						</div>
						<div class='col1 double-padded-top'>
							<div class='col2'>Phone<input class='padded-left' ng-model='friend.phone' placeholder='phone'></div>
							<div class='col2'>Discount<input class='padded-left' ng-model='friend.discount' placeholder='discount'></div>
						</div>
					</div>
					<div class='left double-padded-top'>
		                <input class='file{{friend.id}}' type="file" ng-model-instant/>
		                <button type="button" ng-click="friends.imageUpload(friend.id,friend.name)">Upload</button>
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
