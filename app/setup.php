<html>
	<head>
		<meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">
		<link rel=stylesheet href=css/bootstrap.min.css type=text/css>
	</head>
	<body style='padding-left:20px'>
		<?php
		if(!file_exists(dirname($_SERVER['DOCUMENT_ROOT']).'/config.php') && !isset($_POST['next_form'])): ?>
			<h1>DJLand Initial Setup</h1>
			<span>
				It appears that you have not set up DJLand before as your config.php file is missing.
				Please Fill out the following, or copy the config.php.sample and fill in the fields.

				<form action='index.php' method='post'>
					Will this environment be used for testing?
					<select name='testing_environment'>
						<option value='true' >Yes</option>
						<option value='false'>No</option>
					</select>
					<h2>Station Info</h2>
					<h4>General Info</h4>
					<div>
						<input name='station_info[station_name]' placeholder='Station Name' />
						<input name='station_info[call_letters]' placeholder='Call Letters' />
						<input name='station_info[frequency]' placeholder='Frequency' />
						<input name='station_info[website]' placeholder='Website' />
						<input name='station_info[station_id]' placeholder='Default Station ID' />
						<input name='station_info[tech_email]' placeholder='Tech Help Email' />
					</div>
					<h4>Station Location</h4>
					<div>
						<input name='station_info[city]' placeholder='City'/>
						<input name='station_info[province]' placeholder='Province/Territory'/>
						<input name='station_info[country]' placeholder='Country' />
					</div>
					<h4>Time Zone</h4>
					<div>
						<select name='station_info[timezone]'>
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
						<input name='db[address]' placeholder='Address' />
						<input name='db[username]' placeholder='Username' />
						<input name='db[password]' placeholder='Password' />
						<input name='db[database]' placeholder='Database' />
					</div>
					<h4>SAM Database (optional)</h4>
					<div>
						<input name='sam_db[address]' placeholder='Address' />
						<input name='sam_db[username]' placeholder='Username' />
						<input name='sam_db[password]' placeholder='Password' />
						<input name='sam_db[database]' placeholder='Database' />
					</div>
					<h2>Enabled Features</h2>
					<dl class='dl-horizontal'>
						<dt>Membership<dt>
						<dd>
							<select name='enabled[membership]' />
								<option value='true' selected>Yes</option>
								<option value='false'>No</option>
							</select>
						</dd>
						<dt>Library</dt>
						<dd>
							<select name='enabled[library]'/>
								<option value='true' selected>Yes</option>
								<option value='false'>No</option>
							</select>
						</dd>
						<dt>Shows</dt>
						<dd>
							<select name='enabled[shows]'/>
								<option value='true' selected>Yes</option>
								<option value='false'>No</option>
							</select>
						</dd>
						<dt>Ad Scheduler</dt>
						<dd>
							<select name='enabled[ad_scheduler]'/>
								<option value='true' selected>Yes</option>
								<option value='false'>No</option>
							</select>
						</dd>
						<dt>Charts</dt>
						<dd>
							<select name='enabled[charts]'/>
								<option value='true' selected>Yes</option>
								<option value='false'>No</option>
							</select>
						</dd>
						<dt>Report</dt>
						<dd>
							<select name='enabled[report]'/>
								<option value='true' selected>Yes</option>
								<option value='false'>No</option>
							</select>
						</dd>
						<dt>Playsheet</dt>
						<dd>
							<select name='enabled[playsheet]'/>
								<option value='true' selected>Yes</option>
								<option value='false'>No</option>
							</select>
						</dd>
						<dt>Podcasting</dt>
						<dd>
							<select name='enabled[podcasting]' />
								<option value='true' selected>Yes</option>
								<option value='false'>No</option>
							</select>
						</dd>
						<dt>Sam Integration</dt>
						<dd>
							<select name='enabled[sam_integration]'/>
								<option value='true' selected>Yes</option>
								<option value='false'>No</option>
							</select>
						</dd>
					</dl>
					<h2>Podcasting Variables (optional)</h2>
					<h4>Audio Paths</h4>
					<div>
						<input name='path[audio_base]' class='form-control' placeholder='/path/to/audio/base' />
						<input name='url[audio_base]' class='form-control' placeholder='http://path/to/hosted/audio/base' />
					</div>
					<h4>Show XML Paths</h4>
					<div>
						<input name='path[xml_base]' class='form-control' placeholder='/path/to/xml/base' />
						<input name='url[xml_base]' class='form-control' placeholder='http://path/to/hosted/xml/base' />
					</div>
					<h4>Podcast Audio Generator</h4>
					<div>
						<input name='url[archiver_tool]' class='form-control' placeholder='Podcast Audio Tool URL' />
						<input name='url[archiver_request]' class='form-control' placeholder='Podcast Audio Generation Request URL' />
					</div>
					<div>
						<br/>
						<input style='display:none' name='next_form' value='write_config'/>
						<button name='submit'>Create Config File</button>
					</div>
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
		<?php
		elseif(!file_exists(dirname($_SERVER['DOCUMENT_ROOT']).'/config.php') && isset($_POST['next_form']) && $_POST['next_form'] == 'write_config'):
			require_once(dirname($_SERVER['DOCUMENT_ROOT'])."/setup/write_config.php");
		else:
			require_once(dirname($_SERVER['DOCUMENT_ROOT'])."/setup/setup_database.php");
		endif;
		?>
	</body>
