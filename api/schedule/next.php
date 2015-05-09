<?php
/**
 * Created by PhpStorm.
 * User: brad
 * Date: 5/6/15
 * Time: 5:55 PM
 */


require_once('../api_common.php');


if ( isset($_GET['ID'])){
//  fetch id
  $id = $_GET['ID'];

} else {
  $error .= " please supply show id ( ?ID=##) ";
  $blame_request = true;

  //error
}

if (!is_numeric($id)){
  $error .= ' ID parameter should not be a string ';
  $blame_request = true;
}

if($error != '') finish();


