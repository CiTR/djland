<?php
/**
 * Created by PhpStorm.
 * User: brad
 * Date: 3/19/15
 * Time: 2:07 PM
 */


$clean_qs = array(

    'delete playlists with no show id ' => 'DELETE FROM `playlists` WHERE show_id IS NULL;',
    'delete playlists with 0000:00 start time' => 'DELETE FROM `playlists` WHERE start_time = 0000-00-00;',
    // import playlists
    'delete playlists with no show id after import' => 'DELETE FROM `playlists` WHERE show_id IS NULL;',
    'delete playlists with 0000:00 start time after import' => 'DELETE FROM `playlists` WHERE start_time = 0000-00-00;'




);

