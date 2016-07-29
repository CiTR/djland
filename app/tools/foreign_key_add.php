<html>
    <head>
        <link rel='stylesheet' href='../../../js/bootstrap/bootstrap.min.css'></script>
    </head>
    <body>
    	<table class='table'>
            <tr><th>Description</th><th>Query</th><th>Result</th></tr>     

<?php 
require_once('../headers/db_header.php');
$queries = array(
'Create Entries for playsheets and playitems that have been detached/deleted' => 
	'INSERT INTO `djland`.`shows` (`id`, `name`, `host_id`, `weekday`, `start_time`, `end_time`, `pl_req`, `cc_req`, `indy_req`, `fem_req`, `last_show`, `create_date`, `create_name`, `edit_date`, `edit_name`, `active`, `crtc_default`) VALUES ('1', 'Deleted Show', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
	INSERT INTO `djland`.`playsheets` (`id`, `show_id`, `host_id`, `edit_date`) VALUES ('1', '1', '2', '0');',
'Making sure "playsheet-> show" foreign key constraint will hold before adding' = >
	'UPDATE playsheets SET show_id =1 WHERE show_id = "";
	UPDATE playsheets SET show_id=1 WHERE show_id NOT IN (select id from shows);',
'Adding foreign key in playsheets to show' =>
	'ALTER TABLE `playsheets` 
	ADD INDEX `show_id_idx` (`show_id` ASC);
	ALTER TABLE `playsheets` 
	ADD CONSTRAINT `show_id`
		FOREIGN KEY (`show_id`)
		REFERENCES `shows` (`id`)
		ON DELETE CASCADE
		ON UPDATE CASCADE;',
'Changing datatypes to match foreign key references in member show' =>
	'ALTER TABLE `djland`.`member_show` 
		CHANGE COLUMN `member_id` `member_id` INT(11) UNSIGNED NOT NULL ,
		CHANGE COLUMN `show_id` `show_id` INT(10) UNSIGNED NOT NULL ;',
'Adding foreign key to member_show'=>
	'ALTER TABLE `djland`.`member_show` 
		ADD CONSTRAINT `member_link_id`
			FOREIGN KEY (`member_id`)
			REFERENCES `djland`.`membership` (`id`)
			ON DELETE CASCADE
			ON UPDATE CASCADE,
		ADD CONSTRAINT `show_link_id`
			FOREIGN KEY (`show_id`)
			REFERENCES `djland`.`shows` (`id`)
			ON DELETE CASCADE
			ON UPDATE CASCADE;',
'Making sure "playitem->playsheet" foreign key constraint will hold before adding' =>
	'UPDATE playitems SET playsheet_id = '1' WHERE !playsheet_id;
	UPDATE playitems SET playsheet_id = '1' WHERE playsheet_id not in (SELECT id FROM playsheets);',
'Adding foreign key to playitems -> playsheet' =>
	'ALTER TABLE `playitems` 
		CHANGE COLUMN `playsheet_id` `playsheet_id` BIGINT(20) UNSIGNED NOT NULL;
	ALTER TABLE `playitems` 
		ADD CONSTRAINT `playitem_playsheet_id`
			FOREIGN KEY (`playsheet_id`)
			REFERENCES `djland`.`playsheets` (`id`)
			ON DELETE CASCADE
			ON UPDATE CASCADE;',
'Adding foreign key membership_years -> membership'=>
	'ALTER TABLE `membership_years` 
	ADD CONSTRAINT `membership_years_member_id`
	  FOREIGN KEY (`member_id`)
	  REFERENCES `djland`.`membership` (`id`)
	  ON DELETE CASCADE
	  ON UPDATE CASCADE;',
'Adding foreign key to group_members' =>
	'ALTER TABLE group_members 
        ADD CONSTRAINT `user_id`
        FOREIGN KEY (`user_id`)
        REFERENCES user (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE;"',
'Add foreign key to group members' => 
	'ALTER TABLE group_members 
		ADD CONSTRAINT `user_id` 
		FOREIGN KEY (`user_id`) 
		REFERENCES user.(`id`) 
			ON DELETE CASCADE 
			ON UPDATE CASCADE;',

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