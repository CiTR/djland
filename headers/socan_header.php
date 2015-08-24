
<?php
//SOCAN_HEADER
function socanCheck($db) {
	
	if($socanPeriods = $db->query("SELECT * FROM socan ORDER BY socanStart DESC")){
		

		while($socanrow = $socanPeriods->fetch_array())
		{
		$socanrows[] = $socanrow;
		}
			
			//$db->close();
			$index = 0;
		if(!count($socanrows) ) return false;
			
		foreach($socanrows as $socanrow)
		{
			$id = $index++;
			
			$socanStart = strtotime($socanrow['socanStart']);
			$socanEnd = strtotime($socanrow['socanEnd']);
			
			$currentTime = strtotime("now");
		
			
			if( ($socanStart <= $currentTime) && ($currentTime<=$socanEnd) )
			{
				return true;
			}
		}
		return false;
	}
	else{
	echo "database query failed";	
	}
}
//END SOCAN_HEADER
?>

