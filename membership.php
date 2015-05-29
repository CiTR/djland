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
		<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
        <script type='text/javascript' src='js/test.js'></script>
        <script type='text/javascript' src='js/constants.js'/></script>
        <script type='text/javascript' src='js/member.js'></script>
        <script type='text/javascript' src='js/membership_functions.js'></script>
		<script type='text/javascript' src='js/membership.js'></script>
		<script type="text/javascript" src="js/admin.js"></script>
		
		
		<script>
			$(function() {
			$( ".datepicker" ).datepicker({ dateFormat: "yy-mm-dd" });
			});
		</script>
	</head>
	<body>
		<?php
        print_menu();
        ?>
		<ul id ='tab-nav'>
			<?php if(permission_level() >= $djland_permission_levels['staff']) : ?>
				<li class = 'nodrop active-tab member_action' name='search'>Search Members</li>
				<li class = 'nodrop inactive-tab member_action' name='view' data='1'>View Member</li>
				<li class = 'nodrop inactive-tab member_action' name='admin'>Membership Admin</li>
				<li class = 'nodrop inactive-tab member_action' name='report'>Report</li>
			<?php endif; ?>
			<li class = 'nodrop inactive-tab member_action' id='mail' value='mail'>Send Emails</li>
		</ul> 
		<?php if(permission_level() >= $djland_permission_levels['staff']): ?>


		<!-- Begin Tab 1 "member search" -->		
		<div id='search' class='membership clearfix'>
			<ul id='membership_header' name='search' class='clean-list inline-list	'>
					<li id='search'>Search By:
						<select id='search_by'>
							<option value='name'>Name</option>
							<option value='interest'>Interest</option>
							<option value='member_type'>Member Type</option>
						</select>
						<input class='search_value' name='name' placeholder='Enter a name'/>
						<select class='search_value hidden' name='interest'>
							<?php
								foreach($djland_interests as $key=>$value){
									echo "<option value='{$value}'>{$key}</input>";
								}
							?>
						</select>
						<select class='search_value hidden' name='member_type'>
							<?php
								foreach($djland_member_types as $key=>$value){
									echo "<option value='{$value}'>{$key}</input>";
								}
							?>
						</select>
					</li>
					<li>
						<select id='paid_status'>
							<option value='both'>Paid or Unpaid for</option>
							<option value='1'>Only Paid for </option>
							<option value='0'>Only Unpaid for</option>
						</select>
					</li>
					<li>
						<select id='year_select'>
							
						</select>

					</li>
					<li>
						Order By
						<select id='order_by'>
							<option value='id'>Join Date</option>
							<option value='lastname'>Lastname</option>
							<option value='firstname'>Firstname</option>
							<option value='type'>Member Type</option>
						</select>
					</li>
					<li>
						<button class='member_submit' name='search'>Search</button>
					</li>
					<li>
						<button id='member_table_save'>Save Comments</button>
					</li>
			</ul>
			<div id='membership_result'>
				<div id='search_loading' class='col1'>Loading...</div>
				<table id='membership_table' name='search'>
					<tr id='headerrow' class='hidden'><th>Name</th><th>Email</th><th>Phone</th><th>Type</th><th>Staff Comments</th><th><button id='delete_button'>Delete</button></th></tr>
				</table>
			</div>
   		</div>
		<!-- Begin Tab 2 "member view" -->   		
   		<div id='view' class='membership clearfix'>
   			<div class='col1'>
   				<h4>Edit Member</h4>
   			</div>
   			<div id='member_loading' class='col1'>Loading...</div>
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
					<div class='col1'>
						<div class='col6'>Interests for:</div><select id ='membership_year'></select> 
					</div>

					
					<div class='col1'>
						<?php 
						foreach($djland_interests as $key=>$interest){ 
							echo "<div class='col3 text-right'>{$key}";
							if($interest == 'other'){echo " <input id='{$interest}' placeholder='Enter interest'/>";}
							else echo "<input type='checkbox' id='{$interest}'/></div>";
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
				<hr>
				<div class='containerrow'>
					<div class='col1 text-left'>Permission Levels:</div>
					<?php
						foreach($djland_permission_levels as $key=>$value){
							if($key != 'operator') echo "<div class='col6'>{$key} <input type=checkbox id='$key'/></div>";
						}
					?>
				</div>
				<hr>
				<div class='containerrow'>
					<div class='col1'>
						Change Current Password: <input id='password' placeholder='Enter new password.'/> 
					</div>
				</div>

				<hr>
				<div class='containerrow'>
					<div class='col1'>
						<button name='edit' class='member_submit red' disabled='true'>Form Not Complete</button>
					</div>	
				</div>
				<div class='containerrow'>
					<div class='col1'>
						*indicates a required field 
					</div>
				</div>
			</div>	
   		</div>

   		<!-- Begin Tab 3 "report view" -->  
		<div id='report' class='membership clearfix'>
			<div class='col1'>
   				<h4>Yearly Report</h4>
   			</div>
			<div class='col1'>
				<select id="year_select">
				</select>
				<button class="member_submit" name="report">Get Yearly Report</button>
			</div>
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
   		<div id='admin' class='membership clearfix'>
   			<div class='col1'>
   				<h4>Admin Panel</h4>
   			</div>

   			<ul class="inner clean-list text-center">
	            <li class="col4"><button id="year_rollover">Start new membership year</button></li>
	            <li class="col4" id="current_year">Loading Current Membership Year...</li>
	            <li class="col4" id="current_cutoff">Loading Current Cutoff...</li>
	            <li class="col4">Current membership year members must have renewed, and paid for to this current year</li>
    		</ul>

   		</div>
        <!-- Begin Tab 5 "email view" -->  
   		<?php endif; ?>
   		<div id='email' class='membership clearfix'>
   			<div class='col1'>
   				<h4>Email List</h4>
   			</div>	
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