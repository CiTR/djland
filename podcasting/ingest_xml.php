<?php

$burli_xml_dir = './burli-xml/';
$max = '1000'; // set to something like 10 if you are just testing the script

?>

<head>
    <style type="text/css">
        body{
            width:1100px;
        }

        #blue{
            background-color:paleturquoise;
            margin-left:30px;
            margin-right:10px;
            padding:10px;
            margin-bottom:20px;
            width:40%;
            overflow:scroll;
            display: inline-block;
            height:750px;
        }

        #red{
            background-color:lightcoral;
            margin-left:30px;
            margin-bottom:20px;
            width:40%;
            overflow:scroll;

            height:750px;
            float:right;
            display:inline;
        }

        #section{
            border-width: 2;
            border-style:solid;
            width:98%;
        }
    </style>
</head>

<?php

require_once('../headers/db_header.php');

    $dir = $burli_xml_dir;
    $extension = 'xml';
    $dir_contents = scandir($dir);

    foreach ($dir_contents as $key => $filename) {

        if ($key <= $max){

        // IF ITS A XML FILE, process it
        if (is_file($dir . $filename) && substr($filename, 0, 1) != '.') {

//            echo '<div id=section>';
            if (file_extension($filename) == $extension) {

                $p = xml_parser_create();

                $xml_string = file_get_contents($dir . $filename);
                xml_parse_into_struct($p, $xml_string, $values, $index);

                echo '<h3>'.$filename.'</h3>';
                echo '<hr> xml parse result:<br/> ';

               echo '<div id=blue><pre>';

               print_r($values);




               echo '</pre></div>';
               echo '<div id="red"><pre>';
               print_r($index);
               echo '</pre></div>';



//                echo '<h2>channel data:</h2>';
                $channel_info = array();

                $target_index = $index['TITLE'][0];
                echo $values[$target_index]['value'];

                $channel_info['title'] = $values[$index['TITLE'][0]]['value'];
                $channel_info['subtitle'] = isset($index['ITUNES:SUBTITLE'][0])&&isset($values[$index['ITUNES:SUBTITLE'][0]]['value']) ? $values[$index['ITUNES:SUBTITLE'][0]]['value'] : '';
                
                
                $channel_info['summary'] = intval($values[$index['DESCRIPTION'][0]]['level'])<=3? $values[$index['DESCRIPTION'][0]]['value'] : '';
                $channel_info['author'] = $values[$index['ITUNES:AUTHOR'][0]]['value'];
                $channel_info['keywords'] = isset($index['ITUNES:KEYWORDS'][0])? $values[$index['ITUNES:KEYWORDS'][0]]['value'] : '';
                $channel_info['owner_name'] = $values[$index['ITUNES:NAME'][0]]['value'];
                $channel_info['owner_email'] = $values[$index['ITUNES:EMAIL'][0]]['value'];
                $channel_info['default_episode_title'] = '';
                $channel_info['default_episode_subtitle'] = '';
                $channel_info['default_episode_author'] = '';
                $channel_info['link'] = $values[$index['LINK'][0]]['value'];
                $channel_info['image'] = isset($index['ITUNES:LINK'][0])? $values[$index['ITUNES:LINK'][0]]['attributes']['HREF'] : '';
                $channel_info['xml'] = $filename;

                $channel_q = "INSERT into podcast_channels (title, subtitle, summary, author, keywords, owner_name, owner_email, episode_default_title, episode_default_subtitle, episode_default_author, link, image_url, xml) ";
                $channel_q .= "VALUES ('".htmlentities(addslashes($channel_info['title']))."','".
                                htmlentities(addslashes($channel_info['subtitle'])) ."','".
                    htmlentities(addslashes($channel_info['summary'])) ."','".
                    htmlentities(addslashes($channel_info['author']))."','".
                    htmlentities(addslashes($channel_info['keywords'])) ."','".
                    htmlentities(addslashes($channel_info['owner_name'])) ."','".
                    htmlentities(addslashes($channel_info['owner_email']))."','".
                    htmlentities(addslashes($channel_info['default_episode_title'])) ."','".
                    htmlentities(addslashes($channel_info['default_episode_subtitle'])) ."','".
                    htmlentities(addslashes($channel_info['default_episode_author'])) ."','".
                    htmlentities(addslashes($channel_info['link'])) ."','".
                    htmlentities(addslashes($channel_info['image'])) ."','".
                    htmlentities(addslashes($channel_info['xml'])) ."');";

//                $channel_q = mysqli_escape_string($db,$channel_q);
                $channel_id = -1;
                if ($result = mysqli_query($db,$channel_q)){
                    $channel_id = mysqli_insert_id($db);
                    echo '<h2>channel inserted! (id is '.$channel_id.')</h2>';



                } else {
                    echo '<h2>could not insert this show into the db. query:'.$channel_q.'</h2>';
                }
                echo '<pre>';
                print_r($channel_info);
                echo '</pre>';



//IMAGE
                if(isset($index['ITUNES:IMAGE'])) {
                    echo '<h3>channel image:</h3>';
                    $target_index = $index['ITUNES:IMAGE'][0];
                    echo '<img src="' . $values[$target_index]['attributes']['HREF'] . '"/>';
                }


                $item_indexes = array();

                if( isset($index['ITEM']) ) {
                    foreach ($index['ITEM'] as $i => $v) {
                        if ($values[$v]['type'] == 'open') {
                            $item_indexes [] = $v;
                        }
                    }

                    $episodes = array();
                    foreach ($item_indexes as $i => $v) {

                        $one_episode = array();

                        $searching = true;
                        $more = 1;
                        while ($searching) {

                            if ($values[$v + $more]['tag'] == 'ITEM' && $values[$v + $more]['type'] == 'close') {
                                $searching = false;
                            }
                            if ($values[$v + $more]['tag'] != 'ITEM') {

                                if (isset($values[$v + $more]['value'])) {
                                    $word_index = $values[$v + $more]['tag'];
                                    $one_episode[$word_index] = $values[$v + $more]['value'];
                                } else {

                                    if (isset($values[$v + $more]['attributes'])) {
                                        foreach ($values[$v + $more]['attributes'] as $attr_name => $attr_val) {
                                            $one_episode[$attr_name] = $attr_val;
                                        }
                                    }
                                }
                            }

                            $more++;
                        }

                        $episodes [] = $one_episode;
                    }

                if($channel_id>=0){
                    ingest_episodes($episodes,$channel_id,$db);
                } else {
                    echo '<br/>no channel id found<br/>';
                }

                } else {
                    echo 'no episodes in this channel';
                }



                xml_parser_free($p);
/*
                echo 'episodes:<br/><pre>';
                print_r($episodes);
                echo '</pre>';*/
            }

        } else {
            echo '<p>' . $filename . ' is not a file';
        }
            echo '</div>';
    }
    }

function file_extension($filename){
    $array = explode('.',$filename);
    return $array[count($array)-1];
}


function ingest_episodes($episodes,$channel_id, $db){
    foreach($episodes as $i => $episode){
        $episode_insert = "INSERT into podcast_episodes (title,subtitle,summary,date,channel_id,url,length) ";
        $episode_insert .= "VALUES ('".
            htmlentities(addslashes($episode['TITLE']))."','".
            htmlentities(addslashes($episode['ITUNES:SUBTITLE']))."','".
            htmlentities(addslashes($episode['ITUNES:SUMMARY']))."','".
            htmlentities(addslashes($episode['PUBDATE']))."','".
            htmlentities(addslashes($channel_id))."','".
            htmlentities(addslashes($episode['URL']))."','".
            $episode['LENGTH']."');";

        if ($result = mysqli_query($db,$episode_insert)){
            echo '<br/>episode inserted<br/>';
        } else {
            echo '<br/>problem inserting episode. Query:<br/>'.$episode_insert;
        }

    }
}
