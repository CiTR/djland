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

        <link href="css/jquery.dataTables.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" />
		<link rel=stylesheet href='css/style.css' type='text/css'>

		<title>DJLAND | Scan Incoming Library Files</title>

		<script type='text/javascript' src='js/jquery-1.11.3.min.js'></script>
		<script type='text/javascript' src='js/jquery-ui-1.11.3.min.js'></script>
		<script type='text/javascript' src='js/constants.js'/></script>
        <script src="js/jquery.dataTables.min.js"></script>

        <script type='text/javascript' src='js/djland_scan//djland_scan.js'></script>

        <script type="text/javascript" src="js/test.js"></script>

	</head>
	<body class='wallpaper'>
		<?php
        print_menu();

        if(isset($_GET['scan']) && $_GET['scan'] == "true"){ ?>
        <div class='submissioncontainer' >
            <h2 class=double-padded-bottom> DJLand Scan Results</h2>
            <div class="submission grey clearfix side-padded padded">
                <table id=DJLandScanTable class="submission_table cell-border">
                    <thead>
                        <tr id="headerrow" style="display: table-row;">
                            <th>File Source</th>
                            <th>Artist</th>
                            <th>Album</th>
                            <th>Song</th>
                            <th>Genre</th>
                            <th>Year</th>
                            <th>Matched with</th>
                            <th>Action To Take</th>
                        </tr>
                    </thead>
                    <tbody id=DJLandScan>

                    </tbody>
                </table>
                <table id=DJLandScanResultsTable class="submission_table cell-border hidden">
                    <thead>
                        <tr id="headerrow" style="display: table-row;">
                            <th>File Source</th>
                            <th>Action</th>
                            <th>New ID</th>
                            <th>Destination</th>
                        </tr>
                    </thead>
                    <tbody id=DJLandScanResults>

                    </tbody>
                </table>
            </div>
            <div class='side-padded right'>
                <button id="submitScan">Apply Actions</button>
            </div>
        </div>

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
