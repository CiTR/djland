<?php
	require_once("headers/security_header.php");
	require_once("headers/menu_header.php");

	if( permission_level() < $djland_permission_levels['volunteer_leader']['level']){
		header("Location: main.php");
	}
	?>
<html>
	<head>
		<meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">
		<meta charset="utf-8">
		<link rel=stylesheet href='css/style.css' type='text/css'>


		<title>DJLAND | Membership</title>

		<script type='text/javascript' src='js/jquery-1.11.3.min.js'></script>
		<script type='text/javascript' src='js/jquery-ui-1.11.3.min.js'></script>
        <script type='text/javascript' src='js/constants.js'/></script>
        <script type='text/javascript' src='js/membership/member.js'></script>
        <script type='text/javascript' src='js/membership/functions.js'></script>
		<script type='text/javascript' src='js/membership/membership.js'></script>
		<script type="text/javascript" src="js/membership/admin.js"></script>
		<script type="text/javascript" src="js/test.js"></script>


		<script>
			$(function() {
			$( ".datepicker" ).datepicker({ dateFormat: "yy-mm-dd" });
			});
		</script>
	</head>
	<body class='wallpaper'>
		<?php
        print_menu();
        ?>

        <button id='print_friendly'>Print View</button>

		<ul id ='tab-nav'>
			<?php if(permission_level() >= $djland_permission_levels['staff']['level']) : ?>
				<li class = 'tab nodrop active-tab member_action' name='search'>Search Members</li>
				<li class = 'tab nodrop inactive-tab member_action' name='view' data='1'>View Member</li>
				<li class = 'tab nodrop inactive-tab member_action' name='report'>Yearly Report</li>
			<?php endif;
			if(permission_level() >= $djland_permission_levels['administrator']['level']) : ?>
			<li class = 'tab nodrop inactive-tab member_action' name='admin'>Membership Admin</li>
			<?php endif;
			if(permission_level() >= $djland_permission_levels['volunteer_leader']['level']) : ?>
			<li class = 'tab nodrop inactive-tab member_action' name='email'>Send Emails</li>
			<?php endif;?>
		</ul>
		<?php if(permission_level() >= $djland_permission_levels['staff']['level']): ?>


		<!-- Begin Tab 1 "member search" -->
		<div id='search' class='membership grey clearfix'>
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
									echo "<option value='{$value}'>{$key}</option>";
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
						<select class='year_select' name='search'>

						</select>

					</li>
					<li>
						Order By
						<select id='order_by'>
							<option value='created'>Renew Date</option>
							<option value='id'>Join Date</option>
							<option value='lastname'>Lastname</option>
							<option value='firstname'>Firstname</option>
							<option value='member_type'>Member Type</option>
						</select>
					</li>
					<li>
						<button class='member_submit' name='search'>Search</button>
					</li>
					<li>
						<button id='save_comments'>Save Comments</button>
					</li>
			</ul>

			<div id='membership_result' class='overflow_auto height_cap' name='search'>
				<div id='search_loading' class='col1 text-center' name='search'>Loading...</div>
				<table id='membership_table' name='search'>
					<tr id='headerrow' class='hidden'>
						<th>Name</th>
						<th>Email</th>
						<th>Phone</th>
						<th>Type</th>
						<th>Staff Comments</th>
						<th>Year</th>
						<?php if(permission_level() >= $djland_permission_levels['staff']['level']) : ?>
							<th><button id='delete_button'>Delete</button></th>
						<?php endif; ?>
						</tr>
				</table>
			</div>
   		</div>
		<!-- Begin Tab 2 "member view" -->
   		<div id='view' class='hidden membership grey clearfix'>
   			<div class='col1'>
   				<h4>Edit Member</h4>
   			</div>
   			<div id='member_loading' class='col1 text-center' name='view'>Loading...</div>
			<div id='member_result' class = 'container hidden'>
				<div id='row1' class='containerrow'>
					<div class='col5'>Username: </div>
					<div class='col5' id='username' name='username'></div>
				</div>

				<div id='row2' class='containerrow'>
					<div class='col5'>First Name: </div>
					<div class='col5' ><input id='firstname' class='required' placeholder='firstname' maxlength='50'/></div>
					<div class='col5'>Last Name: </div>
					<div class='col5' ><input id='lastname' class='required' placeholder='lastname' maxlength='50'/></div>
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
						</select>
					</div>
					<div class='col5'>Postal Code*:</div>
					<div class='col5'><input id='postalcode' class='required' placeholder='Postal Code' maxlength='6'/></div>
				</div>
				<div class='containerrow'>
					<div class='col5'>UBC Alumni:</div>
					<div class='col5'><input id='alumni' type='checkbox'/></div>
					<div class='col5'>Member Since: </div>
					<div class='col5' id='since'>1927</div>
				</div>
				<div class='containerrow'>
					<div class='col5'>Canadian Citizen*:</div>
					<div class='col5'>
						<input id='canadian_citizen' type='checkbox'>
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
					<div class='span3col5'>
						I would like to incorporate CiTR into my courses(projects,practicums,etc.): <input id='integrate'  name='integrate' type='checkbox' />
					</div>
				</div>
				<div class='containerrow'>
					<div class='col5'>Do you have a show?*:</div>
					<div class='col5'><input id='has_show' type='checkbox' ></div>
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
				<div class='contrainerrow'>
					<ol class='inline-list col1 member_shows'>

					</ol>
				</div>
				<hr>
				<div class='containerrow'>
					<div class='col2'>
						<div class='col4'>Interests for: </div><div class='side-padded'><select class='left' id ='membership_year'></select></div>
					</div>
					<div class='col2'><div class='left'>Paid<input type='checkbox' id='paid'></div> </div>

					<div class='col1'>
						<?php foreach($djland_interests as $key=>$interest): ?>
						<div class='col3 text-right'>
							<?php
							echo $key;
							if($interest == 'other'): ?>
							<input id='<?php echo $interest ?>' placeholder='Enter interest'/>
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
				<div class='containerrow'>
					<div class='col6'>Staff Comments:</div>
					<textarea id='comments' placeholder='Enter a staff only comment' class='largeinput' rows='3'></textarea>
				</div>
				<hr>
				<div class='containerrow'>
					<div class='col1 text-left'>Training Completion Status:</div>
					<?php
					foreach($djland_training AS $key=>$value){
						echo "<div class='col5 text-right'>{$key} <input type='checkbox' id={$value}></div>";
					}
					?>
				</div>
				<hr>
				<div class='containerrow'>
					<div class='col1 text-left'>Permission Levels:</div>

					<?php
						foreach($djland_permission_levels as $key=>$value){
							if($key != 'operator') echo "<div href='#'  title='".$value['tooltip']."' class='col7 custom_tooltip'><span title=''  ><label for='level_{$key}'>{$value['name']}</label><input type=radio id='level_{$key}' name='permission'/></span></div>";
						}
					?>
				</div>
				<hr>
				<div class='containerrow'>
					<div class='col1 text-center'>
						Change Current Password: <input id='password' placeholder='Enter new password.'>
					</div>
				</div>

				<hr>
				<div class='containerrow'>
					<div class='col1 text-center'>
						<button name='edit' class='member_submit red' disabled='true'>Form Not Complete</button>
					</div>
				</div>
				<div class='containerrow'>
					<div class='col1 text-center'>
						*indicates a required field
					</div>
				</div>
			</div>
   		</div>

   		<!-- Begin Tab 3 "report view" -->
		<div id='report' class='hidden membership grey clearfix'>
			<div class='col1'>
   				<h4>Yearly Report</h4>
   			</div>
			<div class='col1 text-center'>
				<select class='year_select' name='report'>
				</select>
				<button class='member_submit' name='report'>Get Yearly Report</button>
			</div>
   			<div id="membership_result" class='overflow_auto height_cap'>
   				<div class='col2'>
	   				<h4>Members Registered this year</h4>
	   				<ul class='clean-list'>
	   					<li class='report_row total'>
	   						<div class='col2'>Total:</div>
	   						<div id='report_total' class='col2'></div>
	   					</li>
	   					<li class='report_row paid'>
	   						<div class='col2'>Paid:</div>
	   						<div id='report_paid' class='col2'></div>
	   					</li>
	   					<li class='report_row unpaid'>
	   						<div class='col2'>Unpaid:</div>
	   						<div id='report_unpaid' class='col2'></div>
   						</li>
	   					<li class='report_row student'>
	   						<div class='col2'>Student:</div>
	   						<div id='report_student' class='col2'></div>
	   					</li>
	   					<li class='report_row community'>
	   						<div class='col2'>Community:</div>
	   						<div id='report_community' class='col2'></div>
   						</li>
	   					<li class='report_row staff'>
	   						<div class='col2'>Staff:</div>
	   						<div id='report_staff' class='col2'></div>
   						</li>
	   				</ul>
	   			</div>
	   			<div class='col2'>
	   				<h4>Interest Levels from Paid Members</h4>
	   				<ul class='clean-list'>
	   				<?php

					foreach($djland_interests as $key=>$value){
						echo "<li class='report_row'><div class='col2'>{$key}:</div><div id='report_{$value}' class='col2'></div></li>";
					}
	   				?>
	   				</ul>
   				</div>
   			</div>
   		</div>
   		<?php if(permission_level() >= $djland_permission_levels['staff']['level']): ?>
   		<!-- Begin Tab 4 "member admin" -->
   		<div id='admin' class='hidden membership grey clearfix'>
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
   		<?php endif; ?>
        <!-- Begin Tab 5 "email view" -->
   		<?php endif;
			if(permission_level() >= $djland_permission_levels['volunteer']['level']):
			?>
   		<div id='email' class='hidden membership grey clearfix'>
   			<div class='col1'>
   				<h4>Email List</h4>
   			</div>
   			<ul id='membership_header' name='email' class='clean-list inline-list'>
   				<li>Get paid members
   					<select id='email_select'>
   						<option value='interest'>that are interested in</option>
   						<option value='member_type'>of type</option>
   					</select>
				</li>
   				<li>
   					<select name='interest' class='email_select_value'>
   						<option value='all'>All</option>
   						<?php
   							foreach($djland_interests as $key=>$value){
   								echo "<option value='{$value}'>{$key}</option>";
   							}
   						?>
   					</select>
   					<select name='member_type' class='email_select_value hidden'>
   						<option value='all'>All</option>
   						<?php
   							foreach($djland_member_types as $key=>$value){
   								echo "<option value='{$value}'>{$key}</option>";
   							}
   						?>
   					</select>
   				</li>
   				<li>
   					for
   					<select class='year_select' name='email'>
					</select>
				</li>
				<li>
   	   				Use date range instead of membership year<input type='checkbox' id='email_date_range'>
   	   			</li>
   				<li>
   					<button class='member_submit' name='email'>Get List</button>
   				</li>
   	   			<li>
   	   				<div id='email_date_container' class='hidden'>
   	   					from<input type='text' id='from'>
                    	to<input type='text' id='to'>
                    </div>
   				</li>
   			<div id='membership_result' name='email' class='containerrow'>
   				<br/>
   				<textarea id='email_list' class='largeinput center' placeholder='Email List Will Be Generated Here'></textarea>
			</div>
   		</div>
		<?php endif; ?>
	</body>
</html>
