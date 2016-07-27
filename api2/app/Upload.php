<?php


namespace App;

class Upload{
	protected $table = 'membership';
    protected $fillable = array('file_name','file_type','path','djland_category','description','url','size','CREATED_AT','EDITED_AT');


    public function upload($file){
    	if($_FILES == null || $this->file_name == null || $this->path == null || $this->djland_category == null)
    		return false;
    	$file = $_FILES[0];
    	
    	

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
			$this->file_type = '.pdf'
			break;
		case 'mp3'
			$this->file_type = '.mp3';
			break;
		default:
			$imageFileType = 'null';
			break;
   		}

   		$base_dir = $_SERVER['DOCUMENT_ROOT']."/uploads";
     	

    }


}