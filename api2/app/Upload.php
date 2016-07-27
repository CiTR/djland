<?php


namespace App;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model{
	protected $table = 'uploads';
    protected $fillable = array('file_name','file_type','size','path','category','description','url','CREATED_AT','EDITED_AT');


    public function upload($file){
    	if($_FILES == null || $this->file_name == null || $this->path == null || $this->category == null)
    		return false;


    	
    	switch($file['type']){
		case 'image/png':
		case 'png':
			$this->file_type = '.png';
			break;
		case 'image/jpeg':
		case 'jpeg':
			$this->file_type = '.jpeg';
			break;
		case 'image/jpg':
		case 'jpg':
			$this->file_type = '.jpg';
			break;
		case 'image/gif':
		case 'gif':
			$this->file_type = '.gif';
			break;
		case 'pdf':
			$this->file_type = '.pdf';
			break;
		case 'mp3':
			$this->file_type = '.mp3';
			break;
		default:
			$imageFileType = 'null';
			break;
   		}

   		$base_dir = $_SERVER['DOCUMENT_ROOT']."/uploads";
     	

    }


}