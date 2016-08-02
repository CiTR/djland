<html>
	<head>
		<meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">
		<link rel=stylesheet href=css/bootstrap.min.css type=text/css>
	</head>
	<body style='padding-left:20px'>
		<h1>DJLand Initial Setup</h1>
		<span>
			It appears that you have not set up DJLand before as your config.php file is missing.
			Please Fill out the following, or copy the config.php.sample and fill in the fields.

			<form id = 'config'>
				<h2>Station Info</h2>
				<h4>General Info</h4>
				<div>
					<input name='station_name' placeholder='Station Name' />
					<input name='call_letters' placeholder='Call Letters' />
					<input name='frequency' placeholder='Frequency' />
					<input name='website' placeholder='Website' />
					<input name='station_id' placeholder='Default Station ID' />
					<input name='tech_email' placeholder='Tech Help Email' />
				</div>
				<h4>Station Location</h4>
				<div>
					<input name='city' placeholder='City'/>
					<input name='province' placeholder='Province/Territory'/>
					<input name='country' placeholder='Country' />
				</div>
				<h4>Time Zone</h4>
				<div>
					<select name='time_zone'>
						<?php
						foreach(timezone_identifiers_list() as $key=>$value){
							echo substr($value,0,7) == "America" ? "<option value='".$value."' ".($value=="America/Vancouver" ? "selected" : "").">".$value."</option>" : "";
						}
						?>
					</select>
				</div>
				<h4>Start Month of Membership Year</h4>
				<select name='membership_cutoff_month'>
					<?php
					for($i=1; $i<=12; $i++){
						$m = date('F',mktime(0,0,0,$i));
						echo "<option value='".$i."' ".($m=='May'?"selected":"").">".$m."</option>";
					}
					?>
				</select>
				<h2>Database Connections</h2>
				<h4>DJLand Database</h4>
				<div>
					<input name='db_address' placeholder='Address' />
					<input name='db_username' placeholder='Username' />
					<input name='db_password' placeholder='Password' />
					<input name='db_username' placeholder='Database' />
				</div>
				<h4>SAM Database (optional)</h4>
				<div>
					<input name='sam_db_address' placeholder='Address' />
					<input name='sam_db_username' placeholder='Username' />
					<input name='sam_db_password' placeholder='Password' />
					<input name='sam_db_username' placeholder='Database' />
				</div>
				<h2>Enabled Features</h2>
				<dl class='dl-horizontal'>
					<dt>Membership<dt><dd><input type='checkbox' name='membership' checked /></dd>
					<dt>Library</dt><dd><input type='checkbox' name='library' checked /></dd>
					<dt>Shows</dt><dd><input type='checkbox' name='shows' checked /></dd>
					<dt>Ad Scheduler</dt><dd><input type='checkbox' name='ad_scheduler' checked /></dd>
					<dt>Charts</dt><dd><input type='checkbox' name='charts' checked /></dd>
					<dt>Report</dt><dd><input type='checkbox' name='report' checked /></dd>
					<dt>Playsheet</dt><dd><input type='checkbox' name='playsheet' checked /></dd>
					<dt>Podcasting</dt><dd><input type='checkbox' name='podcasting' checked /></dd>
					<dt>Sam Integration</dt><dd><input type='checkbox' name='sam_integration' checked /></dd>
				</dl>
				<button name='submit'>Create Config File</button>
			</form>
			Additional Variables can be edited in the config manually that cover:
				<ul>
					<li>Permission Levels (Membership)</li>
					<li>Station Training (Membership)</li>
					<li>Member Interests (Membership)</li>
					<li>Member Types (Membership)</li>
					<li>School Year Options (Membership)</li>
					<li>Falculty Listings (Membership)</li>
					<li>Provinces (Membership)</li>
					<li>Primary Genres (Shows)</li>
					<li>Upload Categories & File Types</li>
				</ul>
		</span>
	</body>
