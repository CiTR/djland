<?php 
	require_once("headers/session_header.php");
	include_once("headers/security_header.php");
	include_once("headers/socan_header.php");
	include_once("headers/menu_header.php");
	include_once("headers/functions.php");

	$playsheet_id = isset($_GET['id']) ? $_GET['id'] : '-1';
	$member_id = $_SESSION['sv_id'];
	$username = $_SESSION['sv_username'];
	$socan = isset($_GET['socan'])? ($_GET['socan']=='true'?true:false) : socanCheck($db);
?>
<html>
	<head>
		<link rel="stylesheet" href="css/style.css" type="text/css">	
	</head>
	<body class='wallpaper'>
		<?php print_menu(); ?>
		<div class='hidden'>
			<div id='playsheet_id'><?php echo $playsheet_id ?></div>
			<div id='member_id' ><?php echo $member_id ?></div>
			<div id='username' ><?php echo $username; ?></div>
			<div id='socan'><?php echo $socan ?></div>
		</div>
		<div id='wrapper'>
			<FORM name='metadata'>
				<div class='col1'>
					<div class='col2'>
						<!-- Left Side Playsheet Meta -->
						<div class='col1'>
							Show: 
							<select id='show_select' name='show'>
							</select>
						</div>
						<div class='col1'>
							Hosts: <input id='host' name='host' >
						</div>
						<div class='col1'>
							Show Type:
							<select name='type' id='type'>
								<option value="Live">Live</option>
								<option value="Syndicated">Syndicated</option>
					          	<option value="Rebroadcast">Rebroadcast</option>
					          	<option value="Simulcast">Simulcast</option>
							</select>
							<select id='rebroadcast' class='rebroadcast hidden'>
							</select>
							<button id='load_rebroadcast' class='rebroadcast hidden'>Load this playsheet</button>
						</div>
						<div class='col1'>
							Language: <input id='lang' name='lang' value='English'>
						</div>
						<div class='col1'>
							CRTC Category:
							<select id='crtc' name='crtc'>
								<option value='30'>30</option>
								<option value='20'>20</option>
							</select>
						</div>
					</div>
					<div class='col2'>
						<!-- Right Side Playsheet Meta -->
						<div class='col1'>
							<div class='col1'>
								<button type='button' id='start_show'>Start Episode</button>
								<div class='right'>
									Start: 
									<input id='start_date'>
									[<select id='start_hour'>
										<?php for($i=0;$i<23;$i++){ echo "<option value='".str_pad($i,2,"0",STR_PAD_LEFT)."'>".str_pad($i,2,"0",STR_PAD_LEFT)."</option>"; } ?>
									</select>
									:
									<select id='start_minute'>
										<?php for($i=0;$i<59;$i++){ echo "<option value='".str_pad($i,2,"0",STR_PAD_LEFT)."'>".str_pad($i,2,"0",STR_PAD_LEFT)."</option>"; } ?>
									</select>
									:
									<select id='start_second'>
										<?php for($i=0;$i<59;$i++){ echo "<option value='".str_pad($i,2,"0",STR_PAD_LEFT)."'>".str_pad($i,2,"0",STR_PAD_LEFT)."</option>"; } ?>
									</select>]
									
								</div>
							</div>
							<div class='col1'>
								<div class='right'>
									End: 
									<input id='end_date'>
									[<select id='end_hour'>
										<?php for($i=0;$i<23;$i++){ echo "<option value='".str_pad($i,2,"0",STR_PAD_LEFT)."'>".str_pad($i,2,"0",STR_PAD_LEFT)."</option>"; } ?>
									</select>
									:
									<select id='end_minute'>
										<?php for($i=0;$i<59;$i++){ echo "<option value='".str_pad($i,2,"0",STR_PAD_LEFT)."'>".str_pad($i,2,"0",STR_PAD_LEFT)."</option>"; } ?>
									</select>
									:
									<select id='end_second'>
										<?php for($i=0;$i<59;$i++){ echo "<option value='".str_pad($i,2,"0",STR_PAD_LEFT)."'>".str_pad($i,2,"0",STR_PAD_LEFT)."</option>"; } ?>
									</select>]
								</div>
								<button type='button' id='end_show'>End Episode</button>
							</div>
							<div class='col1 double-padded-top'>
								<div class='text-center'>
									Spokenword Duration: 
								<select class='required'>
									<?php for($i=0;$i<23;$i++){ echo "<option value='".str_pad($i,2,"0",STR_PAD_LEFT)."'>".str_pad($i,2,"0",STR_PAD_LEFT)."</option>"; } ?>
								</select>Hours
	      						<select class='required'>
	      							<option value='null'></option>
	      							<?php for($i=0;$i<59;$i++){ echo "<option value='".str_pad($i,2,"0",STR_PAD_LEFT)."'>".str_pad($i,2,"0",STR_PAD_LEFT)."</option>"; } ?>
	      						</select>Minutes
	      					</div>
						</div>
					</div>
				</div>
				<div class='col1'>
					<!-- Center Episode Description -->
					<h4>Episode Title</h4>
					<input name='title' class='wideinput required'/>
					<h4>Episode Description</h4>
					<textarea name='description' class='fill required'></textarea>
				</div>
			</FORM>
			<FORM name='playitems'>
				<div class='col1'>
					<table class='playitem'>
						<tr class='music_row_heading playitem border'>
							<th class='side-padded'>#</th>
							<th><input value="Artist" readonly></input></th>
							<th><input value="Song" readonly></input></th>
							<th><input value="Album" readonly  tooltip-side:'bottom' tooltip="{{c.playsheet.help.album}}" ng-class="{socan: c.playsheet.socan }"></input></th>
							<?php if($socan==true): ?>
							<th><input ng-class="{socan: c.playsheet.socan}" value="Composer" readonly tooltip="{{compHelp}}" ng-class="{socan: c.playsheet.socan }"></input></th>
							<th><input value="Time Start(H:M)" tooltip-placement:'bottom' tooltip="{{c.playsheet.help.timeHelp1}}" class='socantiming'></input></th>
							<th><input value ="Duration(M:S)"tooltip="{{timeHelp2}}" class='socantiming'></input></th>
							<?php endif; ?>
							<th><button tooltip="{{c.playsheet.help['playlist']}}" class="box playlist filled pad-top"></button></th>
							<th><button tooltip="{{playlist.help['cancon']}}" class="box cancon filled pad-top"></button>
							<th><button tooltip="{{c.playsheet.help['femcon']}}" class="box femcon filled pad-top"></button></th>
							<th><button tooltip="{{c.playsheet.help['instrumental']}}" class="box instrumental filled pad-top"></button></th>
							<th><button tooltip="{{c.playsheet.help['partial']}}" class="box partial filled pad-top"></button></th>
							<th><button tooltip="{{c.playsheet.help['hit']}}" class="box hit filled pad-top"></button></th>
							<?php if($socan==true): ?>
							<th><button tooltip="{{c.playsheet.help['background']}}" class="box background filled pad-top"></button></th>
							<th><button tooltip="{{c.playsheet.help['theme']}}" class="box theme filled pad-top"></button></th>
							<?php endif; ?>
							<th><a href='http://www.crtc.gc.ca/eng/archive/2010/2010-819.HTM' target='_blank'><input class="lang" readonly tooltip='{{c.playsheet.help.crtc}}' value="Category"></a></th>
							<th><input class="lang" tooltip='{{c.playsheet.help.lang}}' readonly value="Language"/></th>
							<th><th><th></th>
						</tr>
					</table>
					<button id='add_row' class='right' type='button'>Add Row</button>
					<button id='add_five_rows' class='right' type='button'>Add Five Rows</button>
				</div>
			</FORM>
			<FORM name='promotions'>

			</FORM>
		</div>

		<script type='text/javascript' src="js/jquery-1.11.3.min.js"></script>
		<script type='text/javascript' src="js/jquery-ui-1.11.3.min.js"></script>

		<script type='text/javascript' src='js/playsheet/object.js'></script>

	</body>
</html>