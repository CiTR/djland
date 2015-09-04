<?php

namespace App;

define('PODCAST_LIMIT_HOURS',8);

use Illuminate\Database\Eloquent\Model;

class Podcast extends Model
{
    protected $table = 'podcast_episodes';
    const CREATED_AT = null;
    const UPDATED_AT = 'edit_date';
    protected $fillable = array('playsheet_id', 'title', 'subtitle', 'summary', 'date', 'channel_id', 'url', 'length', 'author', 'active', 'duration', 'edit_date');

    public function playsheet(){
    	return $this->belongsTo('App\Playsheet');
    }
    public function channel(){
    	return $this->belongsTo('App\Channel');
    }
    public function make_podcast(){
    	$response['audio'] = $this->make_audio();
    	$response['xml'] = $this->make_podcast_xml();

    	return $response;
    }
    private function make_audio(){
		include($_SERVER['DOCUMENT_ROOT'].'/config.php');
		
		if($this->duration > 8 * 60 * 60 || $this->duration < 0){
			return "Duration Wrong";
		}
		//Date Initialization
		$start = strtotime($this->playsheet->start_time);
		$end = $start + $this->duration;
	    $start_date =  date('d-m-Y+G%3\Ai%3\As', $start);
	    $end_date =  date('d-m-Y+G%3\Ai%3\As', $end);
	    $date = date('F-d-Y',$start);
	    $year = date('Y',$start);

	    //Set up FTP access
		$ftp = $ftp_audio;
		$ftp->target_path = '/'.$year.'/';
		$ftp->url_path = 'http://playlist.citr.ca/podcasting/audio/'.$year.'/';

	    //Archiver URL to download from
		$archive_access_url = "http://archive.citr.ca/py-test/archbrad/download?archive=%2Fmnt%2Faudio-stor%2Flog";
	    $archive_url = $archive_access_url."&startTime=".$start_date."&endTime=".$end_date;

	    //Set File Name
	    $file_name = html_entity_decode($this->playsheet->show->name,ENT_QUOTES).'-'.$date.'.mp3';

		//Set ID3 Tags
    	$tags = array(
	        'title'         => array($this->title),
	        'artist'        => array($this->playsheet->show->name),
	        'album'         => array('CiTR Radio Podcasts'),
	        'year'          => array(date('Y', strtotime($this->date))),
	        'genre'         => array($this->playsheet->show->primary_genre_tags),
	        'comment'       => array('This podcast was created in part by CiTR Radio')
    	);


	    $ftp_connection = ftp_connect($ftp->url, $ftp->port);
	    if($ftp_connection){
	    	if(ftp_login($ftp_connection,$ftp->username ,$ftp->password)){
	    		//Set to passive mode? It worked...
	    		ftp_pasv($ftp_connection, true);
	    		
	    		//Download the file from the server
	    		$file_from_archive = file_get_contents($archive_url);
	    		
	    		if(strlen($file_from_archive) > 1){
	    			//Create a temporary file to hold the mp3
    				$temporary_file = tmpfile();
        			$num_bytes = fwrite($temporary_file,$file_from_archive);
    				
    				//Attempt to add ID3 Tags
    				/*if($tags && $error == '') {
			            rewind($file_handle);
			            write_tags($tags,$info['uri']);
			            rewind($file_handle);
			        }*/

    				if($num_bytes > 16){
    					//Check to see if directory exists, if not then create it
 						/*if(!ftp_chdir($ftp_connection,$ftp->target_path)){
    						ftp_chdir($ftp_connection,"/");
    						ftp_mkdir($ftp_connection, $ftp->target_path);
    					}*/

    					if(ftp_fput($ftp_connection, $ftp->target_path.$file_name, $temporary_file, FTP_BINARY)){
	            			//Successfully Uploaded the file
    						$response = array(
		            			'filename' => $file_name,
		                		'size' => $num_bytes,
		                		'start' => $start_date,
		                		'end' => $end_date,
		                		'url' => $ftp->url_path.$file_name
		                		);
	            		}else{
	            			$response = "Failed to write to FTP server";
	            		}
        			}else{
        				$response = "Failed to connect to write temp file";
        			}
	    		}else{
	    			$response = "Failed to connect to archiver";
	    		}
	    	}else{
	    		$response = "Failed to login";
	    	}
    		//Make sure we close our connection
	    	ftp_close($ftp_connection);
	    	
	    }
	    return json_encode($response);

	}
	

	private function make_podcast_xml(){
		include($_SERVER['DOCUMENT_ROOT'].'/config.php');

	    //Set up FTP access
		$ftp = $ftp_xml;
		$ftp->target_path = '/';
		$ftp->url_path = 'http://playlist.citr.ca/podcasting/xml/';

		//Get objects
		$host = $this->playsheet->show->host;
		$show = $this->playsheet->show->getAttributes();
		$channel = $this->playsheet->show->channel;
		$episodes = $channel->podcasts;
	    $channel = $channel->getAttributes();
	    $file_name = $channel['slug'].'.xml';

	    //Remove Legacy Encoding issues
	    foreach ($channel as $field) {
        	$field = htmlspecialchars(html_entity_decode($field,ENT_QUOTES),ENT_QUOTES);
            }


	    $xml_head = '<?xml version="1.0"  ?><rss xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" version="2.0" > ';
	    $xml = '';
	    $xml .= $xml_head;
	    $xml .= '<channel>';
	    $xml .= '<title>'. $channel['title'] . '</title>';
	    
	    $xml .= '<description>' . $show['show_desc'] . '</description> ';
	    $xml .= '<itunes:summary>' . $show['show_desc'] . '</itunes:summary> ';
	    $xml .= '<itunes:author>' . $host['name'] . '</itunes:author> ';
	    $xml .= '<itunes:subtitle>' . $channel['subtitle'] . '</itunes:subtitle> ';
	    $xml .= '<itunes:owner> ' .
	        '<itunes:name>' . $channel['owner_name'] . '</itunes:name> ' .
	        '<itunes:email>' . $channel['owner_email'] . '</itunes:email> ' .
	        '</itunes:owner>';
	    $xml .= '<itunes:image href="' . $show['show_img'] . '"/>';

	    $xml .= '<itunes:link rel="image" type="video/jpeg" href="' . $show['show_img'] . '">' . $show['name'] . '</itunes:link> ';
	    $xml .= '<image>' .
	        '<link>' . $channel['link'] . '</link>' .
	        '<url>' . $show['show_img'] . '</url>' .
	        '<title>' . $show['name'] . '</title>' .
	        '</image> ';
	    $xml .= '<link>' . $channel['link'] . '</link> ';
	    $xml .= '<generator>CiTR Radio Podcaster</generator> ';

	    //Build Each Podcast
	    foreach ($episodes as $episode) {
	    	//Get Objects
	    	$playsheet = $episode->playsheet;
	    	$episode = $episode->getAttributes();
	        if($episode['active']== 1) {
	            
	        	//Remove Legacy Encoding issues
	            foreach ($episode as $field) {
	            	$field = htmlspecialchars(html_entity_decode($field,ENT_QUOTES), ENT_QUOTES);
	            }
	            $xml .=
	                '<item>' .
	                '<title>' . htmlspecialchars(html_entity_decode($playsheet['title'],ENT_QUOTES),ENT_QUOTES) . '</title>' .
	                '<pubDate>' . $episode['date'] . '</pubDate>' .
	                '<description>' . htmlspecialchars(html_entity_decode($playsheet['summary'],ENT_QUOTES),ENT_QUOTES) . '</description>' .
	                '<itunes:subtitle>' . $episode['subtitle'] . '</itunes:subtitle>';
	            $xml .= ($episode['duration'] > 0) ? '<itunes:duration>' . $episode['duration'] . '</itunes:duration> ' : '';

	            $xml .=
	                '<enclosure url="' . $episode['url'] . '" length="' . $episode['length'] . '" type="audio/mpeg"/>' .
	                '<guid isPermaLink="true">' . $episode['url'] . '</guid></item>';
	        }
	    }
	    $xml .= '</channel></rss>';


	    $ftp_connection = ftp_connect($ftp->url, $ftp->port);
	    if($ftp_connection){
	    	if(ftp_login($ftp_connection,$ftp->username ,$ftp->password)){
	    		//Set to passive mode? It worked...
	    		ftp_pasv($ftp_connection, true);
	    		
    			//Create a temporary file to hold the xml
				$temporary_file = tmpfile();
    			$num_bytes = fwrite($temporary_file,$xml);

				if($num_bytes > 16){
					//Check to see if directory exists, if not then create it
						/*if(!ftp_chdir($ftp_connection,$ftp->target_path)){
						ftp_chdir($ftp_connection,"/");
						ftp_mkdir($ftp_connection, $ftp->target_path);
					}*/
					if(ftp_fput($ftp_connection, $ftp->target_path.$file_name, $temporary_file, FTP_BINARY)){
            			//Successfully Uploaded the file
						$response = array(
	            			'filename' => $file_name,
	                		'size' => $num_bytes,
	                		'url' => $ftp->url_path.$file_name
	                		);
            		}else{
            			$response = "Failed to write to FTP server";
            		}
    			}else{
    				$response = "Failed to connect to write temp file";
    			}

	    	}else{
	    		$response = "Failed to login";
	    	}
    		//Make sure we close our connection
	    	ftp_close($ftp_connection);
	    	
	    }
	    return json_encode($response);
	}

	private function write_tags($tags,$file){
	    global $error;
	    $TextEncoding = 'UTF-8';
	    //require_once($_SERVER['CONTEXT_DOCUMENT_ROOT'].'/headers/getid3/getid3/getid3.php');
	    // Initialize getID3 engine


	    $getID3 = new getID3;
	    $getID3->setOption(array('encoding'=>$TextEncoding));

	    //require_once($_SERVER['CONTEXT_DOCUMENT_ROOT'].'/headers/getid3/getid3/write.php');
	    // Initialize getID3 tag-writing module
	    $tagwriter = new getid3_writetags;
	    $tagwriter->filename = $file;
	    $tagwriter->tagformats = array('id3v2.4');

	    // set various options (optional)
	    $tagwriter->overwrite_tags = true;
	    $tagwriter->tag_encoding = $TextEncoding;
	    $tagwriter->remove_other_tags = true;

	    // populate data array
	    $TagData = $tags;
	    $tagwriter->tag_data = $TagData;

	    // write tags
	    if ($tagwriter->WriteTags()) {

	        if (!empty($tagwriter->warnings)) {
	            $error .= 'There were some warnings:<br>'.implode('<br><br>', $tagwriter->warnings);
	        }
	    } else {
	        $error .= 'Failed to write tags!<br>'.implode('<br><br>', $tagwriter->errors);
	    }
	}

}
