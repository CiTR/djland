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

		$static_page = fopen($_SERVER['DOCUMENT_ROOT']."/static/friends.html",'w');
		$friends = Friends::orderBy('name','asc')->get();
		$alphabetical = array();
		foreach($friends as $friend){
			$alphabetical[$friend->name[0]][] = $friend;
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
			$listing .= "<li>";
			$listing .= "<a name='".$letter."'></a>";
			foreach($alphabetical[$letter] as $entry){
				$listing .="<div style='width:100%; max-height:150px; display:inline-block'>";
				$listing .="<h3>".$entry->name."</h3>";
				$listing .="<h4 style='display:inline; float:left'>".$entry->discount."</h4>";
				$listing .="<p>".$entry->address."</p>";
				$listing .="<p>".$entry->website."</p>";
				$listing .="<img style='max-height:100px;' href='".$entry->image_url."'></image>";
				$listing .="</div>";
			}
			$listing .= "</li>";
		}
		$listing .= "</ul>";
		$html .= $alphabet_nav;
		$html .= $listing;
		return $html;




	}
}
