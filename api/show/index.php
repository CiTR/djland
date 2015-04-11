<?php
/**
 * Created by PhpStorm.
 * User: brad
 * Date: 3/5/15
 * Time: 2:52 PM
 */


require_once('../api_common.php');



$rawdata = array();


$query = 'SELECT '.
    "shows.id as show_id,
       shows.name,
       shows.last_show,
       shows.create_date,
       shows.edit_date,
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
        podcast_channels.xml as podcast_xml,
        podcast_channels.edit_date as podcast_edit_date,
       social.social_name,
       social.social_url,
       social.short_name,
       social.unlink  ".
    "FROM shows LEFT JOIN hosts on hosts.id = shows.host_id ".
    "JOIN social on show_id = shows.id
    LEFT JOIN podcast_channels on podcast_channels.id = shows.podcast_channel_id";



if ( isset($_GET['ID'])){
//  fetch id
  $id = $_GET['ID'];

  $query .=' WHERE shows.id = '.$id.'';

} else {
  $error .= " please supply show id ( show?ID=##) ";
  $blame_request = true;

  //error
}

if (!is_numeric($id)){
  $error .= ' ID parameter should not be a string ';
  $blame_request = true;
}

if($error != '') finish();

if ($result = mysqli_query($db, $query) ) {

  if (mysqli_num_rows($result) == 0) {

    // now try again without socials
    $query = 'SELECT '.
        "shows.id as show_id,
       shows.name,
       shows.last_show,
       shows.create_date,
       shows.edit_date,
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
        podcast_channels.xml as podcast_xml,
        podcast_channels.edit_date as podcast_edit_date ".

        "FROM shows LEFT JOIN hosts on hosts.id = shows.host_id
                    LEFT JOIN podcast_channels on podcast_channels.id = shows.podcast_channel_id";
    $query .=' WHERE shows.id = '.$id.'';

    if($result2 = mysqli_query($db, $query)){

      if (mysqli_num_rows($result2) == 0) {

        $error .= 'no show with this id:'.$id;
        $blame_request = true;
        finish();

      } else {

        while ($row = mysqli_fetch_assoc($result2)) {

          $rawdata [] = $row;

        }

      }

    }

  } else {

    while ($row = mysqli_fetch_assoc($result)) {

      $rawdata [] = $row;

    }

  }

} else {

  $error .= "\n database error. the problematic query is: ".$query." \n".mysqli_error($db);

}



$data = $rawdata[0];


if ($data['alerts'] == ''){
  $data['alerts'] = 'I am the show alert text for '.$data['name'].'! Check out an upcoming episode on unique dog breeds and the hottest dogetronica music!';
}

if ($data['primary_genre_tags'] == ''){
  $data['primary_genre_tags'] = 'doge talk, dogetronica';
}



$social_array = array();

foreach($rawdata as $i => $show){
  if (isset($show['social_name'])){
    $social_array []= array(
        'type'  =>  html_entity_decode($show['social_name'],ENT_QUOTES),
        'url'   =>  html_entity_decode($show['social_url'],ENT_QUOTES),
        'name'  =>  html_entity_decode($show['short_name'],ENT_QUOTES)
    );
  }
}


$data['social_links'] = $social_array;

$data['edit_date'] = max($data['edit_date'], $data['podcast_edit_date']);

unset($data['podcast_edit_date']);

unset($data['social_name']);
unset($data['social_url']);
unset($data['short_name']);
unset($data['unlink']);


finish();
