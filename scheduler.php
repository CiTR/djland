<?php include_once('headers/menu_header.php'); ?>
<html>
	<head>
		<link rel='stylesheet' href='js/bootstrap/bootstrap.min.css'></script>
		<link rel="stylesheet" href="css/style.css" type="text/css">
	</head>
	<body class='wallpaper'>
		<?php 
		print_menu(); 
		$one_day = 60*60*24;
		?>
		<select class='invisible' id="ad-template"></select>
		<select class='invisible' id="psa-template"></select>
		<select class='invisible' id="ubc-template"></select>
		<select class='invisible' id="community-template"></select>
		<select class='invisible' id="timely-template"></select>
		<select class='invisible' id="id-template"></select>
		<select class='invisible' id="promo-template"></select>

		<ul id ='tab-nav'>
			<li class='nodrop inactive-tab nav-left invisble'> < </li>
			<li class='nodrop inactive-tab nav-right'> > </li>
			<li class = 'tab nodrop active-tab' name="<?php echo date('Y/m/d H:i:s'); ?>" value="0"> Today</li>
			<li class = 'tab nodrop inactive-tab' name="<?php echo date('Y/m/d H:i:s',strtotime('tomorrow')); ?>" value="1">Tomorrow</li>
			<?php for($i = 2; $i < 14; $i++) : ?>
				<li class = "tab nodrop inactive-tab <?php echo ($i >= 10 ? 'invisible' : '');?>" name="<?php echo date('Y/m/d H:i:s',strtotime('today') + $one_day*$i); ?>" value="<?php echo $i; ?>"> <?php echo date('Y/m/d',strtotime('today') + $one_day*$i); ?></li>
			<?php endfor; ?>

		</ul> 
		<div class='grey loadingbar_container'>
			<div class='text-center'><img class='rounded' width ='300' height='20' src='images/loading.gif'/></div>
		</div>
		<ul class='schedule grey'>

		</ul>
		<script type='text/javascript' src='js/jquery-1.11.3.min.js'></script>
		<script type='text/javascript' src='js/ads/schedule.js'></script>
		<script type='text/javascript' src='js/ads/ad_schedule.js'></script>
	</body>
</html>