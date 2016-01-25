<?php
	require_once("headers/session_header.php");
	include_once("headers/security_header.php");
	include_once("headers/socan_header.php");
	include_once("headers/menu_header.php");
	include_once("headers/functions.php");

	$playsheet_id = isset($_GET['id']) ? $_GET['id'] : '-1';
	$member_id = $_SESSION['sv_id'];
	$username = $_SESSION['sv_username'];
	$socan = isset($_GET['socan']) ? ($_GET['socan']=='true'? true: false) : socanCheck($db);
?>
<html>

	<head>
		<link rel='stylesheet' href='js/bootstrap/bootstrap.min.css'>
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
		<div id='wrapper' <?php if($socan==true) echo "class='socan'"; ?>>
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
							Hosts: <input id='host' name='host' class='wideinput'>
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
					<table class='playitems <?php if($socan==true) echo "socan"; ?>'>
						<tr class='border'>
							<th><div class='side-padded'>#</th>
							<th><div class='meta' data-toggle="tooltip" data-placement="top" title="Artist of the track">Artist</div></th>
							<th><div class='meta' data-toggle="tooltip" data-placement="top" title="Name of the track">Song</div></th>
							<th><div class='meta' data-toggle="tooltip" data-placement="top" title="Enter the title of the album, EP, or single that the track is released on.
							    If playing an mp3 or streaming from youtube, soundcloud etc, please take a moment to find the title of the album
							    EP, or single that the track is released on. If it is unreleased, enter 'unreleased'.
							    If you are confused about what to enter here, please contact music@citr.ca This will help the artist chart
							    and help provide listeners with information about the release" >Album </div></th>
							<?php if($socan==true): ?>
							<th><div class='meta' data-toggle="tooltip" data-placement="top" title="Enter the name of the composer or author">Composer</div></th>
							<th><div class='socantiming center' data-toggle="tooltip" data-placement="top" title="Hit the CUE button when the song starts, and the END button when the song stops playing. Enter the duration of the song.Time Format is MIN:SECOND">Duration</div></th>
							<?php endif; ?>
							<th><div class='box filled playlist' data-toggle="tooltip" data-placement="top" title="Playlist (New) Content: Was the song released in the last 6 months?"></div></th>
							<th><div class="box canadian filled" data-toggle="tooltip" data-placement="top" title="To be considered Cancon, two of the following must apply: Music written by a Canadian, Artist performing it is Canadian, Performance takes place in Canada, Lyrics Are written by a Canadian"></div></th>
							<th><div class="box femcon filled"  data-toggle="tooltip" data-placement="top" title="To be considered Femcon, two of the following must apply: Music is written by a female, Performers (at least one) are female, Words are written by a female, Recording is made by a female engineer.Playlist (New) Content: Was the song released in the last 6 months?"></div></th>
							<th><div class="box instrumental filled" data-toggle="tooltip" data-placement="top" title="Is the song instrumental? (no vocals)"></div></th>
							<th><div class="box partial filled" data-toggle="tooltip" data-placement="top" title="Play time on the song is less than 1 minute. This will not count towards cancon"></div></th>
							<th><div class="box hit filled" data-toggle="tooltip" data-placement="top" title="Has the song ever been a hit in Canada?  By law, the maximum is 10% Hits played, but we aim for 0% - you really shouldn't play hits!"></div></th>
							<?php if($socan==true): ?>
							<th><div class="box background filled" data-toggle="tooltip" data-placement="top" title="Is the song playing in the background? Talking over the intro to a song does not count as background"></div></th>
							<th><div class="box theme filled" data-toggle="tooltip" data-placement="top" title="Is the song your themesong?"></div></th>
							<?php endif; ?>
							<th><div class="smalltext hand" data-toggle="tooltip" data-placement="top" title="Category 2: Rock, Pop, Dance, Country, Acoustic, Easy Listening.  Category 3: Concert, Folk, World Beat, Jazz, Blues, Religious, Experimental. (Click me for more info!)" onclick="window.open('http://www.crtc.gc.ca/eng/archive/2010/2010-819.HTM','_blank');">Category</div></th>
							<th><div class="smalltext" data-toggle="tooltip" data-placement="top" title="The language of the song">Language</div></th>
							<th><th><th>
						</tr>
						<tr class='playitem' id="playitem_0">
							<td class='side-padded'><input name='id' class='hidden' value='1'></input></td>
							<td class='side-padded'><input name='artist' class='required' onchange='update()' value=''></input></td>
							<td class='side-padded'><input name='song' class='required' onchange='update()' value=''></input></td>
							<td class='side-padded'><input name='album' class='required'onchange='update()' value=''></input></td>
							<?php if($socan==true): ?>
							<td class='side-padded'>
						        <input name='composer' class='required' onchange='update()' value=''></input>
						    </td>
						    <td>
						    	<button type='button' class='smalltext '>CUE</button>
						        <select name='song_length_minute' class='smalltext required'>
						            <option value='0'  >00</option>
						            <option value='1'  >01</option>
						            <option value='2'  >02</option>
						            <option value='3'  >03</option>
						            <option value='4'  >04</option>
						            <option value='5'  >05</option>
						            <option value='6'  >06</option>
						            <option value='7'  >07</option>
						            <option value='8'  >08</option>
						            <option value='9'  >09</option>
						            <option value='10'  >10</option>
						            <option value='11'  >11</option>
						            <option value='12'  >12</option>
						            <option value='13'  >13</option>
						            <option value='14'  >14</option>
						            <option value='15'  >15</option>
						            <option value='16'  >16</option>
						            <option value='17'  >17</option>
						            <option value='18'  >18</option>
						            <option value='19'  >19</option>
						            <option value='20'  >20</option>
						            <option value='21'  >21</option>
						            <option value='22'  >22</option>
						            <option value='23'  >23</option>
						            <option value='24'  >24</option>
						            <option value='25'  >25</option>
						            <option value='26'  >26</option>
						            <option value='27'  >27</option>
						            <option value='28'  >28</option>
						            <option value='29'  >29</option>
						            <option value='30'  >30</option>
						            <option value='31'  >31</option>
						            <option value='32'  >32</option>
						            <option value='33'  >33</option>
						            <option value='34'  >34</option>
						            <option value='35'  >35</option>
						            <option value='36'  >36</option>
						            <option value='37'  >37</option>
						            <option value='38'  >38</option>
						            <option value='39'  >39</option>
						            <option value='40'  >40</option>
						            <option value='41'  >41</option>
						            <option value='42'  >42</option>
						            <option value='43'  >43</option>
						            <option value='44'  >44</option>
						            <option value='45'  >45</option>
						            <option value='46'  >46</option>
						            <option value='47'  >47</option>
						            <option value='48'  >48</option>
						            <option value='49'  >49</option>
						            <option value='50'  >50</option>
						            <option value='51'  >51</option>
						            <option value='52'  >52</option>
						            <option value='53'  >53</option>
						            <option value='54'  >54</option>
						            <option value='55'  >55</option>
						            <option value='56'  >56</option>
						            <option value='57'  >57</option>
						            <option value='58'  >58</option>
						            <option value='59'  >59</option>
						        </select>:

						        <select name='song_length_second' class='smalltext required'>
						            <option value='0'  >00</option>
						            <option value='1'  >01</option>
						            <option value='2'  >02</option>
						            <option value='3'  >03</option>
						            <option value='4'  >04</option>
						            <option value='5'  >05</option>
						            <option value='6'  >06</option>
						            <option value='7'  >07</option>
						            <option value='8'  >08</option>
						            <option value='9'  >09</option>
						            <option value='10'  >10</option>
						            <option value='11'  >11</option>
						            <option value='12'  >12</option>
						            <option value='13'  >13</option>
						            <option value='14'  >14</option>
						            <option value='15'  >15</option>
						            <option value='16'  >16</option>
						            <option value='17'  >17</option>
						            <option value='18'  >18</option>
						            <option value='19'  >19</option>
						            <option value='20'  >20</option>
						            <option value='21'  >21</option>
						            <option value='22'  >22</option>
						            <option value='23'  >23</option>
						            <option value='24'  >24</option>
						            <option value='25'  >25</option>
						            <option value='26'  >26</option>
						            <option value='27'  >27</option>
						            <option value='28'  >28</option>
						            <option value='29'  >29</option>
						            <option value='30'  >30</option>
						            <option value='31'  >31</option>
						            <option value='32'  >32</option>
						            <option value='33'  >33</option>
						            <option value='34'  >34</option>
						            <option value='35'  >35</option>
						            <option value='36'  >36</option>
						            <option value='37'  >37</option>
						            <option value='38'  >38</option>
						            <option value='39'  >39</option>
						            <option value='40'  >40</option>
						            <option value='41'  >41</option>
						            <option value='42'  >42</option>
						            <option value='43'  >43</option>
						            <option value='44'  >44</option>
						            <option value='45'  >45</option>
						            <option value='46'  >46</option>
						            <option value='47'  >47</option>
						            <option value='48'  >48</option>
						            <option value='49'  >49</option>
						            <option value='50'  >50</option>
						            <option value='51'  >51</option>
						            <option value='52'  >52</option>
						            <option value='53'  >53</option>
						            <option value='54'  >54</option>
						            <option value='55'  >55</option>
						            <option value='56'  >56</option>
						            <option value='57'  >57</option>
						            <option value='58'  >58</option>
						            <option value='59'  >59</option>
						        </select>
						        <button type='button' class='smalltext '>END</button>
						    </td>
						    <?php endif; ?>
							<td><button name='is_playlist' class="box playlist pad-top"></button></td>
							<td><button name='is_canadian' class="box canadian pad-top"></button></td>
							<td><button name='is_fem' class="box femcon pad-top"></button></td>
							<td><button name='is_inst' class="box instrumental pad-top"></button></td>
							<td><button name='is_part' class="box partial pad-top"></button></td>
							<td><button name='is_hit' class="box hit pad-top"></button></td>
							<?php if($socan==true): ?>
							<td><button type='button'  name='is_background' class="box background pad-top"></button ></td>
							<td><button type='button'  name='is_theme' class="box theme pad-top <?php echo $playitem['is_theme']==1? 'filled' : ''; ?>"></button></td>
							<?php endif; ?>
							<td>
								<select name='crtc_category'>
									<option value='20' >20</option>
									<option value='30' >30</option>
								</select>
							</td>
							<td><input name='lang' class="lang" value=''></input></td>
							<td><button type='button' class='add' onclick="playsheet.addPlayitem(1);"><img src='images/collapsed.png'></button></td>
							<td><button type='button' class='remove'><img src='images/expanded.png'></button></td>
							<td><div class='hand side-padded'>&#x21D5;</div></td>
						</tr>
					</table>
					<button id='add_row' class='right' type='button'>Add Row</button>
					<button id='add_five_rows' class='right' type='button'>Add Five Rows</button>
				</div>
			</FORM>
			<FORM name='promotions'>
				<table class='table table-responsive border'>
					<th title='number'>#</th><th>Time</th><th>Type</th><th>Name</th><th>Played</th>

				</table>
			</FORM>
		</div>
	</div>
	<div id='percentages' class='border bottom col1'>
		<table class='table'>
			<td>Cancon Category 2:</td>
			<td><span id='cancon_2_percent' class='red'>20%</span><span>/ 35%</span></td>
			<td>Cancon Category 3:</td>
			<td><span id='cancon_3_percent'>20%</span><span>/ 35%</span></td>
			<td>Femcon:</td>
			<td><span id='femcon_percent'>20%</span><span>/ 35%</span></td>
		</table>

	</div>

		<script type='text/javascript' src="js/jquery-1.11.3.min.js"></script>
		<script type='text/javascript' src='js/bootstrap/bootstrap.js'></script>
		<script type='text/javascript' src='js/playsheet/object.js'></script>
		<script type='text/javascript' src='js/playsheet/app.js'></script>
		<script>
			$(document).ready(function(){
    			$('[data-toggle="tooltip"]').tooltip();
			});
		</script>
	</body>
</html>
