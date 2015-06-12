<?php ///	 playsheet.php - playlist.citr.ca

session_start();
require_once("headers/showlib.php");
require_once("headers/security_header.php");
require_once("headers/function_header.php");
require_once("headers/menu_header.php");
require_once("headers/socan_header.php");
$SOCAN_FLAG;
$showlib = new Showlib($db);

echo "<center>";
print_menu();
echo "</center>";
$SOCAN_FLAG = socanCheck($db);

if (socanCheck($db) || $_GET['socan'] == 'true') {

  $SOCAN_FLAG = true;
  print ('<input type="hidden" id="socancheck" value="1">');
} else {
  $SOCAN_FLAG = false;
  print ('<input type="hidden" id="socancheck" value="0">');
}

$newPlaysheet = false;
if (!isset($_POST['id'])) $newPlaysheet = true;
if (isset($_POST['id']) && $_POST['id'] == 0)
  $newPlaysheet = true;

$actionSet = isset($_GET['action']);
$action = $_GET['action'];


if (isset($_POST['numberOfRows'])) {
  $playlist_entries = $_POST["numberOfRows"];
} else $playlist_entries = 5;

?>
<script type="text/javascript">
  var socan =<?php echo json_encode($SOCAN_FLAG); ?>;
</script>

<html>
  <head>
    <meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">
    <meta charset="utf-8">
    <link rel=stylesheet href='css/style.css' type='text/css'>

    <title>DJLAND | Playsheet</title>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="js/jquery.form.js"></script>

    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css"/>
    <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>

    <script>
      $(function () {
        $(".datepicker").datepicker({dateFormat: "yy-mm-dd"});
      });
    </script>

  </head>

  <body>
    <?php

    //
    //          Task:   Saving a playsheet - whether it's a new one or editing an existing one
    //

    if ((is_member("dj") || (is_member("editdj") && $newPlaysheet)) && $actionSet && $action == "submit") {
      require_once('playsheet_save.php');
    }

    //        Task:      List playsheets

    else if ($actionSet && $action == 'list' && !isset($_GET['delete'])) {

      require_once('playsheet_list.php');

    } else if ((isset($_GET['delete']) && ($_GET['delete'] == 'delete')) && (is_member("addshow"))){

      require_once('playsheet_delete.php');
    }

    //            Task:      Edit/New Playsheet

    else if (is_member("dj")){
      require_once('playsheet_edit_new.php');
      }
      ?>

      <div id='autosave'></div>

      <?php

      if ($_GET['action'] != 'list') {
        require_once('playsheet-ajax.php');
      }
      ?>

    <script type="text/javascript" src="./js/playsheet-functions.js"></script>
    <script type="text/javascript" src="./js/playsheet-setup.js"></script>

    <script type="text/javascript">
      var enabled = {};
      enabled = <?php echo json_encode($enabled);?>;
    </script>

  </body>

</html>

