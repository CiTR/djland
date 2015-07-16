<?php
	require_once("security_header.php");
	$constants = new StdClass();
	$constants->permission_levels = $djland_permission_levels;
	$constants->training = $djland_training;
	$constants->interests = $djland_interests;
	$constants->member_types = $djland_member_types;
	$constants->faculties = $djland_faculties;
	$constants->program_years = $djland_program_years;
	$constants->provinces = $djland_provinces;
	echo json_encode($constants);
?>