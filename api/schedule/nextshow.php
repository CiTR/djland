<?php
/**
 * Created by PhpStorm.
 * User: brad
 * Date: 5/6/15
 * Time: 5:55 PM
 */
// load the exact unix time of the next episode of the show specified in 'session'
// pass in unix time of a day to search within a day

require_once('../api_common_private.php');

if (isset($_GET['time'])){
  $time = $_GET['time'];
} else {
  $time = false;
}
if ($show_id = users_show()){



  $query = "SELECT * FROM show_times where show_id = ".$show_id;

  if ($result = mysqli_query($db,$query)){

    while ( $row = mysqli_fetch_assoc($result)){
      $rawdata []= $row;
    }

  } else {
    $error .= mysqli_error($db);
  }


$last_sun = strtotime('last sunday');

  $candidates = array();

foreach($rawdata as $i => $v){
  // find 'next one'


  // translate into unix time
  $current_week = Date('W', strtotime('tomorrow',strtotime($time)));
//  echo "current week: ".$current_week."\n\n";

   if ((int) $current_week % 2 == 0){

     $current_week_is_even = true;
   } else {
     $current_week_is_even = false;

   };

  $this_week = ( $v['alternating'] == '0' ) || ($current_week_is_even && $v['alternating'] == '2')
      || (!$current_week_is_even && $v['alternating'] == '1');

  if($this_week) {
    // every week
//    echo 'AIRS THIS WEEK (current week even? '.$current_week_is_even.")" ;

  } else {

//    echo 'AIRS NEXT WEEK (current week even? '.$current_week_is_even.")" ;

  }

    $sunday_before_request = strtotime('sunday  -1 week  ', strtotime('tomorrow',strtotime($time)));
//    echo "***  ".Date('F j, Y = h:i:s a',strtotime($time))."\n\n";
//    echo "your show starts at ".$v['start_time']." on day ".$v['start_day']." (alternates: ".$v['alternating'].")\n\n";

    $startday =  (int) $v['start_day'];

  if (!$this_week) $startday +=7;

    $showtime_if_it_was_on_last_sunday = strtotime($v['start_time'],  $sunday_before_request);

    $actual_show_time = strtotime('+'.$startday.' days',$showtime_if_it_was_on_last_sunday);

//    echo " (".$startday.") in current week, your show starts: ".Date('F j, Y = h:i:s a',$actual_show_time)."\n\n";

    $start_time = strtotime($v['start_time'], $sunday_before_request );

    if ($actual_show_time < strtotime($time)){
      if ( $v['alternating'] == '0') {
        $actual_show_time = strtotime('+ 1 week', $actual_show_time);
//        echo  "this is before now, so adding 1 week is ".Date('F j, Y = h:i:s a',$actual_show_time)."\n\n";
      } else {
        $actual_show_time = strtotime('+ 2 week', $actual_show_time);
//        echo  "this is before now, and the show alternates, so adding 2 weeks is ".Date('F j, Y = h:i:s a',$actual_show_time)."\n\n";
      }
    }


    $start = $sunday_before_request + $startday*24*60*60;


  $end = strtotime($v['end_time'], 'last sunday '.strtotime($time));
    $endday = (int) $v['end_day'];

    $end = ($endday)*24*60*60 + $end;

  $end = strtotime($v['end_time'], $actual_show_time);

  $candidates []= array('start' => $actual_show_time, 'end' => $end);

}

$min = $candidates[0];
foreach($candidates as $i => $v){
  if ($v['start'] < $min['start']){
    $min = $candidates[$i];
  }
}

  $data = $min;

  if ($error == ''){
    finish();
  }

} else {
  $error .= ' user must be logged in and have a show ';
  finish();
}

if($error != '') finish();



