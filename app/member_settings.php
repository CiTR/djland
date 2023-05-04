<!--- Member Settings -->
<?php
	require_once("headers/security_header.php");
	require_once("headers/menu_header.php");

?>
<html>
	<head>
		<meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">
		<meta charset="UTF-8">
		<link rel=stylesheet href='css/style.css' type='text/css'>
		<title>DJLAND | Member Settings</title>
		<script src='js/jquery-1.11.3.min.js'></script>
		<script src="js/jquery.form.js"></script>
        <script type='text/javascript' src='js/test.js'></script>
 		<script type='text/javascript' src='js/constants.js'/></script>
        <script type='text/javascript' src='js/membership/member.js'></script>
        <script type='text/javascript' src='js/membership/functions.js?20200511'></script>
		<script type='text/javascript' src="js/membership/settings.js?20210521"></script>
	</head>
	<body class='wallpaper'>
		<?php
		print_menu();
		?>
<div class="membership clearfix"><a href="https://airtable.com/shroc8ZbfhgK38tBb" target="_blank">Be sure to fill out the COVID Proof of Vaccination form</a></div>
		<div class='membership grey clearfix'>
			<h1 id="title"> CiTR &amp; Discorder Member Settings </h1>
            <h4 id="subtitle"></h4>
			<div class="col1"><button id="renew">Renew Membership Form</button></div>
   			<div id='member_loading' name='view' class='col1'>Loading...</div>
			<div id='member_result' class = 'container hidden'>
				<div id='row1' class='containerrow'>
					<div class='col5'>Username: </div>
					<div class='col5' id='username' name='username'></div>
				</div>

				<div id='row2' class='containerrow'>
					<div class='col5'>First Name: </div>
					<div class='col5' id='firstname'></div>
					<div class='col5'>Last Name: </div>
					<div class='col5' id='lastname'></div>
				</div>
				<div id='row2.5' class='containerrow'>
					<div class='col5'>Preferred Name: </div>
					<div class='col5'><input id='preferred_name' placeholder='Preferred Name' maxlength='50'/></div>
					<div class="col5">Pronouns: </div>
					<div class='col5'><input id='pronouns' placeholder='Pronouns' maxlength='255'/></div>
				</div>
				<div id='row3 'class='containerrow'>
					<div class='col5'>Address*: </div>

					<div class='col5'><input id='address' class='required' placeholder='Address' maxlength='50'/></div>
					<div class='col5'>City*:</div>
					<div class='col5'><input id='city' class='required' placeholder='City' maxlength='45'/></div>
				</div>
				<div id='row4 'class='containerrow'>
					<div class='col5'>Province*: </div>
					<div class='col5'>
						<select id='province'>
							<?php
							foreach($djland_provinces as $key=>$province){
								echo "<option value='{$province}'>{$province}</option>";
							}
							?>
						</select></div>
					<div class='col5'>Postal Code*:</div>
					<div class='col5'><input id='postalcode' class='required' placeholder='Postal Code' maxlength='6'/></div>
				</div>
				<div class='containerrow'>
					<div class='col5'>Canadian Citizen*:</div>
					<div class='col5'>
						Yes<input id='can1' class='can_status' type='radio' checked='checked' />
						No<input id='can2' class='can_status' type='radio' />

					</div>
					<div class='col5'>Member Type*:</div>

					<div class='col4'><select id='is_new'>
							<option value='0'>Returning</option>
							<option value='1'>New</option>
						</select>

						<select id='member_type'>
							<?php
							foreach($djland_member_types as $key=>$value){
								if($key != 'Lifetime' && $key != 'Staff') echo "<option value='{$value}'>{$key}</option>";
							}
							?>
						</select>
					</div>
				</div>
				<div class='containerrow student'>
					<div class='col5'>Alumni:</div>
					<div class='col5'> Yes<input id='alumni1' class='alumni_select' type='radio'  />
						No<input id='alumni2' class='alumni_select' type='radio' checked='checked'/> </div>
					<div class='col5'>Member Since: </div>
					<div class='col5' id='since'>1927</div>
				</div>
				<div class='containerrow student'>
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
							<input id='student_no' name='student_no' placeholder='Enter a student number' maxlength='8' onKeyPress="return numbersonly(this, event)"/>
						</div>
					</div>

				</div>
				<div class='containerrow student'>
						<div class='col5'>Year*:</div>
						<div class='col5'>
							<select id='schoolyear'>
								<?php foreach($djland_program_years as $key=>$value){ echo "<option value='{$value}'>{$key}</option>"; } ?>
							</select>
						</div>
					<div class='span3col5'>I would like to incorporate CiTR into my courses(projects,practicums,etc.):
					<input id='integrate'  name='integrate' type='checkbox' /></div>
				</div>
				<div class='containerrow'>
					<div class='col5'>Do you have a radio show at CiTR?*:</div>
					<div class='col5'><input type='checkbox' id='has_show'></div>
					<div class='col5'>Name of show:</div>
					<div class='col5'><input id='show_name' type='text' placeholder='Show name(s)'/></div>
				</div>
				<div class='containerrow'>
					<div class='col5'>Have you contributed to Discorder?</div>
					<div class='col5'><input type='checkbox' id='discorder_contributor'></div>
				</div>
				<hr>
				<div id='row8' class='containerrow'>
					<div class='col7'>Email Address*: </div>
					<div class='col6'><input id='email' class='required'  name='email' placeholder='Email Address' maxlength='40'></input><div id='email_check' class='green text-center invisible'></div></div>
					<div class='col6'>Primary Number*:</div>
					<div class='col6'><input id='primary_phone' class='required' name='phone1' placeholder='Phone Number' maxlength='10' onKeyPress="return numbersonly(this, event)"/></div>
					<div class='col6'>Secondary Number:</div>
					<div class='col6'><input id='secondary_phone' name='phone2' placeholder='Secondary Number' maxlength='10' onKeyPress="return numbersonly(this, event)"/></div>

				</div>

				<hr>
				<div class='containerrow'>
					<div class='col6'>Interests:</div><select id ='membership_year' class='hidden'></select>
					<div class='span3col4'>
						<?php foreach($djland_interests as $key=>$interest): ?>
							<div class='col2 text-left' style="margin-left:50%;">
								<?php if($interest == 'other'): ?>
									<label for='<?php echo $interest ?>'><?php echo $key; ?></label>
									<input id='<?php echo $interest ?>' placeholder='Enter interest' maxlength='40'/>
								<?php else: ?>
									<input type='checkbox' id='<?php echo $interest; ?>'>
									<label for='<?php echo $interest ?>'><?php echo $key; ?></label>
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
				<div class="containerrow" style="text-align:center">
					<div class="col1" id="tos">
						Read CiTR & Discorder's Membership Agreement and Volunteer Codes of Conduct <a href="https://www.citr.ca/wp-content/uploads/2021/05/Membership-Agreement-for-DJLand-MAY2021.pdf" target="blank">here</a>. Please read all items fully and carefully before continuing.
					</div>
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
