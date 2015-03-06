<?php
/**
 * Created by PhpStorm.
 * User: brad
 * Date: 3/5/15
 * Time: 8:10 PM
 */

require_once('../api_common.php');


if(isset($_GET['OFFSET'])) $offset = $_GET['OFFSET']; else $offset = 0;
if(isset($_GET['LIMIT'])) $limit = $_GET['LIMIT']; else $limit = 999999999;

$query = 'SELECT id, edit_date FROM shows ORDER BY edit_date DESC limit '.$limit.' OFFSET '.$offset;

$rawdata = array();

if ($result = mysqli_query($db, $query) ) {

  while ($row = mysqli_fetch_assoc($result)) {

    $rawdata [] = $row;

  }
}

$data = $rawdata;

finish();