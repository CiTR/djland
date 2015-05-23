<?php
	session_start();
	require_once("headers/security_header.php");
	require_once("headers/function_header.php");
	require_once("headers/menu_header.php");
	
if( permission_level() >= $djland_permission_levels['volunteer']){ ?>
<html>
	<head>
		<meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">
		<meta charset="utf-8">
		<link rel=stylesheet href='css/style.css' type='text/css'>

		<title>DJLAND | Membership</title>

		<script src='js/jquery-1.11.3.min.js'></script>
		<script src="js/jquery.form.js"></script>
        <script type='text/javascript' src='js/test.js'></script>
        <script type='text/javascript' src='js/constants.js'/></script>
        <script type='text/javascript' src='js/member.js'></script>
        <script type='text/javascript' src='js/membership_functions.js'></script>
		<script type='text/javascript' src='js/membership.js'></script>
		<script type="text/javascript" src="js/admin.js"></script>
		
		<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
		<script>
			$(function() {
			$( ".datepicker" ).datepicker({ dateFormat: "yy-mm-dd" });
			});
		</script>
	</head>
	<body>
		<?php
        print_menu();
		membership_menu();
		if(permission_level() >= $djland_permission_levels['staff']): ?>
		<div id='member_id'></div>
		<!-- Begin Tab 1 "member search" -->		
		<div id='membership' name='search' class='clearfix'>
			<ul id='membership_header' name='search' class='clean-list inline-list	'>
					<li id='search'>Search By:
						<select id='search_type'>
							<option value='name'>Name</option>
							<option value='interest'>Interest</option>
							<option value='member_type'>Member Type</option>
						</select>
						<input id='search_value' name='name' placeholder='Enter a name'/>
						<select id='search_value' name='interest' class='hidden'>
							<?php
								foreach($djland_interests as $key=>$value){
									echo "<option value='{$value}'>{$key}</input>";
								}
							?>
						</select>
						<select id='search_value' name='member_type' class='hidden'>
							<?php
								foreach($djland_member_types as $key=>$value){
									echo "<option value='{$value}'>{$key}</input>";
								}
							?>
						</select>
					</li>
					<li>
						<select id='paid_status'>
							<option value='both'>Paid or Unpaid</option>
							<option value='paid'>Only Paid for year </option>
							<option value='unpaid'>Only Unpaid for year</option>
						</select>
					</li>
					<li>
						<select id='membership_years'>
							<option value='all'>All years</option>
						</select>

					</li>
					<li>
						Order By
						<select id='sort'>
							<option value='lastname'>Lastname</option>
							<option value='firstname'>Firstname</option>
							<option value='join'>Join Date</option>
							<option value='type'>Member Type</option>
						</select>
					</li>
					<li>
						<button id='member_submit' name='search'>Search</button>
					</li>
			</ul>
			<ul id='membership_result' class='col1 clean-list'>
				Loading...
				
			</div>	
   		</div>
		<!-- Begin Tab 2 "member view" -->   		
   		<div id='membership' name='view'class='clearfix'>
   			<div id='member_id' style='display:none' value = '<?php echo $_SESSION['sv_id']; ?>'><?php echo $_SESSION['sv_id']; ?></div>
			<div class = 'container'>
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
							<option value='Returning'>Returning</option>
							<option value='New'>New</option>
						</select>
					
						<select id='member_type'>
							<?php 
							foreach($djland_member_types as $key=>$value){
								echo "<option value='{$value}'>{$key}</option>";
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
					<div class='col5'>Do you have a show?*:</div>
					<div class='col5'>Yes<input id='show1' class='show_select' type='radio'  />
						No<input id='show2' class='show_select' type='radio' checked='checked'/> </div>
					<div class='col5'>Name of show:</div>
					<div class='col5'><input id='show_name' type='text' placeholder='Show name(s)'/></div>
				</div>
				<hr>
				<div id='row8' class='containerrow'>
					<div class='col7'>Email Address*: </div>
					<div class='col6'><input id='email' class='required'  name='email' placeholder='Email Address' maxlength='40'/></div>
					<div class='col6'>Primary Number*:</div>
					<div class='col6'><input id='primary_phone' class='required' name='phone1' placeholder='Phone Number' maxlength='10' onKeyPress="return numbersonly(this, event)"/></div>
					<div class='col6'>Secondary Number:</div>
					<div class='col6'><input id='secondary_phone' name='phone2' placeholder='Secondary Number' maxlength='10' onKeyPress="return numbersonly(this, event)"/></div>
				</div>

				<hr>
				<div class='containerrow'>
					<div class='col6'>I am interested in:</div>
					<div id = 'membership_year'style='display:none'></div> 
					<div class='span3col4'>
						<?php 
						foreach($djland_interests as $key=>$interest){ 
							echo "<div class='col3'>{$key}";
							if($interest == 'other'){echo " <input id='{$interest}2' placeholder='Enter interest'/>";}
							echo "<input type='checkbox' id='{$interest}'/></div>";
							}?>
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
				<div class='containerrow'>
					<div class='col6'>Staff Comments:</div>
					<textarea id='comments' placeholder='Enter a staff only comment' class='largeinput' rows='3'></textarea>
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
   		<!-- Begin Tab 3 "report view" -->  
		<div id='membership' name='report'class='clearfix'>
			<select id="year_select">
				
			</select>
			<button class="member_submit" name="report">Get Yearly Report</button>
   			<div id="membership_result">
   				<div class='col2'>
	   				<h4>Members Registered this year</h4>
	   				<ul class='clean-list inline-list'>
	   					<li>Total:<div id='Total'></div></li>
	   					<li>Student:<div id='Student'></div></li>
	   					<li>Community:<div id='Community'></div></li>
	   				</ul>
	   			</div>
	   			<div class='col2'>
	   				<h4>Interest Levels from Paid Members</h4>
	   				<ul class='clean-list'>
	   				<?php

					foreach($djland_interests as $key=>$value){
						if($key!='Other') echo "<li>{$key}:<div id='$value'></div></li>";
					}
	   				?>
	   				</ul>
   				</div>
   			</div>
   		</div>
   		<!-- Begin Tab 4 "member admin" -->  
   		<div id='membership' name='admin' class='clearfix'>
   			<h4>Admin Panel</h4>

   			<ul class="inner clean-list text-center">
	            <li class="col4"><button id="year_rollover">Start new membership year</button></li>
	            <li class="col4" id="current_year">Loading Current Membership Year...</li>
	            <li class="col4" id="current_cutoff">Loading Current Cutoff...</li>
	            <li class="col4">Current membership year members must have renewed, and paid for to this current year</li>
    		</ul>

   		</div>
        <!-- Begin Tab 5 "email view" -->  
   		<?php endif; ?>
   		<div id='membership' name='email' class='clearfix'>
   			<h4>Email List</h4>
   			<ul id='membership_header' name='email' class='clean-list inline-list'>
   				<li>Interested in:
   					<select>
   						<?php
   							foreach($djland_interests as $key=>$value){
   								echo "<option value='{$value}'>{$key}</option>";
   							}
   						?>
   					</select>
   				</li>
   			</ul>
   		</div>
   		<div id="data" style="display:none" ></div>
		<ul id='membership' >
		</ul>
	</body>
</html>
<?php }else{
	header("Location: main.php");
}?>