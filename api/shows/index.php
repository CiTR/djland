<?php
/**
 * Created by PhpStorm.
 * User: brad
 * Date: 3/5/15
 * Time: 8:10 PM
 */

require_once('../api_common.php');


if(isset($_GET['OFFSET'])) $offset = $_GET['OFFSET']; else $offset = 0;
if(isset($_GET['LIMIT'])) $limit = $_GET['LIMIT']; else $limit = 100;

$query = 'SELECT shows.id as id,
                shows.edit_date as edit_date,
                podcast_channels.edit_date as podcast_edit_date,
                podcast_channels.id as podcast_id
                FROM shows
                JOIN podcast_channels on podcast_channels.id = shows.podcast_channel_id ';//ORDER BY edit_date DESC limit '.$limit.' OFFSET '.$offset;


$rawdata = array();

if ($result = mysqli_query($db, $query) ) {

  while ($row = mysqli_fetch_assoc($result)) {

    $rawdata [] = $row;

  }
}


foreach($rawdata as $i => $row){
  $rawdata[$i]['edit_date'] = max($row['edit_date'], $row['podcast_edit_date']);
  unset($rawdata[$i]['podcast_edit_date']);
  unset($rawdata[$i]['podcast_id']);
}

foreach($rawdata as $i => $row){
  $edit[$i] = $row['edit_date'];
}

array_multisort($edit,SORT_DESC,$rawdata);


$data = array_slice($rawdata,$offset,$limit);

finish();