<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friends extends Model
{
    const CREATED_AT = 'created';
    const UPDATED_AT = 'edited';
    protected $table = 'friends';
   	protected $guarded	= array('id');
    protected $fillable = array('name','address', 'phone', 'website','discount','image_url',);

    public static function write_static(){
        include($_SERVER['DOCUMENT_ROOT'].'/config.php');

		if(!$testing_environment){
			$static_page = fopen("/home/citr/citr-wp/app/static/friends.html",'w');
		}else{
			$static_page = fopen($_SERVER['DOCUMENT_ROOT']."/static/friends.html",'w');
		}
		$friends = Friends::whereNotNull('name')->orderBy('name','asc')->get();
		$alphabetical = array();
		foreach($friends as $friend){
			$alphabetical[substr(trim($friend->name),0,1)][] = $friend;
		}
		$letters = array_keys($alphabetical);

		$html = "<div>";

		$alphabet_nav = "<ul style='display:inline; font-size:1.5em; height:40px; list-style:none;'>";
		foreach($letters as $letter){
			$alphabet_nav .= "<li style='display:inline-block; padding:0px 5px 0px 5px;'><a href='#".$letter."'>".$letter."</a></li>";
		}
		$alphabet_nav .= "</ul>";
		$listing = "<ul style='list-style:none;'>";
		foreach($letters as $letter){
			$listing .= "<li style='padding-top:15px;'>";
			$listing .= "<a name='".$letter."'></a>";
			foreach($alphabetical[$letter] as $entry){
				$listing .="<div style='width:100%; min-width:450px; float:left;'>";
				$listing .="<div style='width:60%; display:inline-block; float:left;'>";
				$listing .="<h3>".$entry->name."</h3>";
				$listing .="<div>".$entry->discount."</div>";
				$listing .="<div style:'margin:0;'><a href='https://www.google.ca/maps/search/".join('+',explode(' ',$entry->address)).",+Vancouver,+BC' target='blank_'>".$entry->address."</a></div>";
				$listing .="<div><a href='".$entry->website."'>".$entry->website."</a></div>";
				$listing .="<div>".$entry->phone."</div>";
				$listing .="</div>";
				$listing .="<div style='width:35%; height:150px; display:inline-block; text-align:center; float:left; white-space:nowrap'>";
				$listing .="<span style='vertical-align:middle; display:inline-block; height:100%;'></span><img style='max-height:100%; max-width:100%; vertical-align:middle;' src='".$entry->image_url."'></image>";
				$listing .="</div>";
				$listing .="</div>";
			}
			$listing .= "</li>";
		}
		$listing .= "</ul>";
		$html .= $alphabet_nav;
		$html .= $listing;

		if( fwrite($static_page,$html) > 0){
			fclose($static_page);
			return $html;
		}else{
			fclose($static_page);
			return false;
		} ;
	}
}
