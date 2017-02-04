<?php
//if(!file_exists(dirname($_SERVER['DOCUMENT_ROOT']).'/config.php')) return;
require_once(dirname($_SERVER['DOCUMENT_ROOT'])."/config.php.sample");
$file = fopen(dirname($_SERVER['DOCUMENT_ROOT']).'/config.php','w');

$out[] = "<?php";
$out[] = "//Set to false in production environment";
$out[] = "\$testing_environment = true;";
$out[] = "\n";
$out[] = "//Radio Station Info";
write_post_to_array('station_info',$out);
$out[] ="\n";

$out[] = "//Enable or disable features of DJLand";
write_post_to_array('enabled',$out);
$out[] = "\n";

$out[] = "//Database Connections";
write_post_to_array('db',$out);
if(sizeof($_POST['sam_db']) >0){
	$out[] = "\n";
	write_post_to_array('sam_db',$out);
}
$out[] = "\n";

$out[] = "//Podcasting Tools";
if( $_POST['enabled']['podcasting'] ){
	write_post_to_array('path',$out);
	$out[] = "\n";
	write_post_to_array('url',$out);
}

$out[] = "//Month at which the membership year rolls into the next";
$out[] = "\$djland_membership_cutoff_month =".$_POST['membership_cutoff_month'].";";
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
	$assoc = array_keys($array) !== range(0,count($array)-1);
	$out[] = "\$".$name." = array(";
	foreach($array as $key=>$item){
		if(!is_array($item)) $out[] = ($key && $assoc ? "\"".$key."\" => ":"").($item =='true'||$item=='false' ? '':"\"").$item.($item =='true'||$item=='false' ? '':"\"").",";
		else{
			$out[] = format_inner_array($item,$key);
		}
	}
	$out[] = ");";
}
function format_inner_array($array,$name){
	$assoc = array_keys($array) !== range(0,count($array)-1);
	$temp = array();
	$temp[] = "'".$name."' => array(";
	foreach($array as $key=>$item){
		if(!is_array($item)){
			$temp[] = ($key  && $assoc ? "\"".$key."\" => \"":"\"").$item."\",";
		}
		else{
			$temp[] = format_inner_array($item,($key?$key:''));
		}
	}
	$temp[] = "),";
	return join("",$temp);
}
?>
<h1>Config Written!</h1>
Please make sure your config is setup properly before heading to the next step.
<form action='index.php' method='post'>
	<input style='display:none' name='next_form' value='setup_database'/>
	<button name='submit'>Create Database</button>
</form>
