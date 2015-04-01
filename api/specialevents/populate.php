<?php
/**
 * Created by PhpStorm.
 * User: brad
 * Date: 3/11/15
 * Time: 2:22 PM
 */

require_once('../api_common.php');

$q = 'delete from special_events;';
mysqli_query($db,$q);

$times = array();

for ($i = 0; $i < 20; $i ++){
  $date = date('U',strtotime('June 1, 2015, 2:55pm'));

  $times []= array(
              'start'=>$date + $i*24*60*(60+$i),
              'end' => $date + $i*24*60*60 + 60*(61+($i*5)) ,
              'name' => 'sample special event',
              'show_id' => 50+ $i*4,
              'description' => 'description for sample special event',
              'image' => 'http://static.timetobreak.com/wp-content/uploads/2015/02/bill-murray4.jpg',
              'url' => 'http://citr.ca/event-post');
}

echo '<pre>';

foreach($times as $i => $row){

  $query = 'INSERT INTO special_events (start,end,show_id,name,description,image,url) VALUES ('.
      $row['start'].",".
      $row['end'].",".
      $row['show_id'].",'".
      $row['name']."','".
      $row['description']."','".
      $row['image']."','".
      $row['url']."')";

  if(mysqli_query($db,$query)) echo 'good';
  else echo mysqli_error($db).'<hr>'.$query;

}
print_r($times);