<?php
require_once('../api_common.php');
error_reporting(E_ALL);
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
            <tr><th>URL</th><th>Podcast Channel</th><th>Search Name</th><th>Show ID</th><th> Show Name </th><th>Episodes</th></tr>       


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
						
						$slug_replace = array('/podcasting/xml/','.xml');
						if($raw_xml){
							xml_parse_into_struct($parser, $raw_xml, $parsed_xml, $index);
							$channel -> title 		= isset($parsed_xml[$index['TITLE'][0]]['value']) ? $parsed_xml[$index['TITLE'][0]]['value'] : '';
							$channel -> subtitle 	= isset($parsed_xml[$index['ITUNES:SUBTITLE'][0]]['value']) ? $parsed_xml[$index['ITUNES:SUBTITLE'][0]]['value'] : '';
							$channel -> summary		= isset($parsed_xml[$index['ITUNES:SUMMARY'][0]]['value']) && $parsed_xml[$index['ITUNES:SUMMARY'][0]]['value'] != '' ? $parsed_xml[$index['ITUNES:SUMMARY'][0]]['value'] : '';
							$channel -> author 		= $parsed_xml[$index['ITUNES:AUTHOR'][0]]['value'];
							$channel -> xml 		= $xml_url;
							$channel -> slug 		= str_replace($slug_replace,'',$url);
						}
					}



					//Replace Chars to make matching easier
					$chars_to_strip = array("citr -- ","&", "citr", "radio", "and", "!","shows","show","'",'"',"-",':','2006','2007','2008','2009','2010');
					$strip = array('(',')',"'",'"');

					$channel_name = str_replace($chars_to_strip,'',strtolower($channel->title));
					$show_matches = array();
					$manual_assign = array(
						"sportsbroadcastarchive"=>377,
						"sportsbroadcastarchivetest"=>377,
						"sportsoffair"=>377,
						"sportsbroadcastarchive",
						"stateofflux"=>null,
						"sideofmonday"=>null,
						"livebroadcasts"=>294,
						"thebroadcast"=>null,
						"specialevents"=>294,
						"allawesomeinyourears"=>null,
						"myscienceproject"=>null,
						"thecity"=>'231',
						"thecanadianway"=>null,
						"theterrypodcast"=>'233',
						'queerfm'=>'85',
						'queerfmvancouverreloaded'=>'85',
						'queerfmqmunity'=>null,
						'supworld?'=>'227',
						'fillin'=>'284',
						'discorder'=>'209'
						);

					$key = str_replace(' ','',str_replace($strip,'',$channel_name));
					if(array_key_exists($key,$manual_assign)){
						$show_matches[] = array('name'=>$channel_name,'id'=>$manual_assign[$key]);
						echo "{$channel_name} = {$manual_assign[$key]} <br/>";
					}

					if(count($show_matches) == 0){
						foreach($shows as $show){
							$match = "#(".$channel_name.")#";
							$show_name = str_replace($chars_to_strip,'',html_entity_decode( strtolower( $show['name'] ),ENT_QUOTES ) );

							if( preg_match( $match, $show_name ) ){
								$show_matches[] = $show;
							}		
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

					
					//Give show channel information, otherwise make a new show with this channel information
					if(count($show_matches) > 0 && $show_matches[0]['id'] != null){
						$show_id = $show_matches[0]['id'];
						$update_query = "UPDATE shows SET 
							podcast_title=:title,
							podcast_subtitle=:subtitle,
							podcast_summary=:summary,
							podcast_xml=:xml,
							podcast_slug=:slug 
							WHERE id=:id";
						$statement = $pdo_db->prepare($update_query);
						$statement ->bindvalue(":id",$show_matches[0]['id']);
						$statement -> bindValue(":title",$channel->title);
						$statement -> bindValue(":subtitle",$channel->subtitle);
						$statement -> bindValue(":summary",$channel->summary);
						$statement -> bindValue(":xml",$channel->xml);
						$statement -> bindValue(":slug",$channel->slug);
						try{
							$statement->execute();
						}catch(PDOException $pdoe){
							$statement ->debugDumpParams();
							print_r($channel);
							echo $pdoe->getMessage();
							echo "</br/>";
						}
					}else{
						$today = date('Y-m-d H:i:s');
						$insert_query = "INSERT INTO shows SET
							name=:name,
							show_desc=:show_desc,
							podcast_author=:author,
							podcast_title=:title,
							podcast_subtitle=:subtitle,
							podcast_summary=:summary,
							podcast_xml=:xml,
							podcast_slug=:slug,
							weekday='0',
							start_time='00:00:00',
							end_time='00:00:00',
							pl_req='50',
							cc_req='75',
							indy_req='0',
							fem_req ='30',
							last_show='0000-00-00 00:00:00',
							create_date='{$today}',
							create_name='Techserv',
							active='0'";
						$statement = $pdo_db -> prepare($insert_query);

						$statement -> bindValue(":name",str_replace('CiTR -- ','',$channel->title));
						$statement -> bindValue(":show_desc",$channel->summary);
						$statement -> bindValue(":author",$channel->author);
						$statement -> bindValue(":title",$channel->title);
						$statement -> bindValue(":subtitle",$channel->subtitle);
						$statement -> bindValue(":summary",$channel->summary);
						$statement -> bindValue(":xml",$channel->xml);
						$statement -> bindValue(":slug",$channel->slug);
						try{
							$statement->execute();
							$show_id = $pdo_db->lastInsertId();
						}catch(PDOException $pdoe){
							$statement ->debugDumpParams();
							print_r($channel);
							echo $pdoe->getMessage();
							echo "</br/>";
						}
					}

					//Assign episodes to show
					$item_indexes = array();

					//Check if channel has any episodes
					if(isset($index['ITEM'])){
						//Pull out episode ID's
						foreach($index['ITEM'] as $key => $value){
							if($parsed_xml[$value]['type'] == 'open'){
								$item_indexes[] = $value;
							}
						}
						$episodes = array();

						//Reverse array order so that older podcasts have lower ID
						$item_indexes = array_reverse($item_indexes,true);
						foreach($item_indexes AS $key=>$value){
							$episode = array();
							$still_searching = true;
							$increment = 1;

							while($still_searching){
								if( $parsed_xml[$value + $increment]['tag'] == 'ITEM' && $parsed_xml[$value + $increment]['type'] == 'close' ){
									//Check to see if we have reached the end of the item
									$still_searching = false;
								}
								if( $parsed_xml[$value + $increment]['tag'] != 'ITEM'){
									if(isset($parsed_xml[$value + $increment]['value']) ){
										//Assign value to appropriate tag in episode
										$episode[$parsed_xml[$value + $increment]['tag']] = $parsed_xml[$value + $increment]['value'];
									}else if( isset($parsed_xml['attributes']) ){
										//Assign episode attribues
										foreach($element['attributes'] as $name => $value){
											$episode[$name] = $value;
										}
									}
									
								}

								$increment ++;
							}
							//Get Times
							$times_segment = explode('/',$episode['GUID'])[5];
							$times_array = explode('-to-',$times_segment);

							$start_time = $times_array[0];
			                $end_time = str_replace('.mp3','',$times_array[1]);

		                 	$episode['start_time'] = $start_time;
			                $episode['end_time'] = $end_time;

			                $start_date = date_parse_from_format('Ymd-His',$start_time);
			                $end_date = date_parse_from_format('Ymd-His',$end_time);

			                $start_unix =mktime(
	                            $start_date['hour'],
	                            $start_date['minute'],
	                            $start_date['second'],
	                            $start_date['month'],
	                            $start_date['day'],
	                            $start_date['year']
	                        );
			                $end_unix = mktime(
			                	$end_date['hour'],
	                            $end_date['minute'],
	                            $end_date['second'],
	                            $end_date['month'],
	                            $end_date['day'],
	                            $end_date['year']
	                        );

			                $episode['start_unix'] = $start_unix;
			                $episode['end_unix'] = $end_unix;
			                $episode['duration'] = $end_unix - $start_unix;
			                //print_r($episode);
			                $episodes[] = $episode;
						}
					}

					$insert_query = "INSERT INTO podcast_episodes SET 
						show_id=:show_id,
						title=:title,
						subtitle=:subtitle,
						summary=:summary,
						date=:date,
						url=:url,
						length=:length,
						author=:author,
						active='1',
						duration=:duration";

					$statement = $pdo_db->prepare($insert_query);

					foreach($episodes as $key=>$episode){
						$statement->bindValue(':show_id',$show_id);
						$statement->bindValue(':title',isset($episode['TITLE']) ? $episode['TITLE'] : "");
						$statement->bindValue(':subtitle',isset($episode['ITUNES:SUBTITLE']) ? $episode['ITUNES:SUBTITLE'] : "");
						$statement->bindValue(':summary',isset($episode['ITUNES:SUMMARY']) ? $episode['ITUNES:SUMMARY'] : "");
						$statement->bindValue(':date', $episode['PUBDATE']);
						$statement->bindValue(':url',$episode['GUID']);
						$statement->bindValue(':length',$episode['length']);
						$statement->bindValue(':author',isset($channel->author) ? $channel->author : "" );
						$statement->bindValue(':duration',$episode['duration']);
						try{
							$statement->execute();
							$episodes[$key]['id'] = $pdo_db->lastInsertId();
						}catch(PDOException $pdoe){
							$pdoe->getMessage();
						}
					}

					           
					
					echo count($show_matches) == 0 ? "<tr class='danger'>": "<tr>";
					echo "<td>{$xml_url}</td><td>{$channel->title}</td><td>{$channel_name}</td><td>";
					
					//Display matches
					echo count($show_matches) > 1 ? "<ul>":"";
					if(count($show_matches) == 0 || $show_matches[0]['id'] == null){
						echo "Creating New Show";
					}
					foreach($show_matches as $match){
						echo count($show_matches) > 1 ? "<li>{$match['id']}</li>" : $match['id'];
					}
					echo "</td><td>";
					foreach($show_matches as $match){
						echo count($show_matches) > 1 && $show_matches[0]['id'] != null ? "<li>{$match['name']}</li>" : $match['name'];
					}
					if(count($show_matches) == 0 || $show_matches[0]['id'] == null){
						echo "Creating New Show";
					}
					echo count($show_matches) > 1 ? "</ul>":"";
					echo "</td><td>";
					echo sizeOf($episodes);
					echo "</td><tr>";

					
				}
?>	
		</table>
    </body>
</html>

