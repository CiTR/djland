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
		<script type='text/javascript' src='js/membership-add.js'></script>
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
		<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
	</head>
	<body>
		<div id='membership' >
			<h1> CiTR Member Signup </h1>
			<hr>
			<div class = 'container'>
				<div id='row1' class='containerrow'>
					<div class='col5'> Username: </div>
					<div id="username_check" class='col5'><input id='username' class='required' name='username' placeholder='Enter a username' maxlength='15'></input></div>
					<div class='col5'>Password:</div>
					<div class='col5'><input id='password1' class='required' placeholder='Enter a password' maxlength='15'></input></div>
					<div id="password_check" class='col6'><input id='password2' class='required' placeholder='Enter again' onkeyup="passwordCheck();" maxlength='15'></input></div>
				</div>
				
				<div id='row2' class='containerrow'>
					<div class='col5'>First Name: </div>
					<div class='col5'><input id='firstname' class='required' placeholder='First name' maxlength='30'></input></div>
					<div class='col5'>Last Name: </div>
					<div class='col5'><input id='lastname' class='required' placeholder='Last name' maxlength='30'></input></div>					
				</div>
				
				<div id='row3 'class='containerrow'>
					<div class='col5'>Address: </div>
					<div class='col5'><input id='address' class='required' placeholder='Address' maxlength='40'></input></div>
					<div class='col5'>City:</div>
					<div class='col5'><input id='city' class='required' placeholder='City' maxlength='25'></input></div>
				</div>
				<div id='row4 'class='containerrow'>
					<div class='col5'>Province: </div>
					<div class='col5'><select id='province'>
							<option value='BC'>BC</option>
							<option value='AB'>AB</option>
							<option value='SASK'>SASK</option>
							<option value='WIN'>WIN</option>
							<option value='ON'>ON</option>
							<option value='QC'>QC</option>
							<option value='NB'>NB</option>
							<option value='NS'>NS</option>
							<option value='NFL'>NFL</option>
							<option value='NU'>NU</option>
							<option value='NWT'>NWT</option>
							<option value='YUK'>YUK</option>
						</select></div>
					<div class='col5'>Postal Code:</div>
					<div class='col5'><input id='postal_code' class='required' placeholder='Postal Code' maxlength='6'></input></div>
				</div>

				<div id='row5' class='containerrow'>
					<div class='col5'>Immigration status:</div>
					<div class='col5'>
						<select id='can_status'>
							<option value='citizen'>Canadian Citizen</option>
							<option value='immigrant'>Landed Immigrant</option>
							<option value='visitor'>Visitor</option>
						</select>
					</div>
					<div class='col5'>Member Type:</div>
					<div class='col5'>
						<select id='member_type'>
							<option value='student'>Student</option>
							<option value='community_member'>Community member</option>
						</select>
					</div>
				</div>
				<div id='row6' class='containerrow'>
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
					<div class='col5'>Year:</div>
					<div class='col5'>
						<select id='year' style='z-position=10;'>
							<option value='1'>1</option>
							<option value='2'>2</option>
							<option value='3'>3</option>
							<option value='4'>4</option>
							<option value='5'>5+</option>
						</select>
					</div>
				</div>

				<div id='row4' class='containerrow'>
					<div id='student_no_container'>
						<div class='col5'>Student Number:</div>
						<div class='col5'>
							<input id='student_no' name='student_no' placeholder='Enter a student number' maxlength='10' onKeyPress="return numbersonly(this, event)"></input>
						</div>
					</div>
					<div class='col5'>Email Address: </div>
					<div class='col5'><input id='email' class='required'  name='email' placeholder='Email Address' maxlength='40'></input></div>
				</div>

				<div id='row3 'class='containerrow'>
					<div class='col5'>Home Phone Number:</div>
					<div class='col5'><input id='phone' name='phone' placeholder='Phone Number' maxlength='10' onKeyPress="return numbersonly(this, event)"></input></div>
					<div class='col5'>Cell Phone Number:</div>
					<div class='col5'><input id='cellphone' name='cellphone0' placeholder='Cell Phone Number' maxlength='10' onKeyPress="return numbersonly(this, event)"></input></div>
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
				<br/>
				<div class='containerrow'>
					<div class='col6'>My Skills:</div>
					<textarea id='skills' class='largeinput' rows="5"></textarea>
				</div>
				
				<div class='contanerrow'>
					<center>
						<button id='submit_user' class='red' disabled='true'>Form Not Complete</button>
					</center>
				</div>
				<div class='contanerrow'>
					<br/>
				</div>
			</div>	
			
		</div>
	</body>
</html>