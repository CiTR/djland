<?php
/**
 * Created by PhpStorm.
 * User: brad
 * Date: 3/5/15
 * Time: 2:52 PM
 */


require_once('../api_common.php');

$id = isset($_GET['ID']) && is_numeric($_GET['ID']) ? $_GET['ID'] * 1 : 0;

if (!$id) {
	$error = "[ERROR] please supply a numeric show id ( show?ID=##) ";
	$blame_request = true;
	finish();
	exit;
}


$query =   "SELECT
			shows.id as show_id,
			shows.name,
			shows.last_show,
			shows.create_date,
			GREATEST(shows.edit_date,COALESCE(podcast_channels.edit_date,'0000-00-00 00:00:00')) as edit_date,
			shows.active,
			shows.primary_genre_tags,
			shows.secondary_genre_tags,
			shows.website,
			shows.rss,
			shows.show_desc,
			shows.alerts,
			shows.show_img,
			hosts.name as host_name,
			podcast_channels.title as podcast_title,
			podcast_channels.subtitle as podcast_subtitle,
			podcast_channels.summary as podcast_summary,
			podcast_channels.keywords as podcast_keywords,
			podcast_channels.image_url as podcast_image_url,
			podcast_channels.xml as podcast_xml

			FROM shows

			LEFT JOIN hosts on hosts.id = shows.host_id
			LEFT JOIN podcast_channels on podcast_channels.id = shows.podcast_channel_id

			WHERE shows.id=$id";

$data = array();

if ($result = mysqli_query($db, $query) ) {
	$data = mysqli_fetch_assoc($result);

	$show_id = isset($data['show_id']) ? $data['show_id'] : 0;
	if ($show_id) {
		$query = "SELECT
					social_name,
					social_url,
					short_name
				  from social
				  where show_id = $show_id";
		$social = array();
		if ($result = mysqli_query($db, $query)) {
			while ($row = mysqli_fetch_assoc($result)) {
			    $social[] = array(
			    	'type'  =>  html_entity_decode($row['social_name'],ENT_QUOTES),
			        'url'   =>  html_entity_decode($row['social_url'],ENT_QUOTES),
			        'name'  =>  html_entity_decode($row['short_name'],ENT_QUOTES)
				);
			}
		}
		$data['social_links'] = $social;
	}
}

if (empty($data)) {
	//$error = ' no show with this id:'.$id;
    //$blame_request = true;
    $data = array(
    	'api_message' => '[NO RECORD FOUND]',
    	'message'     => 'no show with this id:'.$id,
    );
    finish();
    exit;
}

finish();
