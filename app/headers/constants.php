<?php
	include_once("session_header.php");
	require_once(dirname($_SERVER['DOCUMENT_ROOT']).'/config.php');
	//require_once("security_header.php");
	$constants = new StdClass();
	$constants->permission_levels = $djland_permission_levels;
	$constants->training = $djland_training;
	$constants->interests = $djland_interests;
	$constants->member_types = $djland_member_types;
	$constants->faculties = $djland_faculties;
	$constants->program_years = $djland_program_years;
	$constants->provinces = $djland_provinces;
	$constants->genres = $djland_primary_genres;
    $constants->subgenres = $djland_subgenres;
	$constants->primary_genres = $djland_primary_genres;
	$constants->cutoff_month = $djland_membership_cutoff_month;
	$constants->max_podcast_length = $djland_max_podcast_length;
	echo json_encode($constants);
?>
