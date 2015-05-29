<?php
/**
 * Created by PhpStorm.
 * User: brad
 * Date: 5/28/15
 * Time: 8:43 AM
 */

require_once('../config.php');

date_default_timezone_set('America/Vancouver');

$time = date(DATE_RSS);

sleep(10);

$result = file_put_contents($xml_path_local.'delayed.txt', 'updated: '.$time);
echo 'finally ('.date(DATE_RSS).')';

