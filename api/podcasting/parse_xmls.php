<?php
require_once('../api_common.php');
error_reporting(E_ALL & ~ E_NOTICE);
$extension_type = 'xml';


function file_extension($xml_url){
    $array = explode('.',$xml_url);
    return $array[count($array)-1];
}

$directory_html = file_get_contents('http://playlist.citr.ca/podcasting/xml/');
//Get all filenames in this directory 
preg_match_all('/podcasting\/xml\/[a-zA-Z0-9_-]*.'.$extension_type.'/', $directory_html, $xml_urls);

$show_names_query = "SELECT id,name from shows";
$statement = $pdo_db->prepare($show_names_query);
$statement -> execute();
$show_names = $statement -> fetchAll(PDO::FETCH_ASSOC);

?>
<html>
    <head>
        <link rel='stylesheet' href='../../../js/bootstrap/bootstrap.min.css'></script>
    </head>
    <body>
    	<table class='table-condensed table-hover'>
            <tr><th>URL</th><th>Podcast Channel</th><th>Search Name</th><th>Show ID</th><th> Show Name </th></tr>       


<?php

	$xml_urls = $xml_urls[0];

	//Attempt to match channels with
	$show_query = "SELECT id,name FROM shows";
	$statement = $pdo_db->prepare($show_query);
	$statement -> execute();
	$shows = $statement->fetchAll(PDO::FETCH_ASSOC);
	
	foreach($xml_urls as $key => $url){
		
		$channel = new stdClass();
		$xml_url = 'http://playlist.citr.ca/'.$url;

		if(file_extension($xml_url) == $extension_type){
			$parser = xml_parser_create();
			$raw_xml = file_get_contents($xml_url);
			
			if($raw_xml){
				xml_parse_into_struct($parser, $raw_xml, $parsed_xml, $index);
				$channel -> title 		= $parsed_xml[$index['TITLE'][0]]['value'];
				$channel -> subtitle 	= $parsed_xml[$index['ITUNES:SUBTITLE'][0]]['value'];
				$channel -> summary		= $parsed_xml[$index['DESCRIPTION'][0]]['value'];
				$channel -> xml 		= $xml_url;
				$channel -> slug 		= str_replace('/podcasting/xml/','',$url);
			}
		}


		//Replace Chars to make matching easier
		$chars_to_strip = array("citr -- ","&", "citr", "radio", "and", "!","shows","show","'",'"',"-");
		$channel_name = str_replace($chars_to_strip,'',strtolower($channel->title));

		$show_matches = array();
		foreach($shows as $show){
			$match = "#(".$channel_name.")#";
			$show_name = str_replace($chars_to_strip,'',html_entity_decode( strtolower( $show['name'] ),ENT_QUOTES ) );

			if( preg_match( $match, $show_name ) ){
				$show_matches[] = $show;
			}		
		}
		if(count($show_matches) == 0){
			foreach($shows as $show){
				$show_name = html_entity_decode( str_replace($chars_to_strip,'',strtolower( $show['name'] ) ) ,ENT_QUOTES);
				$terms = explode(' ',$channel_name);
				foreach($terms as $key=>$value){
					if( strlen($value) > 4 && count($show_matches) == 0){
						$match = '#\b'.$value.'\b#';
						if( preg_match( $match, $show_name )){
							$show_matches[] = $show;
						}
					}
				}	
			}
		}
		if(count($show_matches) == 0){
			foreach($shows as $show){
				$show_name = html_entity_decode( str_replace($chars_to_strip,'',strtolower( $show['name'] ) ) ,ENT_QUOTES);
				if(levenshtein($show_name, $channel_name) < 2 && count($show_matches) == 0){
					$show_matches[] = $show;
				}
			}
		}
		//USE SQL LIKE on individual terms over size
		if(count($show_matches) == 0){

			$terms = preg_split("#'|[\s/]#",$channel_name);

			//If the term is 1 or 2 letters long remove it
			foreach($terms as $key => $term){
				if(strlen($term) <= 4) unset ($terms[$key]);
			}
			//If we have any terms left, lets search by them
			if(count($terms) > 0 ){
				$search_query = "SELECT id,name FROM shows WHERE";
				$i = 0;
				foreach($terms as $key=>$term){
					$search_query .= $i < 1 ? " name LIKE :term{$key}" : " OR name LIKE :term{$key}";
					$i++;
				}
				$statement = $pdo_db->prepare($search_query);
				foreach($terms as $key=>$term){
					$statement -> bindValue(':term'.$key,"%".$term."%");
				}
				try{
					$statement -> execute();
				}catch(PDOException $pdoe){
					print_r($terms);
					$statement->debugDumpParams();
				}		
				
				$match_results = $statement->fetchAll(PDO::FETCH_ASSOC);
				foreach($match_results as $show){
					$show_matches[] = $show;
				}
			}	
		}
		echo count($show_matches) == 0 ? "<tr class='danger'>": "<tr>";
		echo "<td>{$xml_url}</td><td>{$channel->title}</td><td>{$channel_name}</td><td>";
		
		//Display matches
		echo count($show_matches) > 1 ? "<ul>":"";
		foreach($show_matches as $match){
			echo count($show_matches) > 1 ? "<li>{$match['id']}</li>" : $match['id'];
		}
		echo "</td><td>";
		foreach($show_matches as $match){
			echo count($show_matches) > 1 ? "<li>{$match['name']}</li>" : $match['name'];
		}
		echo count($show_matches) > 1 ? "</ul>":"";
		echo "</td><tr>";
		//break;
	}
?>	
</table>
    </body>
</html>

