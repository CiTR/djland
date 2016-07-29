<html>
    <head>
        <link rel='stylesheet' href='css/bootstrap.min.css'></script>
    </head>
    <body>
    	<table class='table'>
            <tr><th>Description</th><th>Query</th><th>Result</th></tr>

<?php
require_once('../headers/db_header.php');
$queries = array(
'Populate playsheets with data from podcasts' =>
	'UPDATE playsheets AS p INNER JOIN podcast_episodes AS pe ON p.id = pe.playsheet_id SET
		p.summary = concat(pe.subtitle," \n",p.summary," \n",pe.summary),
		p.title = pe.title;',
'Add temporary column end'=>
	'ALTER TABLE `playsheets`
	ADD COLUMN `end` DATETIME NULL DEFAULT NULL AFTER `start_time`;',
'Set end to proper time'=>
	'UPDATE playsheets AS p INNER JOIN show_times as s ON s.show_id = p.show_id
	set p.end = CASE
	    WHEN ( s.end_day = s.start_day) THEN CONCAT( SUBSTRING(p.start_time,1,11),p.end_time)
	    WHEN ( s.end_day > s.start_day) THEN CONCAT( SUBSTRING( DATE_ADD(p.start_time,INTERVAL 1 DAY),1,11) , p.end_time)
	  END;',
'Change end to end_time' =>
	'ALTER TABLE `playsheets`
	  CHANGE COLUMN `end` `end_time` DATETIME NULL DEFAULT NULL ,
	  CHANGE COLUMN `end_time` `end` TIME NULL DEFAULT NULL ;',
'removing host_id' => '
    ALTER TABLE `shows`
    DROP COLUMN `host_id`;',
);


foreach($queries as $description => $query){
    if($result =   mysqli_query($db,$query) ){
        echo '<tr><td>'.$description.'</td><td>'.$query.'</td><td>Complete</td></tr>';
    }else {
        echo '<tr class="danger"><td>'.$description.'</td><td>'.$query.'</td><td> Failed: '.mysqli_error($db).'</td></tr>';
    }
}
?>
        </table>
    </body>
</html>
