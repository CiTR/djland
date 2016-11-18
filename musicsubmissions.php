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

		<title>DJLAND | Music Submissions</title>

		<script type='text/javascript' src='js/jquery-1.11.3.min.js'></script>
		<script type='text/javascript' src='js/jquery-ui-1.11.3.min.js'></script>
        <script type='text/javascript' src='js/constants.js'/></script>
        <script type='text/javascript' src='js/membership/member.js'></script>
        <script type='text/javascript' src='js/membership/functions.js'></script>
		<script type='text/javascript' src='js/membership/membership.js'></script>
		<script type="text/javascript" src="js/membership/admin.js"></script>
		<script type="text/javascript" src="js/test.js"></script>
		<script type="text/javascript" src="js/musicsubmissions/musicsubmissions.js"></script>

		<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

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
			<?php if(permission_level() >= $djland_permission_levels['volunteer']['level']) : ?>
				<li class="tab nodrop active-tab member_action" name="new_submissions">New Submissions</li>
				<li class="tab nodrop inactive-tab member_action" name="view_submissions" data="1">View Submission</li>
				<li class="tab nodrop inactive-tab member_action" name="reviewed_submissions">Reviewed Submsisions</li></li>
				<li class="tab nodrop inactive-tab member_action" name="tag">Tag Accepted Submsisions</li></li>
			<?php endif;
			if(permission_level() >= $djland_permission_levels['staff']['level']) : ?>
			<li class="tab nodrop inactive-tab member_action" name="admin">Submission Admin</li>
			<li class="tab nodrop inactive-tab member_action" name="manual_submission">Manual Submission</li>
			<?php endif; ?>
		</ul>

		<?php if(permission_level() >= $djland_permission_levels['volunteer']['level']): ?>

		<!-- Begin Tab 1 "member search" -->
		<div id="new_submissions" class="membership grey clearfix">
			<ul id="submission_header" name="search" class="clean-list inline-list	">
				<li id="search">Search By:
					<select id="search_by">
						<option value="name">Submission Date</option>
						<option value="interest">Date of Release</option>
						<option value="member_type">Artist</option>
						<option value="album">Album</option>
						<option value="Assigned to">Assignee</option>
					</select>
					<input class="search_value" name="name" placeholder="Text">
					<select class="search_value hidden" name="interest">
					<option value="arts">Arts</option><option value="ads_psa">Ads and PSAs</option><option value="digital_library">Digital Library</option><option value="dj">DJ101.9</option><option value="discorder">Illustrate for Discorder</option><option value="discorder_2">Writing for Discorder</option><option value="live_broadcast">Live Broadcasting</option><option value="music">Music</option><option value="news">News</option><option value="photography">Photography</option><option value="programming_committee">Programming Committee</option><option value="promotions_outreach">Promos and Outreach</option><option value="show_hosting">Show Hosting</option><option value="sports">Sports</option><option value="tabling">Tabling</option><option value="tech">Web and Tech</option><option value="womens_collective">Women's Collective</option><option value="indigenous_collective">Indigenous Collective</option><option value="accessibility_collective">Accessibility Collective</option><option value="other">Other						</option></select>
					<select class="search_value hidden" name="member_type">
					<option value="Student">UBC Student</option><option value="Community">Community Member</option><option value="Staff">Staff</option><option value="Lifetime">Lifetime</option>						</select>
				</li>
				<li>
					<select id="paid_status">
						<option value="both">Complete or Uncomplete</option>
						<option value="1">Only Complete</option>
						<option value="0">Only Uncomplete</option>
					</select>
				</li>
				<li>
					<select class="year_select" name="search">
					<option value="2016/2017">2016/2017</option><option value="2015/2016">2015/2016</option><option value="2014/2015">2014/2015</option><option value="2013/2014">2013/2014</option><option value="2011/2012">2011/2012</option><option value="all">All Years</option></select>
				</li>
				<li>
					Order By
					<select id="order_by">
						<option value="created">Submission Date</option>
						<option value="id">Date of Release</option>
						<option value="lastname">Artist</option>
						<option value="firstname">Album</option>
						<option value="member_type">Genre</option>
						<option value="Assignee">Assignee</option>
					</select>
				</li>
				<li>
					<button class="submisison_submit" name="search">Search</button>
				</li>
				<li>
					<button id="save_comments">Save Comments</button>
				</li>
			</ul>

			<div id="submisison_result" class="left overflow_auto height_cap" name="search">
				<table id="submission_table" name="search">
					<tbody>
						<tr id="headerrow" style="display: table-row;">
							<th>Artist</th>
							<th>Album</th>
							<th>Date of Release</th>
							<th>Genre</th>
							<th>Date Submitted</th>
							<th>Staff Comments</th>
							<th>Assignee  </th>
							<th><button id="delete_button">Delete</button></th>
						</tr>
						<tr id="row1277" class="submission_row" name="1277"><td class="submission_row_element name">DJ Shadow</td><td class="submission_row_element email">Endtroducing.....</td><td class="submission_row_element primary_phone">November 19th, 1996</td><td class="submission_row_element submission_type">Hip Hop</td></td><td class="submission_row_element membership_year">May 10th, 2016</td><td><input class="staff_comment" id="comment1277" value=""></td><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td><td><input type="checkbox" class="delete_submission" id="delete_0"></td><div class="check hidden">❏</div></tr>
						<tr id="row1276" class="submission_row" name="1276"><td class="submission_row_element name">Led Zeppelin</td><td class="submission_row_element email">Led Zeppelin IV</td><td class="submission_row_element primary_phone">November 8th 1971</td><td class="submission_row_element submission_type">Classic Rock</td></td><td class="submission_row_element membership_year">June 10th, 2015</td><td><input class="staff_comment" id="comment1276" value=""></td><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td><td><input type="checkbox" class="delete_submission" id="delete_1"></td><div class="check hidden">❏</div></tr>
						<tr id="row1275" class="submission_row" name="1275"><td class="submission_row_element name">Supermoon</td><td class="submission_row_element email">Playland</td><td class="submission_row_element primary_phone">May 20th 2016</td><td class="submission_row_element submission_type">Rock</td><td class="submission_row_element membership_year">May 21st 2016</td><td><input class="staff_comment" id="comment1275" value=""></td><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td><td><input type="checkbox" class="delete_submission" id="delete_2"></td><div class="check hidden">❏</div></tr>
						<tr id="row1274" class="submission_row" name="1274"><td class="submission_row_element name">Graftician</td><td class="submission_row_element email">Wander/Weave</td><td class="submission_row_element primary_phone">July 22nd, 2016</td><td class="submission_row_element submission_type">Experimental Electronic</td><td class="submission_row_element membership_year">July 22nd 2016</td><td><input class="staff_comment" id="comment1274" value=""></td><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td><td><input type="checkbox" class="delete_submission" id="delete_3"></td><div class="check hidden">❏</div></tr>
						<tr id="row1273" class="submission_row" name="1273"><td class="submission_row_element name">Koban</td><td class="submission_row_element email">Abject Obsessions</td><td class="submission_row_element primary_phone">September 15th 2016</td><td class="submission_row_element submission_type">Goth, Punk</td><td class="submission_row_element membership_year">September 20th 2016</td><td><input class="staff_comment" id="comment1273" value=""></td><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td><td><input type="checkbox" class="delete_submission" id="delete_4"></td><div class="check hidden">❏</div></tr>
						<tr id="row1272" class="submission_row" name="1272"><td class="submission_row_element name">Fuzzy P</td><td class="submission_row_element email">On A Lawn</td><td class="submission_row_element primary_phone">June 10th 2016</td><td class="submission_row_element submission_type">Indie Rock</td><td class="submission_row_element membership_year">June 26th 2016</td><td><input class="staff_comment" id="comment1272" value=""><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td></td><td><input type="checkbox" class="delete_submission" id="delete_5"></td><div class="check hidden">❏</div></tr>
					</tbody>
				</table>
			</div>
   		</div>
		<!-- Begin Tab 2 Submission view" -->
		<div id='view_submissions' class='hidden membership grey clearfix'>
   			<div class="col1">
				<br>
   				<h3>Review Submission</h3>
				<br>
   			</div>
			<hr>
   			<div class="container" style="display: block;">
				<div class="containerrow">
					<div class="col5">Band Name: </div>
					<div class="col5" id="username" name="username">Band Name Here</div>
					<div class="col5">From: </div>
					<div class="col5" id="username" name="username">Vancouver, Canada</div>
				</div>

				<div class="containerrow">
					<div class="col5">Album: </div>
					<div class="col5">Album Name Here</div>
					<div class="col5">Label: </div>
					<div class="col5">Mint Records</div>
				</div>
				<div class="containerrow">
					<div class="col5">Genre: </div>
					<div class="col5">Genre Here</div>
					<div class="col5">Genre Tags: </div>
					<div class="col5">Tag1, Tag2, Tag3</div>
				</div>
				<div class="containerrow">
					<div class="col5">Release Date: </div>
					<div class="col5">Month, Day Year</div>
					<div class="col5">Submission Date: </div>
					<div class="col5">Month, Day Year: </div>
				</div>
				<div class="containerrow">
					<div class="col5">Album Credit: </div>
					<div class="col4">John Doe, Lucy Lu, Fred Smith</div>
				</div>

				<hr>

				<div class="containerrow padded-left">
					<div class="col1 text-left padded">Submitted Album Description: </div>
				</div>
				<div class="containerrow padded padded-left">
					<div class="col2 text-left"><textarea rows=20 cols=65 readonly>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean et mi dui. Donec enim risus, vestibulum sed faucibus ac, condimentum vitae ligula. Pellentesque consectetur, purus sed fermentum tempor, velit elit congue sem, eu feugiat ipsum tortor eu risus. In hendrerit tristique ultricies. Proin faucibus ipsum diam, sed molestie lacus molestie vitae. Donec a euismod dolor, et maximus nisl. Cras sagittis ligula ut massa ornare vestibulum tristique vitae mauris. In metus orci, blandit a sodales at, auctor in leo. Nulla non facilisis orci, ac imperdiet leo. Duis eu purus sit amet felis convallis suscipit id a lacus. </textarea></div>
					<div class="col3 center"><img src="images/albumart.jpg"></img></div>
				</div>

				<hr>

				<div class="containerrow padded">
					<div class="col3"><h3>Your Feedback: </h3></div>
				</div>
				<div class="containerrow padded">
					<div class="col1"></div>
					<div class="col4">Track 1: Song Title Here</div>
					<div class="col3"><audio controls><source src="horse.mp3" type="audio/mpeg"></audio></div>
				</div>
				<div class="containerrow padded">
					<div class="col4">Submitted Description: </div>
					<div class="col4">Short Desc. here</div>
				</div>
				<div class="containerrow padded">
					<div class="col6">Track Artist: </div>
					<div class="col6">Artist Here (defaults to Album artist) </div>
					<div class="col6">Track Credit: </div>
					<div class="col6">Names here (defaults to album credit)</div>
				</div>
				<div class="containerrow padded">
					<div class="col6">Comments:</div>
					<textarea id="comments" placeholder="What did you think about the song?" class="largeinput" rows="3"></textarea>
				</div>
				<div class="containerrow padded">
					<div class="col1"></div>
					<div class="col4">Track 2: Song Title Here</div>
					<div class="col3"><audio controls><source src="horse.mp3" type="audio/mpeg"></audio></div>
				</div>
				<div class="containerrow padded">
					<div class="col4">Submitted Description: </div>
					<div class="col4">Short Desc. here</div>
				</div>
				<div class="containerrow padded">
					<div class="col6">Track Artist: </div>
					<div class="col6">Artist Here (defaults to Album artist) </div>
					<div class="col6">Track Credit: </div>
					<div class="col6">Names here (defaults to album credit)</div>
				</div>
				<div class="containerrow padded">
					<div class="col6">Comments:</div>
					<textarea id="comments" placeholder="What did you think about the song?" class="largeinput" rows="3"></textarea>
				</div>
				<div class="containerrow padded">
					<div class="col1"></div>
					<div class="col4">Track 3: Song Title Here</div>
					<div class="col3"><audio controls><source src="horse.mp3" type="audio/mpeg"></audio></div>
				</div>
				<div class="containerrow padded">
					<div class="col4">Submitted Description: </div>
					<div class="col4">Short Desc. here</div>
				</div>
				<div class="containerrow padded">
					<div class="col6">Track Artist: </div>
					<div class="col6">Artist Here (defaults to Album artist) </div>
					<div class="col6">Track Credit: </div>
					<div class="col6">Names here (defaults to album credit)</div>
				</div>
				<div class="containerrow padded">
					<div class="col6">Comments:</div>
					<textarea id="comments" placeholder="What did you think about the song?" class="largeinput" rows="3"></textarea>
				</div>

				<hr>

				<div class="containerrow padded">
					<div class="col4">What did you think about the album as a whole?:</div>
					<textarea id="comments" placeholder="What did you think about the album?" class="largeinput" rows="5"></textarea>
				</div>

				<div class="containerrow padded">
					<div class="col3">Would you Approve this Album to go in our library?:</div>
					<div class="col5 left"><select style="font-size:18px;" class="search_value" name="cars"><option value="volvo">Yes</option>  <option value="saab">No</option></select></div>
				</div>


				<hr>

				<div class="containerrow">
					<div class="col1 text-center">
						<button name="edit" class="member_submit">Submit</button>
					</div>
				</div>
				<div class="containerrow">
					<div class="col1 text-center">
						*indicates a required field
					</div>
				</div>
			</div>
   		</div>

   		<!-- Begin Tab 3 "reviewed submissions view" -->
		<div id='reviewed_submissions' class='hidden membership grey clearfix'>
			<ul id="submission_header" name="search" class="clean-list inline-list	">
					<li id="search">Search By:
						<select id="search_by">
							<option value="name">Submission Date</option>
							<option value="interest">Date of Release</option>
							<option value="member_type">Artist</option>
							<option value="album">Album</option>
							<option value="Assigned to">Reviewed By</option>
						</select>
						<input class="search_value" name="name" placeholder="Text">
						<select class="search_value hidden" name="interest">
							<option value="arts">Arts</option><option value="ads_psa">Ads and PSAs</option><option value="digital_library">Digital Library</option><option value="dj">DJ101.9</option><option value="discorder">Illustrate for Discorder</option><option value="discorder_2">Writing for Discorder</option><option value="live_broadcast">Live Broadcasting</option><option value="music">Music</option><option value="news">News</option><option value="photography">Photography</option><option value="programming_committee">Programming Committee</option><option value="promotions_outreach">Promos and Outreach</option><option value="show_hosting">Show Hosting</option><option value="sports">Sports</option><option value="tabling">Tabling</option><option value="tech">Web and Tech</option><option value="womens_collective">Women's Collective</option><option value="indigenous_collective">Indigenous Collective</option><option value="accessibility_collective">Accessibility Collective</option><option value="other">Other						</option></select>
						<select class="search_value hidden" name="member_type">
							<option value="Student">UBC Student</option><option value="Community">Community Member</option><option value="Staff">Staff</option><option value="Lifetime">Lifetime</option>						</select>
					</li>
					<li>
						<select id="paid_status">
							<option value="both">Complete or Uncomplete</option>
							<option value="1">Only Complete</option>
							<option value="0">Only Uncomplete</option>
						</select>
					</li>
					<li>
						<select class="year_select" name="search">

						<option value="2016/2017">2016/2017</option><option value="2015/2016">2015/2016</option><option value="2014/2015">2014/2015</option><option value="2013/2014">2013/2014</option><option value="2011/2012">2011/2012</option><option value="all">All Years</option></select>

					</li>
					<li>
						Order By
						<select id="order_by">
							<option value="created">Submission Date</option>
							<option value="id">Date of Release</option>
							<option value="lastname">Artist</option>
							<option value="firstname">Album</option>
							<option value="member_type">Genre</option>
							<option value="Assignee">Reviewed By</option>
						</select>
					</li>
					<li>
						<button class="submission_submit" name="search">Search</button>
					</li>
					<li>
						<button id="save_comments">Save Comments</button>
					</li>
			</ul>

			<div id="submission_result" class="overflow_auto height_cap" name="search">
				<table id="submisison_table" name="search">
					<tbody><tr id="headerrow" class="" style="display: table-row;">
						<th>Artist</th>
						<th>Album</th>
						<th>Date of Submission</th>
						<th>Genre</th>
						<th>Staff Comments</th>
						<th>Reviewed By</th>
						<th>Approve/Discard</th>
					</tr>
				<tr id="row1277" class="submission_row" name="1277"><td class="submission_row_element name">DJ Shadow</td><td class="submission_row_element email">Endtroducing.....</td><td class="submission_row_element membership_year">May 10th, 2016</td><td class="submission_row_element submission_type">Hip Hop</td></td><td><input class="staff_comment" id="comment1277" value=""></td><td>Hugo Noriega</td><td><input type="checkbox" class="delete_submission" id="delete_0"><div class="check hidden">❏</div><input type="checkbox" class="delete_submission" id="delete_0"><div class="check hidden">❏</div></tr><tr id="row1276" class="submission_row" name="1276"><td class="submission_row_element name">Led Zeppelin</td><td class="submission_row_element email">Led Zeppelin IV</td><td class="submission_row_element membership_year">June 10th, 2015</td><td class="submission_row_element submission_type">Classic Rock</td><td><input class="staff_comment" id="comment1276" value=""></td><td>Madeline Taylor</td><td><input type="checkbox" class="delete_submission" id="delete_0"><div class="check hidden">❏</div><input type="checkbox" class="delete_submission" id="delete_0"><div class="check hidden">❏</div></tr><tr id="row1275" class="submission_row" name="1275"><td class="submission_row_element name">Supermoon</td><td class="submission_row_element email">Playland</td><td class="submission_row_element primary_phone">May 20th 2016</td><td class="submission_row_element submission_type">Rock</td><td><input class="staff_comment" id="comment1275" value=""></td><td>Andy Resto</td><td><input type="checkbox" class="delete_submission" id="delete_2"><div class="check hidden">❏</div><input type="checkbox" class="delete_submission" id="delete_2"></td><div class="check hidden">❏</div></tr><tr id="row1274" class="submission_row" name="1274"><td class="submission_row_element name">Graftician</td><td class="submission_row_element email">Wander/Weave</td><td class="submission_row_element primary_phone">July 22nd, 2016</td><td class="submission_row_element submission_type">Experimental Electronic</td><td><input class="staff_comment" id="comment1274" value=""></td><td>Andy Resto</td><td><input type="checkbox" class="delete_submission" id="delete_3"><div class="check hidden">❏</div><input type="checkbox" class="delete_submission" id="delete_3"></td><div class="check hidden">❏</div></tr><tr id="row1273" class="submission_row" name="1273"><td class="submission_row_element name">Koban</td><td class="submission_row_element email">Abject Obsessions</td><td class="submission_row_element primary_phone">September 15th 2016</td><td class="submission_row_element submission_type">Goth, Punk</td><td><input class="staff_comment" id="comment1273" value=""></td><td>Andy Resto</td><td><input type="checkbox" class="delete_submission" id="delete_4"><div class="check hidden">❏</div><input type="checkbox" class="delete_submission" id="delete_4"></td><div class="check hidden">❏</div></tr><tr id="row1272" class="submission_row" name="1272"><td class="submission_row_element name">Fuzzy P</td><td class="submission_row_element email">On A Lawn</td><td class="submission_row_element membership_year">June 26th 2016</td><td class="submission_row_element submission_type">Indie Rock</td><td><input class="staff_comment" id="comment1272" value=""><td>Emily Stryker</td></td><td><input type="checkbox" class="delete_submission" id="delete_5"><div class="check hidden">❏</div><input type="checkbox" class="delete_submission" id="delete_5"></td><div class="check hidden">❏</div></tr></tbody></table>
			</div>
   		</div>

		<!--Begin Tab 4 "Tagging Interface" -->
		<div id="tag" class="hidden membership grey clearfix">
			<ul id="submission_header" name="search" class="clean-list inline-list	">
				<li id="search">Search By:
					<select id="search_by">
						<option value="name">Submission Date</option>
						<option value="interest">Date of Release</option>
						<option value="member_type">Artist</option>
						<option value="album">Album</option>
						<option value="Assigned to">Assignee</option>
					</select>
					<input class="search_value" name="name" placeholder="Text">
					<select class="search_value hidden" name="interest">
					<option value="arts">Arts</option><option value="ads_psa">Ads and PSAs</option><option value="digital_library">Digital Library</option><option value="dj">DJ101.9</option><option value="discorder">Illustrate for Discorder</option><option value="discorder_2">Writing for Discorder</option><option value="live_broadcast">Live Broadcasting</option><option value="music">Music</option><option value="news">News</option><option value="photography">Photography</option><option value="programming_committee">Programming Committee</option><option value="promotions_outreach">Promos and Outreach</option><option value="show_hosting">Show Hosting</option><option value="sports">Sports</option><option value="tabling">Tabling</option><option value="tech">Web and Tech</option><option value="womens_collective">Women's Collective</option><option value="indigenous_collective">Indigenous Collective</option><option value="accessibility_collective">Accessibility Collective</option><option value="other">Other						</option></select>
					<select class="search_value hidden" name="member_type">
					<option value="Student">UBC Student</option><option value="Community">Community Member</option><option value="Staff">Staff</option><option value="Lifetime">Lifetime</option>						</select>
				</li>
				<li>
					<select id="paid_status">
						<option value="both">Complete or Uncomplete</option>
						<option value="1">Only Complete</option>
						<option value="0">Only Uncomplete</option>
					</select>
				</li>
				<li>
					<select class="year_select" name="search">
					<option value="2016/2017">2016/2017</option><option value="2015/2016">2015/2016</option><option value="2014/2015">2014/2015</option><option value="2013/2014">2013/2014</option><option value="2011/2012">2011/2012</option><option value="all">All Years</option></select>
				</li>
				<li>
					Order By
					<select id="order_by">
						<option value="created">Submission Date</option>
						<option value="id">Date of Release</option>
						<option value="lastname">Artist</option>
						<option value="firstname">Album</option>
						<option value="member_type">Genre</option>
						<option value="Assignee">Assignee</option>
					</select>
				</li>
				<li>
					<button class="submisison_submit" name="search">Search</button>
				</li>
				<li>
					<button id="save_comments">Save Comments</button>
				</li>
			</ul>

			<div id="submisison_result" class="left overflow_auto height_cap" name="search">
				<table id="submission_table" name="search">
					<tbody>
						<tr id="headerrow" style="display: table-row;">
							<th>Artist</th>
							<th>Album</th>
							<th>Date of Release</th>
							<th>Genre</th>
							<th>Date Submitted</th>
							<th>Staff Comments</th>
							<th>Assignee</th>
							<th><button id="delete_button">Delete</button></th>
						</tr>
						<tr id="tagrow1" class="submission_row tag_row" name="1277"><td class="submission_row_element name">DJ Shadow</td><td class="submission_row_element email">Endtroducing.....</td><td class="submission_row_element primary_phone">November 19th, 1996</td><td class="submission_row_element submission_type">Hip Hop</td></td><td class="submission_row_element membership_year">May 10th, 2016</td><td><input class="staff_comment" id="comment1277" value=""></td><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td><td><input type="checkbox" class="delete_submission" id="delete_0"></td><div class="check hidden">❏</div></tr>
						<tr id="tagrow2" class="submission_row tag_row" name="1276"><td class="submission_row_element name">Led Zeppelin</td><td class="submission_row_element email">Led Zeppelin IV</td><td class="submission_row_element primary_phone">November 8th 1971</td><td class="submission_row_element submission_type">Classic Rock</td></td><td class="submission_row_element membership_year">June 10th, 2015</td><td><input class="staff_comment" id="comment1276" value=""></td><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td><td><input type="checkbox" class="delete_submission" id="delete_1"></td><div class="check hidden">❏</div></tr>
						<tr id="tagrow3" class="submission_row tag_row" name="1275"><td class="submission_row_element name">Supermoon</td><td class="submission_row_element email">Playland</td><td class="submission_row_element primary_phone">May 20th 2016</td><td class="submission_row_element submission_type">Rock</td><td class="submission_row_element membership_year">May 21st 2016</td><td><input class="staff_comment" id="comment1275" value=""></td><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td><td><input type="checkbox" class="delete_submission" id="delete_2"></td><div class="check hidden">❏</div></tr>
						<tr id="tagrow4" class="submission_row tag_row" name="1274"><td class="submission_row_element name">Graftician</td><td class="submission_row_element email">Wander/Weave</td><td class="submission_row_element primary_phone">July 22nd, 2016</td><td class="submission_row_element submission_type">Experimental Electronic</td><td class="submission_row_element membership_year">July 22nd 2016</td><td><input class="staff_comment" id="comment1274" value=""></td><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td><td><input type="checkbox" class="delete_submission" id="delete_3"></td><div class="check hidden">❏</div></tr>
						<tr id="tagrow5" class="submission_row tag_row" name="1273"><td class="submission_row_element name">Koban</td><td class="submission_row_element email">Abject Obsessions</td><td class="submission_row_element primary_phone">September 15th 2016</td><td class="submission_row_element submission_type">Goth, Punk</td><td class="submission_row_element membership_year">September 20th 2016</td><td><input class="staff_comment" id="comment1273" value=""></td><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td><td><input type="checkbox" class="delete_submission" id="delete_4"></td><div class="check hidden">❏</div></tr>
						<tr id="tagrow6" class="submission_row tag_row" name="1272"><td class="submission_row_element name">Fuzzy P</td><td class="submission_row_element email">On A Lawn</td><td class="submission_row_element primary_phone">June 10th 2016</td><td class="submission_row_element submission_type">Indie Rock</td><td class="submission_row_element membership_year">June 26th 2016</td><td><input class="staff_comment" id="comment1272" value=""><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td></td><td><input type="checkbox" class="delete_submission" id="delete_5"></td><div class="check hidden">❏</div></tr>
					</tbody>
				</table>
			</div>
		</div>
		<div id="submissionspopup" class="hidden">
			<div style="display:block">
				<p id="submissionscloser"> X </p>
				<br />
				<h3> Tag Album </h3>
				<br />
				<div class="double-padded-top">
					<div id="titleBox">
						<b> Catalog #* </b>
					</div>
					<div id="editTitleBox">
						<input id="editTitle" placeholder="Title here" />
					</div>
				</div>
				<div class="double-padded-top">
					<div id="titleBox">
						<b> Format* </b>
					</div>
					<div id="editTitleBox">
						<script type="text/javascript">
							$(document).ready(function() {
							$(".js-example-basic-single").select2();
							});
						</script>
							<select class="js-example-basic-single vueselect" style="width:30%;">
								<option class='vueselect' value="CD">CD</option>
								<option class='vueselect' value="LP">LP</option>
								<option class='vueselect' value="7in">7"</option>
								<option class='vueselect' value="CASS">CASSETE</option>
								<option class='vueselect' value ="CART">CART</option>
								<option class='vueselect' value="MP3">MP3</option>
								<option class='vueselect' value="MD">MD</option>
								<option value="??">Unknown</option>
							</select>
					</div>
				</div>
				<div class="double-padded-top">
					<div id="titleBox">
						<b> Album Title* </b>
					</div>
					<div id="editTitleBox">
						<input id="editTitle" placeholder="Title here" />
					</div>
				</div>
				<div class="double-padded-top">
					<div id="titleBox">
						<b> Artist* </b>
					</div>
					<div id="editTitleBox">
						<input id="editTitle" placeholder="Title here" />
					</div>
				</div>
				<div class="double-padded-top">
					<div id="titleBox">
						<b> Album Credit </b>
					</div>
					<div id="editTitleBox">
						<input id="editTitle" placeholder="Title here" />
					</div>
				</div>
				<div class="double-padded-top">
					<div id="titleBox">
						<b> Label </b>
					</div>
					<div id="editTitleBox">
						<input id="editTitle" placeholder="Title here" />
					</div>
				</div>
				<div class="double-padded-top">
					<div id="titleBox">
						<b> Genre* </b>
					</div>
					<div id="editTitleBox">
						<script type="text/javascript">
							$(document).ready(function() {
			  				$(".js-example-basic-single").select2();
							});
						</script>
							<select class="js-example-basic-single vueselect" style="width:70%;">
								<?php foreach($djland_primary_genres as $genre){
									printf("<option value=\"$genre\">$genre</option>");
								} ?>
							</select>
					</div>
				</div>
				<div class="double-padded-top">
					<div id="titleBox">
						<b> Subgenre </b>
					</div>
					<div id="editTitleBox">
						<script type="text/javascript">
							$(document).ready(function() {
			  				$(".js-example-basic-single").select2();
							});
						</script>
							<select class="js-example-basic-single vueselect" style="width:70%;">
								<option value"none">No Subgenre</option>
								<?php foreach($djland_subgenres as $genre => $subgenre_array){
									printf("<optgroup label=\"$genre\">");
									if(is_array($subgenre_array)){
										foreach($subgenre_array as $subgenre){
											printf("<option value=\"$subgenre\">$subgenre</option>");
										}
									}
									printf("</optgroup>");
								} ?>
							</select>
					</div>
				</div>
				<div class="double-padded-top">
					<div class="col3">
						<div id="titleBox">
							<input type="checkbox" class="delete_submission" id="delete_0"></td><div class="check hidden">❏</div>
						</div>
						<div id="titleBox">
							Cancon
						</div>
					</div>
					<div class="col3">
						<div id="titleBox">
							<input type="checkbox" class="delete_submission" id="delete_0"></td><div class="check hidden">❏</div>
						</div>
						<div id="titleBox">
							Femcon
						</div>
					</div>
					<div class="col3">
						<div id="titleBox">
							<input type="checkbox" class="delete_submission" id="delete_0"></td><div class="check hidden">❏</div>
						</div>
						<div id="titleBox">
							Is Local
						</div>
					</div>
				</div>
				<div class="double-padded-top">
					<div class="col3">
						<div id="titleBox">
							<input type="checkbox" class="delete_submission" id="delete_0"></td><div class="check hidden">❏</div>
						</div>
						<div id="titleBox">
							Playlist
						</div>
					</div>
					<div class="col3">
						<div id="titleBox">
							<input type="checkbox" class="delete_submission" id="delete_0"></td><div class="check hidden">❏</div>
						</div>
						<div id="titleBox">
							Compilation
						</div>
					</div>
					<div class="col3">
						<div id="titleBox">
							<input type="checkbox" class="delete_submission" id="delete_0"></td><div class="check hidden">❏</div>
						</div>
						<div id="titleBox">
							In SAM
						</div>
					</div>
				</div>
				<br />
				<br />
				<div class='col2 text-right padded-right'>
					<button name='cancel'>Cancel</button>
				</div>
				<div class=' padded-left'>
					<button name='edit' class='member_submit red' disabled='true'>Form not Complete</button>
				</div>
			</div>
		</div>
		<?php endif; ?>


		<!-- Begin Tab 5 "submissions admin" -->
   		<?php if(permission_level() >= $djland_permission_levels['staff']['level']): ?>
		<div id="admin" class="hidden membership grey clearfix">
   			<div class="col1">
			<br>
   				<h4>Admin Panel</h4>
   			</div>
			<div class="col1">
				<h5>Search Past Submissions</h5>
			</div>
   			<div class="col2">
				Submission Date: <input id="adname" placeholder="Enter a song name" maxlength="15">
			</div>
			<div id="col2">
				<label for="from">Start Date: </label>
				<input type="text" id="from" name="from" value="2016/10/16" class="hasDatepicker">

				<label for="to">End Date: </label>
				<input type="text" id="to" name="to" value="2016/10/16" class="hasDatepicker">
				<br>
			</div>
			<div class="col2">
				Album: 	<input id="adname" placeholder="Enter a song name" maxlength="15">
			</div>
			<div id="col2">
				<button id="submitDates">View Past Submissions</button>
			</div>
			<div class="col1">
				Song: <input id="adname" placeholder="Enter a song name" maxlength="15">
			</div>
			<div class="col2">
				Artist: <input id="adname" placeholder="Enter a song name" maxlength="15">
			</div>
			<div class="col1"
			<br>
			<hr>
			<br>
			</div>
			<div id="col1">
				<h5>Generate New Digital Submissions Listings</h5>
			</div>
			<div id="col2">
				<label for="from">Start Date: </label>
				<input type="text" id="from" name="from" value="2016/10/16" class="hasDatepicker">

				<label for="to">End Date: </label>
				<input type="text" id="to" name="to" value="2016/10/16" class="hasDatepicker">
				<br>
			</div>
			<div id="col2">
				<button id="submitDates">Generate Listing</button>
			</div>

			<br>
			<br>
			<br>
			<br>

			</div>

   		</div>
   		<?php endif; ?>

		<!-- Manual Submission Tab -->
		<?php if(permission_level() >= $djland_permission_levels['volunteer']['level']):
		?>
		<div id='manual_submission' class='hidden membership grey clearfix'>
			<div style="padding:10px">
				<h3 class="page-title-default text-center">Manual Album Submission</h3>
				<div class="row">
					<div class="entry-content col-sm-8 col-sm-offset-2">
				<div class="entry-content-inner">
					<p>Please submit a minimum of four 320kbps MP3 files.</p>
					<p>0 files uploaded.</p>
					<div style="border-style:solid;border-width:1px;margin-bottom:20px">
						<p style="text-align:center"><em>File name:</em> </p>
						<p style="text-align:center">Track name:</p>
						<input type="text" style="width:90%; margin-left:5%">
						<p style="text-align:center">Composer(s):</p>
						<input type="text" style="width:90%; margin-left:5%">
						<p style="text-align:center">Performer:</p>
						<input type="text" style="width:90%; margin-left:5%; margin-bottom: 30px">
					</div>
					<div class='col1 text-center'>
						<p><button name='add_album_art'>Add another file</button></p>
					</div>
					<form>
						<div style="width:50%;float:left;">
							Artist: <input type="text" style="width:95%;margin-bottom:30px">
						</div>
						<div style="width:50%;float:right;">
							Contact email: <input type="text" style="width:100%;margin-bottom:30px;">
						</div>
						<div style="width:50%;float:left;">
							Label: <input type="text" style="width:95%;margin-bottom:30px">
						</div>
						<div style="width:50%;float:right;">
							Location: <input type="text" style="width:100%;margin-bottom:30px;">
						</div>
						<div style="width:50%;float:left;">
							Album credit: <input type="text" style="width:95%;margin-bottom:30px">
						</div>
						<div style="width:50%;float:right;">
							Album name: <input type="text" style="width:100%;margin-bottom:30px;">
						</div>
						<div class='col1 text-center'>
							<button name='add_album_art'>Add Album Art</button>
						</div>
						<div class='col1 text-center'>
							<br />
							<img src= "../images/citr-placeholder-square.svg"></img>
							<br />
								<br />
						</div>
						<div class='col1 text-center'>
							<button name='edit' class='member_submit red' disabled='true'>Form Not Complete</button>
						</div>
					</form>
			</div>
		</div>
		<?php endif; ?>

	</body>
</html>
