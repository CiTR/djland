<?php ///	 playsheet.php - playlist.citr.ca

include_once("headers/session_header.php");
require_once("headers/showlib.php");
require_once("headers/security_header.php");
require_once("headers/function_header.php");
require_once("headers/menu_header.php");
require_once("headers/socan_header.php");

if(permission_level() >= $djland_permission_levels['dj']){
	$showlib = new Showlib($db);
	print_menu();

	//Check to see if it is currently a SOCAN period
	$SOCAN_FLAG = socanCheck($db);
	if ($SOCAN_FLAG || $_GET['socan'] == 'true') {
	  print ('<input type="hidden" id="socancheck" value="1">');
	} else {
	  print ('<input type="hidden" id="socancheck" value="0">');
	}


	if (isset($_POST['numberOfRows'])) {
	  $playlist_entries = $_POST["numberOfRows"];
	} else $playlist_entries = 5;

	?>

	<!-- Give Javascript Access to see if it is a SOCAN period or not -->
	<script type="text/javascript">
	  var socan =<?php echo json_encode($SOCAN_FLAG); ?>;
	</script>

	<html>	
	  	<head>
		    <meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">
		    <meta charset="utf-8">
		    <link rel=stylesheet href='css/style.css' type='text/css'>

		    <title>DJLAND | Playsheet</title>

		    <script src="js/jquery-1.11.3.min.js"></script>
		    <script src="js/jquery.form.js"></script>
		    <script src="js/jquery-ui-1.11.3.min.js"></script>
		    <script type="text/javascript" src="js/playsheet_functions.js"></script>
		    <script type="text/javascript" src="js/playsheet_setup.js"></script>
		    <script>
		      	$(function () {
		        	$(".datepicker").datepicker({dateFormat: "yy-mm-dd"});
		      	});
		      			      	var enabled = {};
		      	enabled = <?php echo json_encode($enabled);?>;
		    </script>

	 	</head>

	  	<body class='wallpaper'>
			<?php
				//Check to see if it is a new playsheet, or an existing one.
				$newPlaysheet = isset($_POST['id']) && $_POST['id'] != 0 ? false :true; 
				$action = $_GET['action'];
    			if(isset($_GET['action'])){
    				if( $action == "submit"){
    					//Save Playsheet
    					require_once('playsheet_save.php');
		        		require_once('playsheet-ajax.php');
    				}else if($action == "list"){
    					//Display Playsheet Listing with delete capabilities if permissions
    					if($_GET['delete']=='delete' && permission_level() >= $djland_permission_levels['workstudy']){
    						require_once('playsheet_delete.php');
    					}else{
    						require_once('playsheet_list.php');
    					}
    				}else{
    					//Make a new playsheet, or edit an existing one
    					require_once('playsheet_edit_new.php');
        				require_once('playsheet-ajax.php');
    				}
    			}
			?>
	      	<div id='autosave'></div>

	 	</body>
	</html>
<?php
	}else{
	header("Location: main.php");
	}
?>
