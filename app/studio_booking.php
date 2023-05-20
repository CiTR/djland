<?php

include_once("headers/session_header.php");
require_once("headers/security_header.php");
#require_once("headers/functions.php");
require_once("headers/menu_header.php");

error_reporting(E_ALL);
?>

<html>
	<head>
		<meta name=ROBOTS content="NOINDEX, NOFOLLOW">
		<base href='shows.php'>
		<link rel="stylesheet" href="css/style.css" type="text/css">
		<script
  src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
  integrity="sha256-3edrmyuQ0w65f8gfBsqowzjJe2iM6n0nKciPUp8y+7E="
  crossorigin="anonymous"></script>
	</head>

	<body class='wallpaper'>

	<?php print_menu(); ?>

		<br/>
		<h1>Studio Booking</h1>

		<div class="center">
			<div class="center" style="max-width:830px">
				<p><i>*If you book studio time, you have a fifteen minute grace period to show up on time. If not, the studio will be claimed on a first-come, first-serve basis for interested members.</i><br/>

				<ul id="tab-nav">
					<li class="tab nodrop" data-target="#studio-booking-iframe" data-src="https://appointments.citr.ca/?select-service=2&select-provider=3">Studio B</li>
					<li class="tab nodrop" data-target="#studio-booking-iframe" data-src="https://appointments.citr.ca/?select-service=2&select-provider=4">Studio C</li>
				</ul>
			</div>
			
			<div class="youCanBookMe_Container">
				<iframe id="studio-booking-iframe" src="" style="border:0px;background:transparent;" frameborder="0" allowtransparency="true"></iframe>
			</div>
			<div class="center" style="max-width:830px">
				<p><a onclick="javascript:pageTracker._trackPageview('/outgoing/www.google.com/calendar/embed?src=citr.ca_alhprjo077j16o3tp2r468mucg%40group.calendar.google.com&amp;ctz=America/Vancouver');" style="border: 0px; background: transparent;" title="See the Studio B bookings here"  href="https://www.google.com/calendar/embed?src=citr.ca_alhprjo077j16o3tp2r468mucg%40group.calendar.google.com&amp;ctz=America/Vancouver" target="_blank">See the Studio B bookings here</a></p>			
			</div>
			<div class="center" style="max-width:830px">
				<p><a onclick="javascript:pageTracker._trackPageview('/outgoing/www.google.com/calendar/embed?src=citr.ca_enon4vvn43udfr5p8d6p4bb4pg%40group.calendar.google.com&#038;ctz=America/Vancouver');" style="border: 0px; background: transparent;" title="See the Studio B bookings here"  href="https://www.google.com/calendar/embed?src=citr.ca_enon4vvn43udfr5p8d6p4bb4pg%40group.calendar.google.com&#038;ctz=America/Vancouver" target="_blank">See the Studio C bookings here</a><br />
			</div>
			<div class="center" style="max-width:830px">
				</br>		
				<p>If you would like to book a time that is taken contact the person in the link above or CiTR staff to reschedule.</p>
				<p><em>If any problems occur with your booking, please email <a onclick="javascript:pageTracker._trackPageview('/mailto/technicalassistant@citr.ca mailto:technicalservices@citr.ca');"  href="mailto:technicalassistant@citr.ca mailto:technicalservices@citr.ca">tech services</a>.</em></p>
			</div>
		</div>
		<script>
			$(document).ready(function() {
				$('#tab-nav [data-target][data-src]').addClass('inactive-tab');
				
				$('#tab-nav [data-target][data-src]').click(function(e) {
					if ($(this).hasClass('inactive-tab')) {
						$(this).siblings('.active-tab').toggleClass('inactive-tab').toggleClass('active-tab');
						$($(this).data('target')).attr('src', $(this).data('src'));
						console.log({
							target: $(this).data('target'),
							src: $(this).data('src')
						});
						$(this).toggleClass('inactive-tab').toggleClass('active-tab');
					}
				});

				$('#tab-nav [data-target][data-src]').first().trigger('click');
			});
		</script>
	</body>
</html>
