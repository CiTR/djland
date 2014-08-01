<?php
session_start();
require("headers/config.php");
require("headers/security_header.php");
require("headers/function_header.php");

?>
<html>
	<head>
		<meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">
		<meta charset="utf-8">
		<link rel=stylesheet href='css/style.css' type='text/css'>

		<title>DJLAND | Sign Up</title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script src="js/jquery.form.js"></script>
		
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
		<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
	</head>
	<body>
		<div id='membership' >
			<h1> CiTR Member Signup </h1>
			<hr>
			<div class = 'container'>
				<div class='containerrow'>
					<div class='col5'> Username: </div>
					<div class='col5'><input id='username' onfocus="this.value=''" value='Enter a username' maxlength='15'></input></div>
					<div class='col5'>Password:</div>
					<div class='col5'><input id='password1' onfocus="this.value=''" value='Enter a password' maxlength='15'></input></div>
					<div class='col6'><input id='password2' onfocus="this.value=''" value='Enter again' maxlength='15'></input></div>
						
				</div>
				<div class='containerrow'>
					<div class='col5'>First Name: </div>
					<div class='col5'><input id='firstname' onfocus="this.value=''" value='First name' maxlength='30'></input></div>
					<div class='col5'>Last Name: </div>
					<div class='col5'><input id='lastname' onfocus="this.value=''" value='Last name' maxlength='30'></input></div>					
				</div>
				<div class='containerrow'>
					<div class='col5'>Email Address: </div>
					<div class='col5'><input id='email' onfocus="this.value=''" value='Email Address' maxlength='40'></input></a></div>
					<div class='col5'>Phone Number:</div>
					<div class='col5'><input id='phone' onfocus="this.value=''" value='Phone Number' maxlength='10'></input></div>
				</div>
				<div class='containerrow'>
					<div class='col5'>Faculty: </div>
					<div class='col5'>
						<select id='faculty' style='z-position=10;'>
							<option value='Arts'>Arts</option>
							<option value='Applied Science'>Applied Science</option>
							<option value='Architecture'>Architecture</option>
							<option value='Archival Studies'>Archival Studies</option>
							<option value='Audiology'>Audiology</option>
							<option value='Business'>Business</option>
							<option value='Community Planning'>Community Planning</option>
							<option value='Continuing Studies'>Continuing Studies</option>
							<option value='Dentistry'>Dentistry</option>
							<option value='Doctoral Studies'>Doctoral Studies</option>
							<option value='Education'>Education</option>
							<option value='Environmental Health'>Environmental Health</option>
							<option value='Forestry'>Forestry</option>
							<option value='Graduate Studies'>Graduate Studies</option>
							<option value='Journalism'>Journalism</option>
							<option value='Kinesiology'>Kinesiology</option>
							<option value='Land and Food Systems'>Land and Food Systems</option>
							<option value='Law'>Law</option>
							<option value='Medicine'>Medicine</option>
							<option value='Music'>Music</option>
							<option value='Nursing'>Nursing</option>
							<option value='Pharmaceutical'>Pharmaceutical</option>
							<option value='Public Health'>Public Health</option>
							<option value='Science'>Science</option>
							<option value='Social Work'>Social Work</option>
						</select>
					</div>
					<div class='col5'>Member Type:</div>
					<div class='col5'>
						<select id='member_type'>
							<option value='student'>Student</option>
							<option value='alumni'>Alumni</option>
							<option value='community_member'>Community member</option>
						</select>
					</div>
				</div>
				<div class='containerrow'>
					<div class='col5'>I associate as a:</div>
					<div class='col5'>
						<select id='gender'>
							<option value='male'>Male</option>
							<option value='female'>Female</option>
							<option value='f2m'>Female to Male Trans</option>
							<option value='m2f'>Male to Female Trans</option>
						</select>
					</div>
					<div class='col5'>Immigration status:</div>
					<div class='col5'>
						<select id='can_status'>
							<option value='citizen'>Canadian Citizen</option>
							<option value='immigrant'>Landed Immigrant</option>
							<option value='visitor'>Visitor</option>
						</select>
					</div>
				</div>
				<hr>
				<div class='containerrow'>
					<div class='col6'>I am interested in:</div>
					<div class='span5col6'>
						<div class='col3'><label for'music'>Music Department:</label><input type=checkbox id='music'></div>
						<div class='col3'><label for'discorder'>Discorder:</label><input type=checkbox id='discorder'></div>
						<div class='col3'><label for'show_hosting'>Show Hosting:</label><input type=checkbox id='show_hosting'></div>
						<div class='col3'><label for'sports'>Sports:</label><input type=checkbox id='sports'></div>
						<div class='col3'><label for'news'>News 101.9:</label><input type=checkbox id='news'></div>
						<div class='col3'><label for'arts_report'>Arts Report:</label><input type=checkbox id='arts_report'></div>
						<div class='col3'><label for'live_broadcast'>Live Broadcasting:</label><input type=checkbox id='live_broadcast'></div>
						<div class='col3'><label for'tech'>Web and Tech:</label><input type=checkbox id='tech'></div>
						<div class='col3'><label for'programming'>Programming Committee:</label><input type=checkbox id='programming'></div>
						<div class='col3'><label for'ads_psa'>Ads and PSAs:</label><input type=checkbox id='ads_psa'></div>
						<div class='col3'><label for'promos'>Promotions and Outreach:</label><input type=checkbox id='promos'></div>
					</div>
				</div>
				<hr>
				<div class='containerrow'>
					<div class='col6'>About me:</div>
					<textarea id='about' class='largeinput' rows="5"></textarea>
				</div>
				<br>
				<div class='containerrow'>
					<div class='col6'>My Skills:</div>
					<textarea id='skills' class='largeinput' rows="5"></textarea>
				</div>
				<hr>
				<div class='contanerrow'>
					<center>
						<button id='submit_user'>Submit</button>
					</center>
				</div>
			</div>	
			
		</div>
	</body>
</html>