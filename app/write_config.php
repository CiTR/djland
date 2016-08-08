<?php
//if(!file_exists(dirname($_SERVER['DOCUMENT_ROOT']).'/config.php')) return;
require_once(dirname($_SERVER['DOCUMENT_ROOT'])."/config.php.sample");
$file = fopen(dirname($_SERVER['DOCUMENT_ROOT']).'/config.php','w');

$out[] = "<?php";
$out[] = "\$enabled = array(); \$station_info = array();";
$out[] = "\n";
$out[] = "//Set to false in production environment";
$out[] = "\$tesing_environment = true;";
$out[] = "\n";
$out[] = "//Radio Station Info";
write_post_to_array('station_info',$out);
$out[] ="\n";

$out[] = "//Enable or disable features of DJLand";
write_post_to_array('enabled',$out);
$out[] = "\n";

$out[] = "//Database Connections";
write_post_to_array('db',$out);
if(sizeof($_POST['db_sam']) >0){
	$out[] = "\n";
	write_post_to_array('db_sam',$out);
}
$out[] = "\n";

$out[] = "//Podcasting Tools";
if( $_POST['enabled']['podcasting'] ){
	write_post_to_array('path',$out);
	$out[] = "\n";
	write_post_to_array('url',$out);
}
$out[] = "//Month at which the membership year rolls into the next";
$out[] = "\$djland_membership_cutoff_month =".$_POST['membership_cutoff_month'];
$out[] = "\n";

write_to_array($djland_permission_levels,'djland_permission_levels',$out);
write_to_array($djland_member_types,'djland_member_types',$out);
write_to_array($djland_interests,'djland_interests',$out);
write_to_array($djland_training,'djland_training',$out);
write_to_array($djland_program_years,'djland_program_years',$out);
write_to_array($djland_faculties,'djland_faculties',$out);
write_to_array($djland_provinces,'djland_provinces',$out);
write_to_array($djland_primary_genres,'djland_primary_genres',$out);
write_to_array($djland_upload_categories,'djland_upload_categories',$out);


//Ending, Write the file
foreach($out as $line){
	fwrite($file,$line.($line != "\n" ? "\n" : ""));
}

function write_post_to_array($name,& $out){
	write_to_array($_POST[$name],$name,$out);
}
function write_to_array($array,$name,& $out){
	foreach($array as $key=>$item){
		if(is_array($item)) {
			$line[] = "\$".$name."['".$key."'] = array(";
			write_to_inner_array($item,$key,$line);
			$line[] = ");\n";
		}else $line[] = "'".$key."' => '".$item."',";
	}
	$out[] = join("",$line);
}
function write_to_inner_array($array,$name,&$out){
	$out[] = "'".$name."'=>array(";
	$inner_string = "";
	foreach($array as $key=>$item){
		if(is_array($item)) write_to_inner_array($array,$name,$inner_string);
		else $inner_string[] = "'".$item."',";
	}
	$out[] = join('',$inner_string);
	$out[] = "),\n";
}



require_once(dirname($_SERVER['DOCUMENT_ROOT'])."/setup/setup_database.php");
