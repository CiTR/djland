<?php


$PREFIX = '[new-'.date('yymmdd').'] ';
require_once('../headers/db_header.php');

if(isset($_GET['channel'])){
	$channel_id = $_GET['channel'];
} else {
	echo ' need channel id or "all"';
}
    echo "<a href='connect-shows-with-channels.php'>Connect shows to channels (or try)</a>";

if ($channel_id == 'all'){
$query = "SELECT * FROM podcast_channels ";
} else {
	$query = "SELECT * FROM podcast_channels WHERE id ='".$channel_id."'";
}
//execute the query.

$channels = array();
$episode_lists = array();

if ($result = mysqli_query($db, $query) ){
	
	while($row = mysqli_fetch_array($result)) {

		$channels []= $row;

		$query2 = "SELECT * FROM podcast_episodes WHERE channel_id = ".$row['id'];
		//execute the query.
		$episodes = array();
		if ($result2 = mysqli_query($db, $query2) ){

			while($row = mysqli_fetch_array($result2)) {
				$episodes []= $row;
			} 

		$episode_lists [] = $episodes;

		} else { echo 'db prob. query 2 is '.$query2;}

	} 

	foreach($channels as $i => $channel){

		make_podcast($channel,$episode_lists[$i]);
	}
}







function make_podcast($channel,$episodes){
	global $PREFIX;

	foreach($channel as $i => $v){
		$channel[$i] = htmlspecialchars(html_entity_decode($v));
	}

	$xml_head = '<?xml version="1.0"  ?>

	<rss xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" version="2.0" >';

	$xml = '';

	$xml .= $xml_head;
	$xml .= '<channel>';
	$xml .= '<title>'.$PREFIX.$channel['title'].'</title>';
	$xml .= '<description>'.$channel['summary'].'</description>';
	$xml .= '<itunes:summary>'.$channel['summary'].'</itunes:summary>';
	$xml .= '<itunes:author>'.$channel['author'].'</itunes:author>';
	$xml .= '<itunes:subtitle>'.$channel['subtitle'].'</itunes:subtitle>';
	$xml .= '<itunes:owner> '.
				'<itunes:name>'.$channel['owner_name'].'</itunes:name>'.
				'<itunes:email>'.$channel['owner_email'].'</itunes:email>'.
			'</itunes:owner>';
	$xml .= '<itunes:image href="'.$channel['image_url'].'"/>';

	$xml .= '<itunes:link rel="image" type="video/jpeg" href="'.$channel['image_url'].'">'.$channel['title'].'</itunes:link>';
	$xml .= '<image>'.
				'<link>'.$channel['link'].'</link>'.
				'<url>'.$channel['image_url'].'</url>'.
				'<title>'.$channel['title'].'</title>'.
			'</image>';
	$xml .= '<link>'.$channel['link'].'</link>';
	$xml .= '<generator> podcast mate 2000 </generator>';

	foreach($episodes as $i => $episode){

		foreach($episode as $in => $val){
			$episode[$in] = htmlspecialchars(html_entity_decode($val));
		}

		$xml .= 
		'<item>'.
		'<title>'.$episode['title'].'</title>'.
		'<pubDate>'.$episode['date'].'</pubDate>'.
		'<description>'.$episode['summary'].'</description>'.
		'<itunes:subtitle>'.$episode['subtitle'].'</itunes:subtitle>';
		$xml .=	($episode['duration'] > 0) ? '<itunes:duration>'.$episode['duration'].'</itunes:duration>':'';

		$xml .=
			'<enclosure url="'.$episode['url'].'" length="'.$episode['length'].'" type="audio/mpeg"/>'.
		'<guid isPermaLink="true">'.$episode['url'].'</guid></item>';


	}


	$xml.= '</channel></rss>';

//	$filename = urlencode(html_entity_decode(str_replace(' ','',$channel['title'])).'.xml';
	/*
	$remote_prefix = "http://playlist.citr.ca/podcasting/xml/";
	$local_channel_xml = substr($channel['xml'],strlen($remote_prefix));
	$file = fopen('podcast-media/xml/'.$local_channel_xml,'wb');
	*/

	$file = fopen('podcast-media/xml/'.$channel['xml'],'wb');

	if($file){
		echo fwrite($file, $xml).' bytes written.';
	} else {echo 'could not write file';}

}