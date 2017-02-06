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

		<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" />
		<link rel=stylesheet href='css/style.css' type='text/css'>
	<link rel="stylesheet" href="./station-js/trackform.css" />

		<title>DJLAND | Music Submissions</title>

		<script type='text/javascript' src='js/jquery-1.11.3.min.js'></script>
		<script type='text/javascript' src='js/jquery-ui-1.11.3.min.js'></script>
	    <script type='text/javascript' src='js/constants.js'/></script>

        <script type='text/javascript' src='js/membership/functions.js'></script>
		<script type="text/javascript" src="js/membership/admin.js"></script>
		<script type="text/javascript" src="js/test.js"></script>
		<script type="text/javascript" src="js/musicsubmissions/musicsubmissions.js"></script>
  	<script type = 'text/javascript' src='./station-js/online-submission.js'></script>
    <script type='text/javascript' src='./js/musicsubmissions/submission_post_request.js'></script>

		<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

		<!-- set the datepicker date format -->
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

        <div class='submissioncontainer' >
			<ul id ='tab-nav'>
				<?php if(permission_level() >= $djland_permission_levels['volunteer']['level']) : ?>
					<li class="tab nodrop active-tab submission_action" name="new_submissions">New Submissions</li>
					<li class="tab nodrop inactive-tab submission_action" name="reviewed_submissions">Reviewed Submissions</li></li>
					<li class="tab nodrop inactive-tab submission_action" name="tag">Tag Accepted Submissions</li></li>
				<?php endif;
				if(permission_level() >= $djland_permission_levels['staff']['level']) : ?>
				<li class="tab nodrop inactive-tab submission_action" name="approve">Approve</li>
				<li class="tab nodrop inactive-tab submission_action" name="admin">Submission Admin</li>
				<li class="tab nodrop inactive-tab submission_action" name="manual_submission">Manual Submission</li>
				<?php endif; ?>
			</ul>
			<?php if(permission_level() >= $djland_permission_levels['volunteer']['level']): ?>

			<!-- Begin Tab 1 "new submissions search" -->
			<div id="new_submissions" class="submission grey clearfix padded-right">
				<ul id="submission_header" name="search" class="clean-list inline-list">
					<li id="search">Search By:
						<select id="search_by">
							<option value="name">Submission Date</option>
							<option value="interest">Date of Release</option>
							<option value="member_type">Artist</option>
							<option value="album">Album</option>
							<option value="album">Genre</option>
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
							<option value="both">Complete or Incomplete</option>
							<option value="1">Only Complete</option>
							<option value="0">Only Incomplete</option>
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
				<div id="submission_result" class="left overflow_auto height_cap" name="search">
					CDs
					<table id="submission_table" name="search">
						<tbody name="newSubmissionCd">
							<tr id="music_row_heading border" style="display: table-row;">
								<th>Artist</th>
								<th>Album</th>
								<th>Date of Release</th>
								<th>Genre</th>
								<th>Date Submitted</th>
								<th>Staff Comments</th>
								<th>Assignee  </th>
								<th><button id="delete_button">Delete</button></th>
							</tr>
						</tbody>
					</table>
				</div>
				<div id="submission_result" class="left overflow_auto height_cap" name="search">
					MP3s
					<table id="submission_table" name="search">
						<tbody name="newSubmissionMP3">
							<tr id="music_row_heading border" style="display: table-row;">
								<th>Artist</th>
								<th>Album</th>
								<th>Date of Release</th>
								<th>Genre</th>
								<th>Date Submitted</th>
								<th>Staff Comments</th>
								<th>Assignee  </th>
								<th><button id="delete_button">Delete</button></th>
							</tr>
						</tbody>
					</table>
				</div>
				<div id="submission_result" class="left overflow_auto height_cap" name="search">
					Other
					<table id="submission_table" name="search">
						<tbody name="newSubmissionOther">
							<tr id="music_row_heading border" style="display: table-row;">
								<th>Artist</th>
								<th>Album</th>
								<th>Date of Release</th>
								<th>Genre</th>
								<th>Date Submitted</th>
								<th>Staff Comments</th>
								<th>Assignee  </th>
								<th><button id="delete_button">Delete</button></th>
							</tr>
						</tbody>
					</table>
				</div>
				<!-- Begin Submission view" -->
				<div id='view_submissions' class='hidden submission grey clearfix'>
					<div class="col1">
						<br>
						<h3>Review Submission</h3>
						<br>
						<div class="right">
							<button id="view_submissions_closer">X</button>
						</div>
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
							<div class="col3"><h3>Listen: </h3></div>
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
			</div>

			<!-- Begin Tab 2 "reviewed submissions view" -->
			<div id="reviewed_submissions" class='hidden submission grey clearfix padded-right'>
				<ul id="submission_header" name="search" class="clean-list inline-list">
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
								<option value="both">Complete or Incomplete</option>
								<option value="1">Only Complete</option>
								<option value="0">Only Incomplete</option>
							</select>
						</li>
						<li>
							<select class="year_select" name="search">
								<option value="2016/2017">2016/2017</option>
								<option value="2015/2016">2015/2016</option>
								<option value="2014/2015">2014/2015</option>
								<option value="2013/2014">2013/2014</option>
								<option value="2011/2012">2011/2012</option>
								<option value="all">All Years</option>
							</select>
						</li>
						<li>
							Order By
							<select id="order_by">
								<option value="created">Submission Date</option>
								<option value="id">Date of Release</option>
								<option value="lastname">Artist</option>
								<option value="firstname">Album</option>
								<option value="member_type">Genre</option>
								<option value="approval">Approval</option>
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

				<div id="submission_result" class="right overflow_auto height_cap" name="search">
					CDs
					<table id="submission_table" name="search">
						<tbody name="reviewedSubmissionCd">
							<tr id="music_row_heading border" style="display: table-row;">
								<th>Artist</th>
								<th>Album</th>
								<th>Date of Submission</th>
								<th>Staff Comments</th>
								<th>Reviewed By</th>
								<th>Approved?</th>
								<th>Approve</th>
								<th>Discard</th>
							</tr>
							<tr>
								<td></td><td></td><td></td><td></td><td></td><td></td><td><button>Apply Approvals</button></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div id="submission_result" class="right overflow_auto height_cap" name="search">
					MP3s
					<table id="submission_table" name="search">
						<tbody name="reviewedSubmissionMP3">
							<tr id="music_row_heading border" style="display: table-row;">
								<th>Artist</th>
								<th>Album</th>
								<th>Date of Submission</th>
								<th>Staff Comments</th>
								<th>Reviewed By</th>
								<th>Approved?</th>
								<th>Approve</th>
								<th>Discard</th>
							</tr>
							<tr>
								<td></td><td></td><td></td><td></td><td></td><td></td><td><button>Apply Approvals</button></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div id="submission_result" class="right overflow_auto height_cap" name="search">
					Other
					<table id="submission_table" name="search">
						<tbody name="reviewedSubmissionOther">
							<tr id="music_row_heading border" style="display: table-row;">
								<th>Artist</th>
								<th>Album</th>
								<th>Date of Submission</th>
								<th>Staff Comments</th>
								<th>Reviewed By</th>
								<th>Approved?</th>
								<th>Approve</th>
								<th>Discard</th>
							</tr>
							<tr>
								<td></td><td></td><td></td><td></td><td></td><td></td><td><button>Apply Approvals</button></td>
							</tr>
						</tbody>
					</table>
					<br />
					<hr />
					<br />
				</div>
				<!-- Begin Reviewed Submission view" -->
				<div id='reviewed_submissions_view' class='hidden submission grey clearfix'>
					<div class="col1">
						<br>
						<h3>View Review</h3>
						<br>
						<div class="right">
							<button id="reviewed_submissions_closer">X</button>
						</div>
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
							<div class="col3"><h3>Listen: </h3></div>
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
						<hr>

						<div class="containerrow padded">
							<div class="col4">What did you think about the album as a whole?:</div>
							<textarea readonly="true" id="comments" placeholder="What did you think about the album?" class="largeinput" rows="5"></textarea>
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
			</div>

			<!--Begin Tab 3 "Tagging Interface" -->
			<div id="tag" class="hidden submission grey clearfix padded-right">
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
							<option value="both">Complete or Incomplete</option>
							<option value="1">Only Complete</option>
							<option value="0">Only Incomplete</option>
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
				<div id="submisison_result" class="left overflow_auto height_cap padded side-padded" name="search">
					CDs
					<table id="submission_table" name="search">
						<tbody name="toTagSubmissionCd">
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
						</tbody>
					</table>
				</div>
				<div id="submisison_result" class="left overflow_auto height_cap side-padded" name="search">
					MP3s
					<table id="submission_table" name="search">
						<tbody name="toTagSubmissionMP3">
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
						</tbody>
					</table>
				</div>
				<div id="submisison_result" class="left overflow_auto height_cap padded side-padded" name="search">
					Other
					<table id="submission_table" name="search">
						<tbody name="toTagSubmissionOther">
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
						</tbody>
					</table>
				</div>
			</div>
			<div id="submissionspopup" class="hidden submission">
				<div style="display:block">
					<p id="submissionscloser"> X </p>
					<br />
					<h3> Tag Album </h3>
					<br />
					<div class="double-padded-top">
						<div id="titleBox">
							<b> Catalog # </b>
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
						<button name='tagcancel' id='tagcancel'>Cancel</button>
					</div>
					<div class='padded-left'>
						<button name='edit' class='submissions_submit red' disabled='true'>Submit Files for Approval</button>
					</div>
				</div>
			</div>
			<?php endif; ?>

			<!--- Begin Tab 4 "add to library" -->
			<?php if(permission_level() >= $djland_permission_levels['staff']['level']): ?>
			<div id="approve" class="hidden submission grey clearfix">
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
							<option value="both">Complete or Incomplete</option>
							<option value="1">Only Complete</option>
							<option value="0">Only Incomplete</option>
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
				<div id="submisison_result" class="left overflow_auto height_cap padded side-padded" name="search">
					CDs
					<table id="submission_table" name="search">
						<tbody name="taggedSubmissionCd">
							<tr id="headerrow" style="display: table-row;">
								<th>Artist</th>
								<th>Album</th>
								<th>Date of Release</th>
								<th>Genre</th>
								<th>Date Submitted</th>
								<th>Staff Comments</th>
								<th>Tagger</th>
								<th><button id="delete_button">Delete</button></th>
							</tr>
						</tbody>
					</table>
				</div>
				<div id="submisison_result" class="left overflow_auto height_cap padded side-padded" name="search">
					MP3s
					<table id="submission_table" name="search">
						<tbody name="taggedSubmissionMP3">
							<tr id="headerrow" style="display: table-row;">
								<th>Artist</th>
								<th>Album</th>
								<th>Date of Release</th>
								<th>Genre</th>
								<th>Date Submitted</th>
								<th>Staff Comments</th>
								<th>Tagger</th>
								<th><button id="delete_button">Delete</button></th>
							</tr>
						</tbody>
					</table>
				</div>
				<div id="submisison_result" class="left overflow_auto height_cap padded side-padded" name="search">
					Other
					<table id="submission_table" name="search">
						<tbody name="taggedSubmissionOther">
							<tr id="headerrow" style="display: table-row;">
								<th>Artist</th>
								<th>Album</th>
								<th>Date of Release</th>
								<th>Genre</th>
								<th>Date Submitted</th>
								<th>Staff Comments</th>
								<th>Tagger</th>
								<th><button id="delete_button">Delete</button></th>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div id="submissionsapprovalpopup" class="hidden submission">
				<div style="display:block">
					<p id="submissionsapprovalcloser"> X </p>
					<br />
					<h3> Approve Tags </h3>
					<br />
					<div class="double-padded-top">
						<div id="titleBox">
							<b> Catalog # </b>
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
						<button name='approvecancel' id='approvecancel'>Cancel</button>
					</div>
					<div class='padded-left'>
						<button name='edit' class='submissions_submit'>Add to Library</button>
					</div>
				</div>
			</div>

			<!-- Begin Tab 5 "submissions admin" -->
			<div id="admin" class="hidden submission grey clearfix">
				<div class="col1">
				<br>
					<h3>Admin Panel</h3>
				</div>
				<div class="padded-left">
					<div class="col1">
						<h5>Search Past Submissions</h5>
					</div>
					<div class="col1">
						Submission Date:
						<label>Start Date </label>
						<input type="text" class="datepicker" value="<?php $today ?>"/>
						<label>End Date </label>
						<input type="text" class="datepicker"  value="<?php $today ?>"/>
						<br>
					</div>
					<div class="col1">
						Album: 	<input id="adname" placeholder="Album" maxlength="15">
					</div>
					<div class="col1">
						Song: <input id="songname	" placeholder="Song" maxlength="15">
					</div>
					<div class="col1">
						Artist: <input id="artistname" placeholder="Artist" maxlength="15">
					</div>
					<div id="col1">
						<button id="submitDates">View Past Submissions</button>
					</div>
					<div class="col1">
					<br>
					<hr>
					<br>
					</div>
					<div class="col1">
						<h5>Generate New Digital Submissions Listings</h5>
					</div>
					<div id="col1">
						<label for="from">Start Date: </label>
						<input type="text" id="from" name="from" class="datepicker" value=<?php $today ?>>

						<label for="to">End Date: </label>
						<input type="text" id="to" name="to" class="datepicker" value=<?php $today ?>>
						<br>
					</div>
					<div id="col1">
						<button id="submitDates">Generate Listing</button>
					</div>

					<br>
					<br>
					<!-- Submission Rescue Tab -->
					<div class='col1'>
						<hr />
						<div style="padding:10px">
							<h3 class="page-title-default text-center">Deleted Submissions from the Past Month</h3>
						</div>
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
										<option value="both">Complete or Incomplete</option>
										<option value="1">Only Complete</option>
										<option value="0">Only Incomplete</option>
									</select>
								</li>
								<li>
									<select class="year_select" name="search">
										<option value="2016/2017">2016/2017</option>
										<option value="2015/2016">2015/2016</option>
										<option value="2014/2015">2014/2015</option>
										<option value="2013/2014">2013/2014</option>
										<option value="2011/2012">2011/2012</option>
										<option value="all">All Years</option>
									</select>
								</li>
								<li>
									Order By
									<select id="order_by">
										<option value="created">Submission Date</option>
										<option value="id">Date of Release</option>
										<option value="lastname">Artist</option>
										<option value="firstname">Album</option>
										<option value="member_type">Genre</option>
										<option value="approval">Approval</option>
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

						<div id="submission_result" class="right overflow_auto height_cap" name="search">
							<table id="submission_table" name="search">
								<tbody name="trashedSubmissions">
									<tr id="headerrow" style="display: table-row;">
										<th>Artist</th>
										<th>Album</th>
										<th>Date of Submission</th>
										<th>Staff Comments</th>
										<th>Reviewed By</th>
										<th>Approved?</th>
										<th>Approve /&nbsp</th>
										<th>Discard</th>
									</tr>
									<tr>
										<td></td><td></td><td></td><td></td><td></td><td></td><td><button>Apply Approvals</button></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>

			<!-- Begin Tav 6: Manual Submission Tab -->
			<?php if(permission_level() >= $djland_permission_levels['volunteer']['level']):
			?>
			<div id='manual_submission' class='hidden submission grey clearfix'>
				<div style="padding:10px">
					<div class="row">
						<div class="col-sm-12 page-header">
							<h1 class="page-title-default text-center">Manual Music Submission</h1>
						</div>
					</div>
					<div class="row">
						<div class="entry-content col-sm-8 col-sm-offset-2">
					<div class="entry-content-inner">

            <p>Items with a &#9733; are required.</p>
            <form>
              <div class="album-row">
              <div style="width:50%;float:left;">
                &#9733; Artist / Band name: <input id="artist-name" type="text" style="width:95%;margin-bottom:30px" placeholder="The Ultimate Supergroup">
              </div>
              <div style="width:50%;float:right;">
                &#9733; Contact email: <input type="text" id="contact-email" style="width:100%;margin-bottom:30px;" placeholder="ultimate@example.com">
              </div>
            </div>
            <div class="album-row">
              <div style="width:50%;float:left;">
                Record label: <input type="text" id="record-label" style="width:95%;margin-bottom:30px" placeholder="Stardust Records">
              </div>
              <div style="width:50%;float:right;">
                &#9733; Home city: <input type="text" id="home-city" style="width:100%;margin-bottom:30px;" placeholder="London, England">
              </div>
            </div>
            <div class="album-row">
              <div style="width:50%;float:left;">
                (For bands) Member names: <input type="text" id="member-names" style="width:95%;margin-bottom:30px" placeholder="David Bowie, Paul McCartney, Neil Peart">
              </div>
              <div style="width:50%;float:right;">
                &#9733; Album name: <input type="text" id="album-name" style="width:100%;margin-bottom:30px;" placeholder="Ziggy and Friends">
              </div>
            </div>
            <div class="album-row">
              <div style="width: 50%;float:left;">
                &#9733; Genre: <select name="Pick a genre" id="genre-picker" style="width:95%;margin-bottom:30px;">
                  <!-- TODO: populate this with present genres from DB -->
                  <option>Electronic</option>
                  <option>Experimental</option>
                  <option>Hip Hop / R&B / Soul</option>
                  <option>International</option>
                  <option>Jazz/Classical</option>
                  <option>Punk / Hardcore / Metal</option>
                  <option>Rock / Pop / Indie</option>
                  <option>Roots / Blues / Folk</option>
                  <option>Talk</option>
                </select>
              </div>
              <div style="width: 50%;float:right;">
                &#9733; Date released: <input type="text" id="date-released" style = "width:100%;margin-bottom:30px;" placeholder="June 3, 1993">
              </div>
            </div>
            <div class="album-row">
              <div class="fem-can-van">
                <label>
                  <input type="checkbox" id="female-artist" style="margin-right:20px" />
                  FemCon: Self-identifying female in 2 of the 4 MPWR categories
                  <span class="tooltip-target">?</span>
                  <span class="tooltip-box">
                    <p>
                      <strong>M</strong>usic composed by a self-identified female
                    </p>
                    <p>
                      <strong>P</strong>erformer of music or lyrics is self-identified female
                    </p>
                    <p>
                      <strong>W</strong>ords written by a self-identified female
                    </p>
                    <p>
                      <strong>R</strong>ecording done by or or produced by a self-identified female
                    </p>
                  </span>
                </label>
              </div>
              <div>
                <label>
                  <input type="checkbox" id="canada-artist" style="margin-right:20px;" />
                  CanCon: You fullfill at least 2 of the 4 MAPL categories
                  <span class="tooltip-target">?</span>
                  <span class="tooltip-box">
                    <p>
                      <strong>M</strong>usic composed by a Canadian
                    </p>
                    <p>
                      <strong>A</strong>rtist performing music or lyrics is Canadian
                    </p>
                    <p>
                      <strong>P</strong>erformance is recorded or live broadcast in Canada
                    </p>
                    <p>
                      <strong>L</strong>yrics written by a Canadian
                    </p>
                  </span>
                </label>
              </div>
              <div>
                <label>
                  <input type="checkbox" id="vancouver-artist" style="margin-right:20px" />
                  Local: You / your band is located in the Greater Vancouver Area
                  <!--
                  <span class="tooltip-target">?</span>
                  -->
                  <span class="tooltip-box">You / your band is located in the Greater Vancouver Area</span>
                </label>
              </div>
            </div>
            <br>Comments: <textarea rows="4" id="comments-box" style="width:100%;margin-bottom:20px;" placeholder="Please tell us about yourself, your album, or things to think about as we listen to your songs."></textarea>
            <!--
              <div class="button-container">
                <a id="album-art-button" class="btn btn-primary" style="width:100%;text-align:center"> Add album art (optional)</a>
              </div>
            -->
              <!--
            -->
            </form>

            <!--
            <button id="album-art-button" class="submission-button">
            Add Album Art (Optional)
            </button>
            -->

            <p>We accept .jpg or .png files of at least 500 by 500 pixels.</p>
            <input type="file" id="album-art-input-button" style="display:none" />
            <button id="album-art-button" class="submission-button">
            Add Album Art (Optional)
            </button>
            <output id="album-viewer"></output>

            <script>
            $('#album-art-button').click(function(){ $('#album-art-input-button').trigger('click');});
            </script>

            <!--
            <p>Note: We accept .jpeg or .png files of at least size 300 by 300 pixels.</p>
            -->
            <p>Please submit a minimum of four 320kbps MP3 files.</p>

            <div id="submit-field"></div>


            <input type="file" id="new-track-button-input" style="display:none" multiple/>
            <button id="new-track-button" class="submission-button">
              Add files
            </button>

            <script>
              $('#new-track-button').click(function(){ $('#new-track-button-input').trigger('click');});
            </script>

            <!--
            <div id="new-track-button" class="button-container">
              <a href="" class="btn btn-primary" style="width:100%;text-align:center"> Add another file </a>
            </div>
            -->
            <!--
            <div id="submit-button" class="button-container" style="color:green">
              <a href="" class="btn btn-primary" style="width:100%;text-align:center;margin-top:30px;background-color:green;border:green;margin-bottom:20px;"> Submit </a>
            </div>
            -->
            <div id="submit-button-div">
            <button id="submit-button" class="submission-button">
              SUBMIT
            </button>
            </div>


            <!--
						<form>
							<div class="album-row">
							<div style="width:50%;float:left;">
								Artist / Band name*: <input id="artist-name" type="text" style="width:95%;margin-bottom:30px" placeholder="The Ultimate Supergroup">
							</div>
							<div style="width:50%;float:right;">
								Contact email*: <input type="text" style="width:100%;margin-bottom:30px;" placeholder="ultimate@example.com">
							</div>
						</div>
						<div class="album-row">
							<div style="width:50%;float:left;">
								Record label: <input type="text" style="width:95%;margin-bottom:30px" placeholder="Stardust Records">
							</div>
							<div style="width:50%;float:right;">
								Home city: <input type="text" style="width:100%;margin-bottom:30px;" placeholder="London, England">
							</div>
						</div>
						<div class="album-row">
							<div style="width:50%;float:left;">
								(For bands) Member names: <input type="text" style="width:95%;margin-bottom:30px" placeholder="David Bowie, Paul McCartney, Neil Peart">
							</div>
							<div style="width:50%;float:right;">
								Album name*: <input type="text" style="width:100%;margin-bottom:30px;" placeholder="Ziggy and Friends">
							</div>
						</div>
						<div class="album-row double-padded-bottom">
							<div style="width: 50%;float:left;">
								Genre*: <script type="text/javascript">
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
							<div style="width: 50%;float:right;">
								Date released*: <input type="text" style = "width:100%;margin-bottom:30px;" placeholder="June 3, 1993">
							</div>
							<div class="album-row">
								<div style="width: 50%;float:left;">
									Format*	: <select class="js-example-basic-single vueselect" style="width:20%;">
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
						</div>
						<div class="album-row col1">
							<div class='col3'>
								<input type="checkbox" style="margin-right:20px;" />Canadian artist/band
							</div>
							<div class='col3'>
								<input type="checkbox" style="margin-right:20px"/>Vancouver, BC artist/band
							</div>
							<div class='col3'>
								<input type="checkbox" style="margin-right:20px"/>Female artist/band
							</div>
						</div>
						<div class='col1 padded'>
							<br>Comments: <textarea rows="4" style="width:100%;margin-bottom:20px;" placeholder="Please tell us about yourself."></textarea>
						</div>
					</form>

					<div class="col1 text-center">
						<p>Add album art (optional):</p>
						<input type="file" id="album-art-input-button" style="display:none" />
						<button id="album-art-button" class="submission-button">
							Add Album Art (Optional)
						</button>
						<output id="album-viewer"></output>

						<script>
							$('#album-art-button').click(function(){ $('#album-art-input-button').trigger('click');});
						</script>

							<p>Note: We accept .jpeg or .png files of at least size 300 by 300 pixels.</p>
							<p>Please submit a minimum of four 320kbps MP3 files.</p>

							<div id="submit-field"></div>

							<input type="file" id="new-track-button-input" style="display:none" multiple/>
							<button id="new-track-button" class="submission-button">
								Add files
							</button>

							<script>
								$('#new-track-button').click(function(){ $('#new-track-button-input').trigger('click');});
							</script>

					</div>

					<div class="containerrow double-padded-top">
						<div class="col1 text-center">
							<hr />
							<div class='padded'>
								<button name="edit" class="member_submit">Submit</button>
							</div>
						</div>
					</div>
					<div class="containerrow">
						<div class="col1 text-center">
							*indicates a required field
						</div>
					</div>
        -->

				</div>
			</div>
			<?php endif; ?>
		</div>
	</body>
</html>
