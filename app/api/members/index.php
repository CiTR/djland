<?php
/**
 * Created by PhpStorm.
 * User: brad
 * Date: 3/23/15
 * Time: 4:48 PM
 */

require_once('../api_common.php');


if(isset($_GET['OFFSET'])) $offset = $_GET['OFFSET']; else $offset = 0;
if(isset($_GET['LIMIT'])) $limit = $_GET['LIMIT']; else $limit = 100;

  $query = 'SELECT id, lastname, firstname FROM membership  ORDER BY id DESC limit ' . $limit . ' OFFSET ' . $offset;

$rawdata = array();
if ($result = mysqli_query($db['link'], $query) ) {

  while ($row = mysqli_fetch_assoc($result)) {

    $rawdata [] = $row;

  }
} else {
  $error .= mysqli_error($db['link']);

}

foreach($rawdata as $i => $v){
}
$data = $rawdata;

finish();