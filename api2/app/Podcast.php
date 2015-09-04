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
    	$response['xml'] = $this->channel->make_xml();
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
