<?php
$library_post_key = 'dopeysecurity';
$library_post_val = 'something';

if ($_POST[$library_post_key] == $library_post_val){
	
	require_once("../headers/db_header.php");

	
		if ($_POST['action']=='delete'){
		
		$query = "DELETE from library WHERE id=".$_POST['id'];
		
		
	//	SELECT playsheet_id, name, played, sam_id FROM adlog WHERE playsheet_id >= '$min' AND playsheet_id <= '$max' AND LEFT(type,2) = 'AD'  ORDER BY playsheet_id ASC";
		
		if( $result = $db['link']->query($query)){
			echo "deleted!";
			} else {
			echo "citr database problem :(";
		}
	
	} else if($_POST['action']=='dupenuking'){
		
		$exclude_array = explode(';',$_POST['exclude']);
	//	print_r($exclude_array);
		
/*
		Date Range:<br/>
		<input id=date_start name=date_start value=2012-00-00></input> to 
		<input id=date_end name=date_end value=2013-00-00></input><br/>
		Exclude Text: (character sequences between the semicolons will not be included in the duplicate search)<br/>
		<input id=exclude name=exclude value=" ;  ;s/t;"></input><br/>
		<br/><br/><a id=nukem name=submit value=submit>LOAD</a>';
		*/
		
//		echo 'action is DUPENUKING!!!';
		$query = "
		SELECT p1.id, p2.id, 
		p1.catalog, 
		p1.title, p2.title,
		p1.artist, p2.artist,
		p1.format_id, p2.format_id,
		p1.added, p2.added,
		p1.modified,
		p1.label,
		p1.genre,
		p1.crtc,
		p1.cancon,
		p1.femcon,
		p1.local,
		p1.playlist,
		p1.compilation,
		p1.status
				
				FROM library AS p1, library AS p2
				WHERE p1.added >= '".$_POST['date_start']."'
				AND p1.added <= '".$_POST['date_end']."'
				AND p2.added >= '".$_POST['date_start']."'
				AND p2.added <= '".$_POST['date_end']."'
				AND p1.title like p2.title
				AND p1.artist like p2.artist ";
	foreach($exclude_array as $i => $v){
		$query .= "AND NOT p1.title ='".$v."' ";
		
	}			
	$query.=	"AND p1.format_id = p2.format_id
				AND p1.id != p2.id
				ORDER BY p1.title
				";
		if( $result = $db['link']->query($query)){
			echo 'db request successful:<br/>';
			$dbrows = array();
			
			while($r = mysqli_fetch_array($result)){
				$dbrows []=$r;
			}
	
			foreach($dbrows as $i => $v){
				echo '<script src="js/library-js.js"></script>';
				echo '<div class=dupeSection>';
//				echo 'db ID:<span>'.$v['id'].'</span><br/>';

				echo 'Catalog:<span><a href="./library.php?action=edit&id='.$v['id'].'">'
						.$v['catalog'].'</a></span><br/>';
				echo 'Artist:<span>'.$v['artist'].'</span><br/>';
				echo 'Title:<span>'.$v['title'].'</span><br/>';
				echo 'Format:<span>'.getFormatName($v['format_id'], $db['link']).'</span><br/>';
				echo 'Label:<span>'.$v['label'].'</span><br/>';
				echo 'Genre:<span>'.$v['genre'].'</span><br/>';
				echo 'Added:<span>'.$v['added'].'</span><br/>';
				echo 'Modified:<span>'.$v['modified'].'</span><br/>';
				
				echo "Cancon: <span>".($v["cancon"] ? "Yes" : "No")."</span><br/>";
				echo "Femcon: <span>" .($v["femcon"] ? "Yes" : "No")."</span><br/>";
				echo "Local: <span>" .($v["local"] ? "Yes" : "No")."</span><br/>";
				echo "Playlist: <span>" . ($v["playlist"] ? "Yes" : "No")."</span><br/>";
				echo "Compilation: <span>" . ($v["compilation"] ? "Yes" : "No")."</span><br/>";
				
				
				echo "<br/><br/><span><a class='lib-delete' id=".$v['id'].">delete</a></span><br/><br/>";
				echo '</div>';

		//		print_r($v);

			}
			echo 'there were '.($i-1).' results<br/>';
			echo $query;
		}
			
		else{
			echo "citr database problem :(";
			
		//	echo $query;
		}
		
		
		}
		else {
			echo 'action undefined';
			}
		
	} else {echo 'sorry'; print_r($_POST['action']);}
?>