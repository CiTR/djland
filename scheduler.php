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
		<ul id ='tab-nav'>
			<li class='nodrop inactive-tab nav-left invisble'> < </li>
			<li class='nodrop inactive-tab nav-right'> > </li>
			<li class = 'tab nodrop active-tab' name="<?php echo date('Y/m/d'); ?>" value="<?php strtotime('today'); ?>"> Today</li>
			<li class = 'tab nodrop inactive-tab' name="<?php echo date('Y/m/d',strtotime('tomorrow')); ?>" value="<?php strtotime('tomorrow'); ?>">Tomorrow</li>
			<?php for($i = 0; $i < 12; $i++) : ?>
				<li class = "tab nodrop inactive-tab <?php echo ($i >= 8 ? 'invisible' : '');?>" name="<?php echo date('Y/m/d',strtotime('today') + $one_day*$i); ?>" value="<?php strtotime('today') + $one_day*$i; ?>"> <?php echo date('Y/m/d',strtotime('today') + $one_day*$i); ?></li>
			<?php endfor; ?>

		</ul> 
		<div class='schedule grey'>
			schedule
		</div>
	</body>
</html>