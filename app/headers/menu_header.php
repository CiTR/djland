<?php
//MENU HEADER
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/config.php');
require_once('security_header.php');

function admin_menu()
{
	global $djland_permission_levels;
	if (permission_level() >= $djland_permission_levels['administrator']['level']) : ?>
		<ul id="admin-nav" class="nav mini">
			<li class="nodrop"><a href="../admin.php">Membership Admin</a></li>
			<li class="nodrop"><a href="data_structures.php">Data Structures</a></li>
		</ul>
	<?php
	endif;
}

function print_menu()
{
	global $enabled, $djland_permission_levels;
	if (!is_paid()) : ?>
		<div class="container">
			<div class="row">
				<div class="alert alert-danger">Your membership is currently unpaid for the year. You may also need to renew your acount. <a href="/member_settings.php?renew=1">Renew it here.</a></div>
			</div>
		</div>
	<?php endif; ?>
	<ul id="nav">
		<?php
		$sv_id = (isset($_SESSION['sv_id'])) ? $_SESSION['sv_id'] : null;
		echo "<div id='member_id' class='hidden' value={$sv_id}>{$sv_id}</div>";
		echo "<div id='permission_level' class='hidden'>" . permission_level() . "</div>";
		if ((permission_level() >= $djland_permission_levels['volunteer_leader']['level']) && $enabled['membership']) :
		?>
			<li class=nodrop><a href="membership.php">Membership</a></li>
		<?php
		endif;
		if (permission_level() >= $djland_permission_levels['member']['level']) : ?>
			<li class=drop><a href="library.php">Library</a>
				<div class="dropdown small">
					<div class=small>
						<ul>
							<?php if (permission_level() >=  $djland_permission_levels['member']['level'] && $enabled['library']) : ?>
								<li><a href="library.php"> View Library</a></li>
							<?php endif; ?>
							<?php if (permission_level() >= $djland_permission_levels['volunteer']['level'] && $enabled['library']) : ?>
								<li><a href="library.php?action=add">Update Library</a></li>
							<?php endif; ?>
							<?php if (permission_level() >= $djland_permission_levels['volunteer']['level'] && $enabled['library']) : ?>
								<li><a href="editlibrary.php">Edit Library Entries</a></li>
							<?php endif; ?>
						</ul>
					</div>
				</div>
			</li>

		<?php
		endif;
		if (permission_level() >= $djland_permission_levels['workstudy']['level']) :
		?>
			<li class='drop'><a href='#'>Admin</a>
				<div class="dropdown small">
					<div class=small>
						<ul>
							<?php if (permission_level() >=  $djland_permission_levels['staff']['level']) : ?>
								<li><a href="setSocan.php"> Socan Periods </a></li>
								<li><a href="genremanager.php">Genre Manager</a></li>
							<?php endif; ?>
						</ul>
					</div>
				</div>
			</li>
		<?php
		endif;
		if ($enabled['charts'] && permission_level() >= $djland_permission_levels['workstudy']['level']) : ?>
			<li class=nodrop><a href="charting.php">Charts</a></li>
		<?php
		endif;
		if ($enabled['shows'] && permission_level() >= $djland_permission_levels['dj']['level']) :
		?>
			<li class=drop><a href="shows.php?action=list">Shows</a>
				<div class="dropdown small">
					<div class=small>
						<ul>
							<li><a href="shows.php?action=list&show_status=active">Active Shows</a></li>
							<li><a href="shows.php?action=list&show_status=inactive">Inactive Shows</a></li>
						</ul>
					</div>
				</div>
			</li>
		<?php
		endif;
		if (permission_level() >= $djland_permission_levels['dj']['level']) :
		?>
			<li class=nodrop><a href="crtc_report.php">Reporting</a></li>
		<?php
		endif;
		if ($enabled['playsheet'] && permission_level() >= $djland_permission_levels['dj']['level']) : ?>
			<li class=drop><a href="playsheet_angular.php">Episodes</a>
				<div class="dropdown small">
					<div class=small>
						<ul>
							<li><a href="playsheet_angular.php">New Playsheet</a></li>
							<li><a href="playsheet_angular.php?socan=true">New Socan Playsheet</a></li>
							<li><a href="open_playsheet.php">Open a Playsheet</a></li>
							<li><a href="podcasts.php"> Podcasts </a></li>
						</ul>
					</div>
				</div>
			</li>
		<?php endif; ?>

		<li class="menu_right nodrop"><a href="index.php?action=logout">Log Out</a></li>
		<li class="menu_right nodrop"><a href="member_settings.php">My Profile</a></li>
		<?php if (permission_level() >=  $djland_permission_levels['member']['level']) : ?>
			<li class='menu_right drop'><a href="member_resources.php">Member Resources</a>
				<div class="dropdown small">
					<div class="small">
						<ul>

							<li><a href="member_resources.php">Resources</a></li>
							<li><a href="training_videos.php">Training Videos</a></li>
							<?php
							//if( permission_level() >= $djland_permission_levels['workstudy'] || is_trained()):
							if (permission_level() >= $djland_permission_levels['member']['level'] && is_trained() || permission_level() >= $djland_permission_levels['workstudy']['level']) :
							?>
								<li><a href="studio_booking.php">Book a Studio</a></li>
								<li><a href="fillin_booking.php">Book a Fill In</a></li>
							<?php endif; ?>

							<?php if (permission_level() >= $djland_permission_levels['dj']['level']) : ?>
								<li><a href="help.php" target="_blank"> Help </a></li>
							<?php endif; ?>

						</ul>
					</div>
				</div>
			</li>
		<?php endif; ?>
	</ul>
	<br />
<?php

}
// useful when testing time-related things while faking time
//echo date('l jS \of F Y h:i:s A', get_time());
//echo " (".get_time().")";
