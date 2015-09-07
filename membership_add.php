<?php require_once("headers/db_header.php"); ?>

<html>
	<head>
		<meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">
		<meta charset="utf-8">
		<link rel=stylesheet href='css/style.css' type='text/css'>

		<title>DJLAND | Sign Up</title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script src="js/jquery.form.js"></script>
		<script type='text/javascript' src='js/membership/add.js'></script>
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
		<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
	</head>
	<body class='wallpaper'>
		<div id='membership' class='wrapper' >
			<h1> CiTR Member Signup </h1>
			<hr>
			
			<div class = 'container'>
				<div id='row1' class='containerrow'>
					<div class='col5'>Username*: </div>
					<div id="username_check" class='col5'><input id='username' class='required' name='username' placeholder='Enter a username' maxlength='15' tabindex=1></input></div>
					<div class='col5'>Password*: </div>
					<div class='col5'><input id='password1' class='required' type="password" placeholder='Enter a password' onkeyup="passwordCheck();" tabindex=2></input></div>
					<div id="password_check" class='col6'><input id='password2' type="password" class='required' placeholder='Enter again' onkeyup="passwordCheck();" tabindex=3></input></div>
				</div>
				
				<div id='row2' class='containerrow'>
					<div class='col5'>First Name*: </div>
					<div class='col5'><input id='firstname' class='required' placeholder='First name' maxlength='30'></input></div>
					<div class='col5'>Last Name*: </div>
					<div class='col5'><input id='lastname' class='required' placeholder='Last name' maxlength='30'></input></div>					
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
							<?php 
							foreach($djland_provinces as $key=>$province){ 
								echo "<option value='{$province}'>{$province}</option>"; 
							}
							?>
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
							<?php 
							foreach($djland_member_types as $key=>$value){
								if($key != 'Lifetime') echo "<option value='{$value}'>{$key}</option>";
							}
							?>
						</select>

					</div>
				</div>
				<div class='containerrow'>
					<div class='col5'>Alumni:</div>
					<div class='col5'> Yes<input id='alumni1' class='alumni_select' type='radio'  />
						No<input id='alumni2' class='alumni_select' type='radio' checked='checked'/> </div>
					<div class='col5'>Member Since</div>
					<div class='col5'>
						<select id='since'>
							<?php 
							
							$year = idate('Y');
							$year_end = 1925;
							$today = date('m/d/Y',strtotime("today"));
							$cutoff = date('04/31/'.$year);

							//Check to see if we are in a new school year or not.
							if(strtotime($today) < strtotime($cutoff)){
								$year--;
							}

							for ($i=$year; $i > $year_end ; $i--) { 
								$next_year = $i+1;
								echo "<option value='".$i."/".$next_year."''>".$i."/".$next_year."</option>";
							} ?>
						</select>
				</div>
				<div id='row6' class='containerrow'>
					<div class='col5'>Faculty*: </div>
					<div class='col5'>
						<select id='faculty' style='z-position=10;'>
							<?php 
							foreach($djland_faculties as $value){
								echo "<option value='{$value}'>{$value}</option>";
							}
							?>
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
							<?php foreach($djland_program_years as $key=>$value){ echo "<option value='{$value}'>{$key}</option>"; } ?>
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
						<?php foreach($djland_interests as $key=>$interest): ?> 
						<div class='col3 text-right'>
							<label for='<?php echo $interest ?>'><?php echo $key; ?></label>
							<?php if($interest == 'other'): ?>
							<input id='<?php echo $interest ?>' placeholder='Enter interest' maxlength='40'/>
							<?php else: ?>
							<input type='checkbox' id='<?php echo $interest; ?>'>
							<?php endif; ?>
						</div>
						<?php endforeach; ?>
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
			
		</div>
	</body>
</html>