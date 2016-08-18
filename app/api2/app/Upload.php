<?php


namespace App;
use Illuminate\Database\Eloquent\Model;
use App\Show;
use App\Podcast;
use InvalidArgumentException;

class Upload extends Model{
	protected $table = 'uploads';
    protected $fillable = array('file_name','file_type','category','path','size','description','url','relation_id','CREATED_AT','UPDATED_AT');

	public static function create(array $attributes = array()){
		//Check to see if the file type is acceptable. If not, throw an exception.
		require_once($_SERVER['DOCUMENT_ROOT']."/config.php");
		$allowed_file_types = $djland_upload_categories[$attributes['category']];
		if(!in_array($attributes['file_type'],$allowed_file_types)) throw new InvalidArgumentException('File Type Not Allowed: '.$attributes['file_type']);
		return parent::create($attributes);
	}

    public function uploadImage($file){
    	require_once($_SERVER['DOCUMENT_ROOT']."/config.php");
		$response = new StdClass();

		if($_FILES == null || $this->file_name == null || $this->path == null || $this->category == null){
			$response->text = "Valid file not given.";
			$response->success = false;
			return $response;
		}
		$temp_file = $_FILES['file']['tmp_file'];
		$check = getimagesize($temp_file);

		if($check == false){
			$response->text = "File is not an image";
			$response->success = false;
			return $response;
		}

    	//Get dirs based on file type
    	$base_dir = $_SERVER['DOCUMENT_ROOT']."/uploads/";

		//chars to strip from names + dirs
		$strip = array('(',')',"'",'"','.',"\\",'/',',',':',';','@','#','$','%','&','?','!');

    	//Ensure the uploads folder exists, if not create it
		if(!file_exists($base_dir)){
			mkdir($base_dir,0755);
		}

		//Ensure the category folder exists, if not create it
    	$target_dir = $base_dir.$this->category."/";
		if(!file_exists($target_dir)){
			mkdir($target_dir,0755);
		}

		//Ensure the target folder exists, if not create it
		switch($this->category){
			case 'show_image':
				$show = Show::find($this->relation_id);
				$stripped_name = str_replace($strip,'',$show->name);
				break;
			case 'episode_image':
				$podcast = Podcast::find($this->relation_id);
				$stripped_name = str_replace($strip,'',$podcast->show->name);
				break;
			case 'member_resource':
				$resource = Resource::find($this->relation_id);
				$stripped_name = str_replace($strip,'',$resource->name);
				break;
			case 'friend_image':
				$friend = Friend::find($this->relation_id);
				$stripped_name = str_replace($strip,'',$friend->name);
				break;
			case 'special_broadcast_image':
				$special_broadcast = SpecialBroadcast::find($this->relation_id);
				$stripped_name = str_replace($strip,'',$special_broadcast->name);
				break;
			case 'default':
				break;
		}

		//Generate File Names & Directories
		$today = date('Y-m-d');
		$target_file = $target_dir.$stripped_name.".".$today.$this->file_type;
		$target_url = str_replace($_SERVER['DODCUMENT_ROOT'],'http://'.$_SERVER['SERVER_NAME'],$target_file);

		if(move_uploaded_file($_FILES['file']['tmp_name'],$target_file)){
			if(chmod($target_file,0661)){
				$response->text = "The file ". basename( $temp_file['name']). " has been uploaded.";
				$respones->success = true;
				$response->path = $target_file;
				$response->url = $target_url;
				return $response;
			}else{
				$response->text = "Could not set permissions for file.";
				$response->success = false;
				return $response;
			}
		}else{
			$response->text = "Could not move file to directory.";
			$response->success = false;
			return $response;
		}
    }
	public function uploadAudio($file){
		if(!$file){
			$response->text = "Valid file not given.";
			$response->success = false;
			return $response;
		}
		if(!id3_get_tag($temp_file)){
			$response->success = false;
			$response->text = 'File is not a valid mp3 file';
			return $response;
		}

		//chars to strip from names + dirs
		$strip = array('(',')',"'",'"','.',"\\",'/',',',':',';','@','#','$','%','?','!');

		switch($this->category){
			case 'episode_audio':
				//Get the podcast
				$podcast = Podcast::find($this->relation_id);

				//Strip unwanted chars from the show name and convert & to and
				$stripped_show_name = str_replace('&','and',str_replace($strip,'',$podcast->show->name));

				//Create the file directory,name, and url
				$target_dir = $path['audio_base']."/".date('Y',strtotime($podcast->playsheet->start_time));
				//check if file exists already. If so, we overwrite existing file
				if($podcast->length && $podcast->length > 0 || $podcast->url != null){
					$target_file_name = $target_dir.explode('/',$this->url,6)[5];
				}else{
					$target_file_name = $stripped_show_name."-".date('F-d-H-i-s',strtotime($podcast->playsheet->start_time).'.mp3');
				}
				$target_url = 'http://playlist.citr.ca/podcasting/audio/'.$year.'/'.$target_file_name;
				break;
			default:
				//we only accepting audio files for episode audio right now.
				break;
		}

		$target_file = fopen($target_file_name,'wb');
		$num_bytes = 0;
		//check if the file opens
		if($target_file){
			while(!feof($temp_file)){
				$buffer = fread($file_from_archive,1024*16);
				$num_bytes += fwrite($target_file,$buffer);
			}

			$podcast->length = $num_bytes;
			$podcast->save();
			$response['audio'] = array('url'=>$podcast->url,'size'=>$num_bytes);
			$response['xml'] = $podcast->show->make_show_xml();

			while(is_resource($target_file)){
				//make sure we close the file handle
				fclose($target_file);
			}
		}else{
			$response->success = false;
			$response->text = "Failed to open file for write";
		}
		return $response;
	}
}
