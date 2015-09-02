<?php
/**
 * Created by PhpStorm.
 * User: brad
 * Date: 3/5/15
 * Time: 8:10 PM
 */

require_once('../api_common.php');

$offset = isset($_GET['OFFSET']) && is_numeric($_GET['OFFSET']) ? $_GET['OFFSET'] * 1 : 0;
$limit  = isset($_GET['LIMIT'])  && is_numeric($_GET['LIMIT'])  ? $_GET['LIMIT']  * 1 : 100;
$query =   "SELECT 	shows.id as id,
					GREATEST(shows.edit_date,COALESCE(podcast_channels.edit_date,'0000-00-00 00:00:00')) as edit_date
			FROM shows
			LEFT JOIN podcast_channels on podcast_channels.id = shows.podcast_channel_id
			ORDER BY GREATEST(shows.edit_date,COALESCE(podcast_channels.edit_date,'0000-00-00 00:00:00')) DESC
			LIMIT $limit
			OFFSET $offset";
$data = array();
if ($result = mysqli_query($db, $query) ) {
  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
  }
}
finish();