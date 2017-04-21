<?php
    require_once("headers/security_header.php");
    require_once("headers/menu_header.php");

    if (permission_level() < $djland_permission_levels['staff']['level']) {
        header("Location: main.php");
    }
?>
<html>
	<head>
		<meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">
		<meta charset="utf-8">
		<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" />
		<link rel=stylesheet href='css/style.css' type='text/css'>

		<title>DJLAND | Scan Incoming Library Files</title>

		<script type='text/javascript' src='js/jquery-1.11.3.min.js'></script>
		<script type='text/javascript' src='js/jquery-ui-1.11.3.min.js'></script>
		<script type='text/javascript' src='js/constants.js'/></script>


        <script type="text/javascript" src="js/test.js"></script>

	</head>
	<body class='wallpaper'>
		<?php
        print_menu();

        if(isset($_GET['scan']) && $_GET['scan'] == "true"){ ?>
            // Display results
        <?php } else { ?>
        <h2>
            <a href="djland_scan.php?scan=true">Click here to import new Library items into DJLand</a>
        </h2>

        <pre>


             .--.             .---.
            /:.  '.         .' ..  '._.---.
           /:::-.  \.-"""-;` .-:::.     .::\
          /::'|   \/  _ _  \'   `\:'   ::::|
      __.'    |   /  (o|o)  \     `'.   ':/
     /    .:. /   |   ___   |        '---'
    |    ::::'   /:  (._.) .:\
    \    .='    |:'        :::|
     `""`       \     .-.   ':/
           arf!  '---`|I|`---'
          citr!       '-'
        </pre>
        <?php } ?>
    </body>
</html>
