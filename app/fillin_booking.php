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

</head>

<body class='wallpaper'>

	<?php print_menu(); ?>

	<br />
	<h1>Fill In Booking</h1>

	<div class="center">
		<div class="youCanBookMe_Container" style="height: 100%; overflow: unset; padding: 0;">
			<iframe src="https://citr-fillins-booking.youcanbook.me/" style="border:0px;background:transparent; max-height: 1200px" frameborder="0" allowtransparency="true"></iframe></p>
		</div>
		<div class="center" style="max-width:830px">
			<p><a onclick="javascript:pageTracker._trackPageview('/outgoing/calendar.google.com/calendar/embed?src=citr.ca_dveklvbt1acufiltqe22a101ro%40group.calendar.google.com&amp;ctz=America%2FVancouver');" style="border: 0px; background: transparent;" title="See the Studio B bookings here" href="https://calendar.google.com/calendar/embed?src=citr.ca_dveklvbt1acufiltqe22a101ro%40group.calendar.google.com&amp;ctz=America%2FVancouver" target="_blank">See the Fill In bookings here</a></p>
		</div>
		<div class="center" style="max-width:830px">
			</br>
			<p>If you would like to book a time that is taken contact the person in the link above or CiTR staff to reschedule.</p>
			<p><em>If any problems occur with your fill in booking, please email <a onclick="javascript:pageTracker._trackPageview('/mailto/promotions.executive@citr.ca mailto:promotions.executive@citr.ca');" href="mailto:promotions.executive@citr.ca mailto:promotions.executive@citr.ca">Programming Executive</a>.</em></p>
		</div>
	</div>
</body>

</html>