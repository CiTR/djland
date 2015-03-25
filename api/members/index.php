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

  $query = 'SELECT * FROM user WHERE `status` = \'enabled\' ORDER BY edit_date DESC limit ' . $limit . ' OFFSET ' . $offset;

$rawdata = array();
if ($result = mysqli_query($db, $query) ) {

  while ($row = mysqli_fetch_assoc($result)) {

    $rawdata [] = $row;

  }
} else {
  $error .= mysqli_error($db);
}

foreach($rawdata as $i => $v){
  unset($rawdata[$i]['password']);
  unset($rawdata[$i]['login_fails']);
  unset($rawdata[$i]['edit_name']);
  unset($rawdata[$i]['status']);
  unset($rawdata[$i]['create_date']);
  $rawdata[$i]['id'] = $v['userid'];
}
$data = $rawdata;

finish();