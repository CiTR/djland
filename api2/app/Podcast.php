<?php

namespace App;

define('PODCAST_LIMIT_HOURS',8);

use Illuminate\Database\Eloquent\Model;

class Podcast extends Model
{
    protected $table = 'podcast_episodes';
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $fillable = array('playsheet_id', 'title', 'subtitle', 'summary', 'date', 'channel_id', 'url', 'length', 'author', 'active', 'duration', 'edit_date');

    public function playsheet(){
    	return $this->belongsTo('App\Playsheet');
    }
    public function channel(){
    	return $this->belongsTo('App\Channel');
    }
    public function make_podcast(){
    	$response = $this->make_audio();
    	return $response;
    }
    public function overwrite_podcast(){
    	$response = $this->overwrite_audio();
    	return $response;
    }
    public function duration_from_playsheet(){
    	$this->duration = strtotime($this->playsheet->end_time) - strtotime($this->playsheet->start_time);
    	$this->save();
    }

    private function make_audio(){
		include($_SERVER['DOCUMENT_ROOT'].'/config.php');
		date_default_timezone_set('America/Vancouver');
		if($this->duration > 8 * 60 * 60 || $this->duration < 0){
			return "Duration Wrong";
		}
		//Date Initialization
		$start = strtotime($this->playsheet->start_time);
		$end = $start + $this->duration;
	    $start_date =  date('d-m-Y+G%3\Ai%3\As', $start);
	    $end_date =  date('d-m-Y+G%3\Ai%3\As', $end);
	    $file_date = date('F-d-H-i-s',$start);
	    $year = date('Y',$start);

	    //Mon, 12 Jan 2015 18:00:00 -0800
	    $date = date('M, d Y H:i:s O',$start);

	    //Archiver URL to download from
		$archive_access_url = "http://archive.citr.ca/py-test/archbrad/download?archive=%2Fmnt%2Faudio-stor%2Flog";
	    $archive_url = $archive_access_url."&startTime=".$start_date."&endTime=".$end_date;

	    //Set File Name
	    $file_name = html_entity_decode(str_replace(array("'", '"',' '),'-',$this->playsheet->show->name),ENT_QUOTES).'-'.$file_date.'.mp3';

		//Set ID3 Tags
    	$tags = array(
	        'title'         => array($this->title),
	        'artist'        => array($this->playsheet->show->name),
	        'album'         => array('CiTR Radio Podcasts'),
	        'year'          => array(date('Y', strtotime($this->date))),
	        'genre'         => array($this->playsheet->show->primary_genre_tags),
	        'comment'       => array('This podcast was created in part by CiTR Radio')
    	);
    	
    	$target_dir = '/home/podcast/audio/'.$year.'/';
    	//$target_dir = 'audio/'.$year.'/'; 	
    	$target_file_name = $target_dir.$file_name;
		
    	$target_url = 'http://playlist.citr.ca/podcasting/audio/'.$year.'/'.$file_name;

    	//Get Audio from Archiver
    	$file_from_archive = fopen($archive_url,'r');
    	
		//If we obtain a file from archiver
		if($file_from_archive){
			//Open local file
			$target_file = fopen($target_file_name,'wb');
			$num_bytes = 0;
			
			//If we open local file
			if($target_file){
				//Attempt to add ID3 Tags
				//if($tags && $error == '') {
		        //    rewind($target_file);
		        //    write_tags($tags,$info['uri']);
		        //    rewind($target_file);

				//User a buffer so we don't hit the max memory alloc limit
				while (!feof($file_from_archive)) {
				   $buffer = fread($file_from_archive, 1024*16);  // use a buffer of 16mb bytes
				   $num_bytes += fwrite($target_file, $buffer);
				}

				//Update the podcast object to reflect changes
				$this->url = $target_url;
				$this->length = $num_bytes;
				$this->date = $date;
				$this->save();
				$response['audio'] = array('url' => $target_url	);
				//Update XML to reflect new podcast creation
				$response['xml'] = $this->channel->make_xml();
			}	
		}
		while(is_resource($file_from_archive)){
		   //Handle still open
		   fclose($file_from_archive);
		}
		while(is_resource($target_file)){
		   //Handle still open
		   fclose($target_file);
		}
	    return $response;
	}
	
	private function overwrite_audio(){
		date_default_timezone_set('America/Vancouver');
		//Date Initialization
		$start = strtotime($this->playsheet->start_time);
		$end = $start + $this->duration;
	    $start_date =  date('d-m-Y+G%3\Ai%3\As', $start);
	    $end_date =  date('d-m-Y+G%3\Ai%3\As', $end);

		//Archiver URL to download from
		$archive_access_url = "http://archive.citr.ca/py-test/archbrad/download?archive=%2Fmnt%2Faudio-stor%2Flog";
	    $archive_url = $archive_access_url."&startTime=".$start_date."&endTime=".$end_date;

	    //Get File Name from URL. Note that we set target dir to end at audio so that we handle legacy files that are not sorted by year.
	    $target_dir = '/home/podcast/audio/';
	    if($this->url != null){
	    	 $file_name = explode('/',$this->url,6)[5];
    	}else{
    		//Set File Name
	    	$file_date = date('F-d-H-i-s',$start);
	    	$file_name = html_entity_decode(str_replace(array("'", '"',' '),'-',$this->playsheet->show->name),ENT_QUOTES).'-'.$file_date.'.mp3';
    	}
	  
	   
	    $target_file_name = $target_dir.$file_name;
	    
	    //Get Audio from Archiver
	    $file_from_archive = fopen($archive_url,'r');
    	
    	//If we obtain a file from archiver
		if($file_from_archive){
			//Open local file
			$target_file = fopen($target_file_name,'wb');
			$num_bytes = 0;
			
			//If we open local file
			if($target_file){
				//Attempt to add ID3 Tags
				//if($tags && $error == '') {
		        //    rewind($target_file);
		        //    write_tags($tags,$info['uri']);
		        //    rewind($target_file);

				//User a buffer so we don't hit the max memory alloc limit
				while (!feof($file_from_archive)) {
				   $buffer = fread($file_from_archive, 1024*16);  // use a buffer of 16mb bytes
				   $num_bytes += fwrite($target_file, $buffer);
				}

				$this->length = $num_bytes;
				$this->save();
				$response['audio'] = array('url'=>$this->url,'size'=>$num_bytes);
				//Update XML to reflect new podcast data (Duration,filesize)
				$response['xml'] = $this->channel->make_xml();
			}	
		}
		while(is_resource($file_from_archive)){
		   //Handle still open
		   fclose($file_from_archive);
		}
		while(is_resource($target_file)){
		   //Handle still open
		   fclose($target_file);
		}
	    return $response;
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
