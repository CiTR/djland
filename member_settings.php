<!--- Member Settings -->

<?php session_start();
	require_once("headers/security_header.php");
	require_once("headers/function_header.php");
	require_once("headers/menu_header.php");

?>
<html>
	<head>
		<meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">
		<meta charset="UTF-8">
		<link rel=stylesheet href='css/style.css' type='text/css'>
		<title>DJLAND | Member Settings</title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script src="js/jquery.form.js"></script>
		<script src="js/test.js"></script>
		<script src="js/membership-functions.js"></script>
		<script src="js/member_settings.js"></script>
	</head>
	<body>
		<?php 
		print_menu2(); 
		?>

		<div id='membership' >
			<div id='member_id' style='display:none;'><?php echo $_SESSION['sv_id']; ?> </div>
			<h1> CiTR Member Settings </h1>
			<hr>
			<div class = 'container'>
				<div id='row1' class='containerrow'>
					<div class='col5'>Username: </div>
					<div class='col5' id='username' name='username'><?php echo $_SESSION['sv_username']; ?></div>
				</div>
				
				<div id='row2' class='containerrow'>
					<div class='col5'>First Name*: </div>
					<div class='col5' id='firstname'></div>
					<div class='col5'>Last Name*: </div>
					<div class='col5' id='lastname'></div>					
				</div>
				<div id='row3 'class='containerrow'>
					<div class='col5'>Address*: </div>
					<div class='col5'><input id='address' class='required' placeholder='Address' maxlength='50'></input></div>
					<div class='col5'>City*:</div>
					<div class='col5'><input id='city' class='required' placeholder='City' maxlength='45'></input></div>
				</div>
				<div id='row4 'class='containerrow'>
					<div class='col5'>Province*: </div>
					<div class='col5'><select id='province'>
							<option value='BC'>BC</option>
							<option value='AB'>AB</option>
							<option value='SASK'>SASK</option>
							<option value='MAN'>MAN</option>
							<option value='ON'>ON</option>
							<option value='QC'>QC</option>
							<option value='NB'>NB</option>
							<option value='NS'>NS</option>
							<option value='NFL'>NFL</option>
							<option value='NU'>NU</option>
							<option value='NWT'>NWT</option>
							<option value='YUK'>YUK</option>
						</select></div>
					<div class='col5'>Postal Code*:</div>
					<div class='col5'><input id='postalcode' class='required' placeholder='Postal Code' maxlength='6'></input></div>
				</div>

				<div id='row5' class='containerrow'>
					<div class='col5'>Canadian Citizen*:</div>
					<div class='col5'>
						Yes<input id='can1' class='can_status' type='radio' checked='checked' />
						No<input id='can2' class='can_status' type='radio' />
						
					</div>
					<div class='col5'>Member Type*:</div>
					
					<div class='col5'><select id='is_new'>
							<option value='Returning'>Returning</option>
							<option value='New'>New</option>
						</select>
					
						<select id='member_type'>
							<option value='Student'>Student</option>
							<option value='Community'>Community</option>
							<option value='Staff'>Staff</option>
						</select>

					</div>
				</div>
				<div class='containerrow'>
					<div class='col5'>Alumni:</div>
					<div class='col5'> Yes<input id='alumni1' class='alumni_select' type='radio'  />
						No<input id='alumni2' class='alumni_select' type='radio' checked='checked'/> </div>
					<div class='col5'>Member Since</div>
					<div class='col5' id='since'>1927</div>
				</div>
				<div id='row6' class='containerrow'>
					<div class='col5'>Faculty*: </div>
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
							<option value='Other'>Other</option>
						</select>
						<input id='faculty2' style='display:none' placeholder='Enter your Faculty'/>
					</div>
					
					<div id='student_no_container'>
						<div class='col5'>Student Number*:</div>
						<div class='col5' id='student_no_check'>
							<input id='student_no' name='student_no' placeholder='Enter a student number' maxlength='8' onKeyPress="return numbersonly(this, event)"></input>
						</div>
					</div>	

				</div>

				<div id='row7' class='containerrow'>
						<div class='col5'>Year*:</div>			
						<div class='col5'><select id='schoolyear'>
							<option value='1'>1</option>
							<option value='2'>2</option>
							<option value='3'>3</option>
							<option value='4'>4</option>
							<option value='5+'>5+</option>
						</select></div>
					<div class='span3col5'>I would like to incorporate CiTR into my courses(projects,practicums,etc.):
					<input id='integrate'  name='integrate' type='checkbox' /></div>

					
					
				</div>
				<div class='containerrow'>
					<div class='col5'>Do you have a show?*:</div>
					<div class='col5'>Yes<input id='show1' class='show_select' type='radio'  />
						No<input id='show2' class='show_select' type='radio' checked='checked'/> </div>
					<div class='col5'>Name of show:</div>
					<div class='col5'><input id='show_name' type='text' placeholder='Show name(s)'/></div>
				</div>

				<hr>

				<div id='row8' class='containerrow'>
					<div class='col7'>Email Address*: </div>
					<div class='col6'><input id='email' class='required'  name='email' placeholder='Email Address' maxlength='40'></input></div>
					<div class='col6'>Primary Number*:</div>
					<div class='col6'><input id='phone1' class='required' name='phone1' placeholder='Phone Number' maxlength='10' onKeyPress="return numbersonly(this, event)"></input></div>
					<div class='col6'>Secondary Number:</div>
					<div class='col6'><input id='phone2' name='phone2' placeholder='Secondary Number' maxlength='10' onKeyPress="return numbersonly(this, event)"></input></div>
				</div>

				<hr>
				<div class='containerrow'>
					<div class='col6'>I am interested in:</div>
					<div class='span3col4'>
						<div class='col3'><label for='music'>Music Department:</label><input type=checkbox id='music'></div>
						<div class='col3'><label for='discorder'>Illustrate for Discorder:</label><input type=checkbox id='discorder'></div>
						<div class='col3'><label for='discorder_2'>Writing for Discorder:</label><input type=checkbox id='discorder_2'></div>
						<div class='col3'><label for='dj'>DJ 101.9:</label><input type=checkbox id='dj'></div>
						<div class='col3'><label for='show_hosting'>Show Hosting:</label><input type=checkbox id='show_hosting'></div>
						<div class='col3'><label for='sports'>Sports:</label><input type=checkbox id='sports'></div>
						<div class='col3'><label for='news'>News 101.9:</label><input type=checkbox id='news'></div>
						<div class='col3'><label for='arts'>Arts Report:</label><input type=checkbox id='arts'></div>
						<div class='col3'><label for='live_broadcast'>Live Broadcasting:</label><input type=checkbox id='live_broadcast'></div>
						<div class='col3'><label for='tech'>Web and Tech:</label><input type=checkbox id='tech'></div>
						<div class='col3'><label for='programming'>Programming Committee:</label><input type=checkbox id='programming'></div>
						<div class='col3'><label for='ads_psa'>Ads and PSAs:</label><input type=checkbox id='ads_psa'></div>
						<div class='col3'><label for='promos'>Promotions and Outreach:</label><input type=checkbox id='promos'></div>
						<div class='col3'><label for='photography'>Photography:</label><input type=checkbox id='photography'></div>
						<div class='col3'><label for='digital_library'>Digital Library:</label><input type=checkbox id='digital_library'></div>
						<div class='col3'><label for='tabling'>Tabling Events:</label><input type=checkbox id='tabling'></div>
						<div class='col3'><label for='other'>Other:</label><input type=text id='other'></div>
					</div>
				</div>
				<hr>
				<div class='containerrow'>
					<div class='col6'>About me:</div>
					<textarea id='about' class='largeinput' placeholder='Tell us about yourself!'rows='3'></textarea>
				</div>
				<br/>
				<div class='containerrow'>
					<div class='col6'>My Skills:</div>
					<textarea id='skills' placeholder='Tell us about your sweet skills!' class='largeinput' rows='3'></textarea>
				</div>
				<div class='containerrow'>
					<div class='col6'>How did you hear about us?:</div>
					<textarea id='exposure' placeholder='Was it a friend?' class='largeinput' rows='3'></textarea>
				</div>
				
				<div class='contanerrow'>
					<center>
						<button id='submit_user' class='red' disabled='true'>Form Not Complete</button>
						<br>* indicates a required field
					</center>
				</div>
				<div class='contanerrow'>
					<br/>
				</div>
			</div>	
			
	</body>
</html>
