<?php
//MENU HEADER
require_once(dirname(__DIR__).'/config.php');
//require_once(__DIR__.'\function_header.php');
require_once('security_header.php');

function admin_menu()
{
    global $djland_permission_levels;
    if (permission_level() >= $djland_permission_levels['administrator']) : ?>
        <ul id="admin-nav" class="nav mini">
            <li class="nodrop"><a href="../admin.php">Membership Admin</a></li>
            <li class="nodrop"><a href="data_structures.php">Data Structures</a></li>
        </ul>
    <?php
    endif;
}

function print_menu(){
	global $enabled,$djland_permission_levels,$using_sam;
?>
	<ul id=nav>
		<?php 
			echo "<div id='member_id' class='hidden' value={$_SESSION['sv_id']}>{$_SESSION['sv_id']}</div>";
			echo "<div id='permission_level' class='hidden'>".permission_level()."</div>";
			echo "<div id='using_sam' class='hidden'>".($using_sam ?'1':'0')."</div>";
			if( (permission_level() >= $djland_permission_levels['volunteer']) && $enabled['membership'] ): 
		?>
		<li class=nodrop><a href="membership.php">Membership</a></li>	
		<?php 
			endif; 
			if(permission_level() > $djland_permission_levels['member']): ?>
		<li class=drop><a href="library.php">Library</a>
			<div class="dropdown small">
				<div class=small>
					<ul>
						<?php if(permission_level() >=  $djland_permission_levels['member'] && $enabled['library']) : ?>
						<li><a href="library.php"> View Library</a></li>
						<?php endif; ?>
						<?php if( permission_level() >= $djland_permission_levels['volunteer'] && $enabled['library']) : ?>
						<li><a href="library.php?action=add">Update Library</a></li>
						<?php endif; ?>
					</ul>
				</div>
			</div>
		</li>
		
		<?php 
			endif;
			
				if($enabled['shows'] && permission_level() >= $djland_permission_levels['dj']) :?>
					<li class=nodrop><a href="shows.php?action=list">Shows</a></li>	
		<?php 
				endif; 
			if(permission_level() >= $djland_permission_levels['workstudy']) : 
				if($enabled['adscheduler']) : ?>
					<li class=drop><a href="adscheduler.php">Ads</a>
						<div class="dropdown small">
							<div class=small>
								<ul>
									<?php if(permission_level() >=  $djland_permission_levels['staff']) : ?>
									<li><a href="adscheduler.php">Ad Scheduler</a></li>
									<?php endif; ?>
									<li><a href="adreport.php">Ad Reporting</a></li>
									<li><a href="samAds.php">Sam Ad History</a></li>
								</ul>
							</div>
						</div>
					</li>
		<?php 
				endif; 
				if($enabled['charts']) :?>
					<li class=nodrop><a href="charting.php">Charts</a></li>
		<?php 
				endif;
			endif; 
			if((permission_level() > $djland_permission_levels['dj']) && $enabled['report']): ?>
				<li class=drop ><a href="report.php">Reports</a>
					<div class="dropdown small">
						<div class=small>
							<ul>
								<li><a href="report.php">Show Report</a></li>
		<?php
							if(permission_level() >= $djland_permission_levels['workstudy']) : ?>
								<li><a href="crtcreport.php">CRTC Report</a></li> 
		<?php 
							endif; ?>
							</ul>
						</div>
					</div>
				</li>
		<?php 
			endif;
			if((permission_level() >= $djland_permission_levels['dj']) && $enabled['playsheets']): ?>
				<li class=drop><a href="playsheet_angular.php">Episodes</a>
					<div class="dropdown small">
						<div class=small>
							<ul>
								<li><a href="playsheet_angular.php">New Playsheet</a></li>
								<!-- Temp Removed <li><a href="playsheet.php?socan=true">New Socan Playsheet</a></li> -->
								<li><a href="open_playsheet.php">Open a Playsheet</a></li>
								<li><a href="podcasts.php"> Podcasts </a></li>
								<?php if(permission_level() >=  $djland_permission_levels['administrator']) : ?>
									<li><a href="setSocan.php"> Socan Periods </a></li>
								<?php endif; ?>
							</ul>
						</div>
					</div>
				</li>
		<?php 
			endif;
		?>
	 	<li class="menu_right nodrop"><a href="index.php?action=logout">Log Out</a></li>
	 	<li class="menu_right nodrop"><a href="member_settings.php">My Profile</a></li>
		<?php if(permission_level() >=  $djland_permission_levels['member']) : ?>
		<li class='menu_right drop'><a href="member_resources.php">Member Resources</a>
			<div class="dropdown small">
				<div class=small>
					<ul>

						<li><a href="member_resources.php">Resources</a></li>				
						<?php if( permission_level() >= $djland_permission_levels['workstudy'] || is_trained()): ?>
						<li><a href="studio_booking.php">Book a Studio</a></li>
						<?php endif; ?>
						
						<?php if(permission_level() >= $djland_permission_levels['dj']) : ?>
						<li><a href="help.php" target="_blank"> Help </a></li>
						<?php endif; ?>

					</ul>
				</div>
			</div>
		</li>
		<?php endif; ?>
	</ul>
	<br/>
<?php } 
// useful when testing time-related things while faking time
//echo date('l jS \of F Y h:i:s A', get_time());
//echo " (".get_time().")";
?>

