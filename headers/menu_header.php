<?php
//MENU HEADER

require_once('config.php');
require_once('headers/function_header.php');

function print_menu(){
	global $enabled;
?>
	
	<ul id=nav>
		
		<?php if(is_member("staff") && $enabled['membership']) : ?>
		<li class=nodrop><a href="membership.php">Membership</a></li>	
		<?php endif; ?>
		<li class=drop><a href="library.php">Library</a>
			<div class=dropdown_small>
				<div class=small>
					<ul>
						<?php if(is_member("member") && $enabled['library']) : ?>
						<li><a href="library.php"> View Library</a></li>
						<?php endif; ?>
						<?php if(is_member("workstudy") && $enabled['library']) : ?>
						<li><a href="library.php?action=add">Update Library</a></li>
						<?php endif; ?>
					</ul>
				</div>
			</div>
		</li>

	<?php if(is_member("staff")) :
		if($enabled['shows']) :?>
			<li class=drop><a href="shows.php?action=list">Shows</a>
				<div class="dropdown_small">
					<div class="small">
						<ul>
							<li><a href="shows.php?action=list">All Shows</a></li>
							<li><a href="shows.php">Active Shows</a></li>
							<li><a href="specialbroadcasts.php">Special Broadcasts</a></li>
						</ul>
					</div>
				</div>
			</li>
		<?php endif; 
		if($enabled['adscheduler']) : ?>
			<li class=drop><a href="adscheduler.php">Manage Ads</a>
				<div class=dropdown_small>
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
			<div class=dropdown_small>
				<div class=small>
					<ul>
						<?php if(is_member("dj")) : ?> 
							<li><a href="report.php">Show Report</a></li> 
						<?php endif;
						if(is_member("staff")) : ?>
							<li><a href="crtcreport.php">CRTC Report</a></li> 
						<?php endif; ?>
					</ul>
				</div>
			</div>
		</li>
		<?php endif; ?>
		<li class=drop><a href="playsheet.php">Playsheets</a>
			<div class=dropdown_small>
				<div class=small>
					<ul>
						<?php if(is_member("dj") && ($enabled['playsheets'])) : ?> 
							<li><a href="playsheet.php">New Playsheet</a></li>
							<li><a href="playsheet.php?socan=true">New Socan Playsheet</a></li>
							<li><a href="playsheet.php?action=list">Open a Playsheet</a></li>
						<?php endif; ?>
					</ul>
				</div>
			</div>
		</li>
		<li class="menu_right nodrop"><a href="index.php?action=logout">Log Out</a></li>
		<?php if(is_member("dj")) : ?>
			<li class="menu_right nodrop"><a href="help.php" target="_blank"> Help </a></li>
		<?php endif; ?>
		<li class="menu_right nodrop"><a href="member_settings.php">My Info</a></li>

		<?php if(is_member("dj") && $user_show = users_show()) : ?>
			<li class="menu_right nodrop"><a href="shows.php?action=edit&id=<?php echo $user_show;?>" > My Show </a></li>
			<li class="menu_right nodrop"><a href="podcasts.php?id=<?php echo $user_show;?>" > Podcasts </a></li>
		<?php endif; ?>


	</ul>

<?php } 

function membership_menu(){
require_once('config.php');
?>
<ul id ='tab-nav'>
	<li class = 'nodrop active-tab member_action' id='init' value='init'>Search Members</li>
	<li class = 'nodrop inactive-tab member_action' id='view' name='1' value='view'>View Member</li>
	<li class = 'nodrop inactive-tab member_action' id='report' value='report'>Report</li>
	<li class = 'nodrop inactive-tab member_action' id='mail' value='mail'>Send Emails</li>
</ul> 




<?php }

// useful when testing time-related things while faking time
//echo date('l jS \of F Y h:i:s A', get_time());
//echo " (".get_time().")";

?>
