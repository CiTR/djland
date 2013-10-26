
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
			
			
		foreach($socanrows as $socanrow)
		{
			$id = $index++;
			
			$socanStart = strtotime($socanrow['socanStart']);
			$socanEnd = strtotime($socanrow['socanEnd']);
			
			$currentTime = strtotime("now");
			
			////Uncomment to check times.
			// echo "current time:".$currentTime."\n";
			// echo "socan start time:".$socanStart."\n";
			// echo "socan end time:".$socanEnd."\n";
			// echo "It is a socan period:";
			
			
			if( ($socanStart <= $currentTime) && ($currentTime<=$socanEnd) )
			{
				// echo "true\n";
				return true;
			}
			else
			{
				// echo "false\n";
				
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

