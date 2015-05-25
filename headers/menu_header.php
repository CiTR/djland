<?php
//MENU HEADER

require_once('config.php');
require_once('headers/function_header.php');
require_once('headers/security_header.php');

function admin_menu()
{
    global $djland_permission_levels;
    if (permission_level() >= $djland_permission_levels['administrator']) : ?>
        <ul id="nav-admin" class="nav mini">
            <li class="nodrop"><a href="../admin.php">Membership Admin</a></li>
            <li class="nodrop"><a href="data_structures">Data Structures</a></li>
        </ul>
    <?php
    endif;
}

function print_menu(){
	global $enabled,$djland_permission_levels;
    admin_menu();
?>
	
	<ul id=nav>
		
		<?php 
		echo "<div id='member_id' class='hidden' value={$_SESSION['sv_id']}>{$_SESSION['sv_id']}</div>";
		if((permission_level() >= $djland_permission_levels['volunteer']) && $enabled['membership']): ?>
		<li class=nodrop><a href="membership.php">Membership</a></li>	
		<?php endif; ?>
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

	<?php if(permission_level() >= $djland_permission_levels['workstudy']) : 
		if($enabled['shows']) :?>
			<li class=nodrop><a href="shows.php?action=list">Shows</a></li>
		<?php endif; 
		if($enabled['adscheduler']) : ?>
			<li class=drop><a href="adscheduler.php">Manage Ads</a>
				<div class="dropdown small">
					<div class=small>
						<ul>
							<li><a href="adscheduler.php">Ad Scheduler</a></li>
							<li><a href="adreport.php">Ad Reporting</a></li>
							<li><a href="samAds.php">Sam Ad History</a></li>
						</ul>
					</div>
				</div>
			</li>
		<?php endif; 
		if($enabled['charts']) :?>
			<li class=nodrop><a href="charting.php">Charts</a></li>
		<?php endif;
	endif; 
	if($enabled['report']): ?>
		<li class=drop ><a href="report.php">Reports</a>
			<div class="dropdown small">
				<div class=small>
					<ul>
						<?php if(permission_level() >= $djland_permission_levels['dj']) : ?> 
							<li><a href="report.php">Show Report</a></li> 
						<?php endif;
						if(permission_level() >= $djland_permission_levels['workstudy']) : ?>
							<li><a href="crtcreport.php">CRTC Report</a></li> 
						<?php endif; ?>
					</ul>
				</div>
			</div>
		</li>
		<?php endif; ?>
		<li class=drop><a href="playsheet.php">Playsheets</a>
			<div class="dropdown small">
				<div class=small>
					<ul>
						<?php if(permission_level() >= $djland_permission_levels['dj'] && ($enabled['playsheets'])) : ?> 
							<li><a href="playsheet.php">New Playsheet</a></li>
							<li><a href="playsheet.php?socan=true">New Socan Playsheet</a></li>
							<li><a href="playsheet.php?action=list">Open a Playsheet</a></li>
						<?php endif; ?>
					</ul>
				</div>
			</div>
		</li>
		<li class="menu_right nodrop"><a href="index.php?action=logout">Log Out</a></li>
		<?php if(permission_level() >= $djland_permission_levels['dj']) : ?>
			<li class="menu_right nodrop"><a href="help.php" target="_blank"> Help </a></li>
		<?php endif; ?>
		<li class="menu_right nodrop"><a href="member_settings.php">My Info</a></li>


	</ul>

<?php } 


// useful when testing time-related things while faking time
//echo date('l jS \of F Y h:i:s A', get_time());
//echo " (".get_time().")";

?>
