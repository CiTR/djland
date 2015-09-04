<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $table = 'podcast_channels';
    protected $primaryKey = 'id';
    
    public function podcasts(){
    	return $this->hasMany('App\Podcast');
    }
    public function show(){
    	return $this->belongsTo('App\Show');
    }
    public function make_xml(){
    	return $this->make_channel_xml();
    }
	private function make_channel_xml(){
		include($_SERVER['DOCUMENT_ROOT'].'/config.php');

	    //Set up FTP access
		$ftp = $ftp_xml;
		$ftp->target_path = '/';
		$ftp->url_path = 'http://playlist.citr.ca/podcasting/xml/';

		//Get objects
		$host = $this->show->host;
		$show = $this->show;
		$channel = $this;
		$episodes = $this->podcasts;
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
				$metaDatas = stream_get_meta_data($temporary_file);
				$temporary_file_name = $metaDatas['uri'];
    			$num_bytes = file_put_contents($temporary_file_name,$xml);
    			//fclose($temporary_file);
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
	    return $response;
	}
}
