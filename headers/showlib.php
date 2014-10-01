<?php
// CiTR Show-fetching PHP Library r102 (2013-04-23)
date_default_timezone_set ("America/Vancouver");
class ShowLib {
	private $mysqli_link; // mysql link identifier
	private $curr_week;
	private $curr_time;
	private $all_times; 
	private $all_socials;
	private $all_hosts;
	private $all_shows;
	private $all_shows_inactive;

	function __construct($link) {
		$this->mysqli_link = $link;
		$this->curr_time = time();
		$this->curr_week = ShowTime::getWeekNum($this->curr_time);

		// list of each show's time info
		$time_q = mysqli_query($this->mysqli_link,"SELECT * FROM show_times");
		$this->all_times = array();
		while($time_r = mysqli_fetch_assoc($time_q)) { // Get times
			$this->all_times[] = $time_r;
		}

		$social_q = mysqli_query( $this->mysqli_link,"SELECT * FROM social");
		$this->all_socials = array();
		while($social_r = mysqli_fetch_assoc($social_q)) { // Get socials
			$this->all_socials[] = $social_r;
		}

		$this->all_hosts = array();
		$hosts_q = "SELECT * FROM hosts";
		$hosts_result = mysqli_query($this->mysqli_link, $hosts_q);
		while($hosts_rows = mysqli_fetch_assoc($hosts_result)){
			$this->all_hosts []= $hosts_rows;
		}

		$this->all_shows = $this->initializeShows(false);
		$this->all_shows_inactive = $this->initializeShows(true);

		mysqli_free_result($time_q);
		mysqli_free_result($social_q);
		mysqli_free_result($hosts_result);
	}
	
	// Private helper functions
	/*
	private function mysqli_result_dep($res, $row, $field=0) {
    $res->data_seek($row); 
    $datarow = $res->fetch_array(); 
    return $datarow[$field]; 
} */
	private function initializeShows($include_inactive){
		$query = "SELECT * FROM shows"; 
		
		if(!$include_inactive){
			$query.= " WHERE active = '1'";
		}
		$query.= " ORDER BY name";
		
		$shows = array();
		if($result = $this->mysqli_link->query($query)){
			while($row = mysqli_fetch_array($result)){
				$shows[$row['id']] = $this -> prepareShow($row); // Returns Array with each show prepared to specific format.
			}
		}else{
			echo 'database query error';
		}
		return $shows;
	}

	private function prepareShow($show_r) {
		//Filling in show host names.
		foreach($this->all_hosts as $i => $host_info){
			if($host_info['id'] == $show_r['host_id']){
				$show_r["host"] = $host_info['name'];
			}
		}

		$show_times = array();

		foreach($this->all_times as $show => $one_time_list){
			if ($one_time_list['show_id'] == $show_r['id']){
				

						//TODO add duration as a new field in all $times arrays created with show objects
				$wdt = ShowTime::createWeekdayTime($one_time_list['start_time'],$one_time_list['start_day']);
				$wdt_end = ShowTime::createWeekdayTime($one_time_list['end_time'],$one_time_list['end_day']);
				$duration = ($wdt_end - $wdt)/3600; // 3600 seconds in an hour

		 	 	$one_time_list['duration'] = $duration;
				$show_times []= $one_time_list;
			}
		}

//
		$show_socials = array();

		foreach($this->all_socials as $i => $one_socials_list){
			if($one_socials_list['show_id'] == $show_r['id']){
				$show_socials []= $one_socials_list;
			}
			
		}

	
		return new Show($show_r, $show_times, $show_socials);
	}
	
	// Public functions ----------------------------------------
	
	// Returns: show (obj) - the current show
	function getCurrentShow() {
		if ($show = $this->getShowByTime($this->curr_time)) {
			return $show;
		}
		else { // No show at current time, CiTR Ghost Mix
			return new Show(array("name"=>"CiTR Ghost Mix"),array(),array()); // TODO
		}
	}

	// Args: $id - a valid show id number
	// Returns: show (obj) - the show represented by the id
	function getShowById($id) {
		return $this->all_shows[$id];
/*		$show_q = mysqli_query( $this->mysqli_link,"SELECT * FROM shows WHERE id=$id");
		if ($show = mysqli_fetch_assoc($show_q)) {
			return $this->prepareShow($show);
		}
		else { // No show found
			return null;
		}
		*/
	}

	// Args: $time - a valid Unix timestamp (eg. time() )
	// Returns: show (obj) - the show represented by the time
	function getShowByTime($time) {
//		echo 'finding show for time '.$time.' ('.date("H:i:s", $time).')';
		$target_wdt = ShowTime::createWeekdayTime(date("H:i:s", $time),date("w", $time));
		$target_weeknum = ShowTime::getWeekNum($time);
		// Retrieve all active shows
		$shows = $this->getAllShows();
		foreach ($shows as $show) {
			foreach ($show->times as $time_r) {


				if ($time_r['alternating'] == 0 || $time_r['alternating'] == $target_weeknum) {
					$start_wdt = ShowTime::createWeekdayTime($time_r['start_time'],$time_r['start_day']);
					$end_wdt = ShowTime::createWeekdayTime($time_r['end_time'],$time_r['end_day']);
					if ($start_wdt > $end_wdt)  {
						// fixme 
						// if start time later than end time, week wrap-around
						// UNLESS there is human error in show info
						// can only be wrap-around case if show start day is on saturday
						$end_wdt = ShowTime::addWeek($end_wdt);
					}
					if ($target_wdt >= $start_wdt && $target_wdt < $end_wdt) { // a match
//						echo ' target: '.$target_wdt;
//						echo ' start:'.$start_wdt;
//						echo ' end:'.$end_wdt;
						return $show;
					}
				}
			}
		}
		return null; // No match, return null
	}
	
	// Returns an array of two weeks of show blocks starting from current time, sorted by time
	function getAllCurrentShowBlocks() {
		return $this->getAllShowBlocksByTime($this->curr_time);
	}
	
	// a BetterBlock is like a ShowBlock except it is an associative 
	// array with just two elements:
	// 'show_obj' is the show object for the block
	// 'unix_start' is the absolute unix start time for this show episode, rather than 
	// the number of seconds since sunday.

	function getBetterBlocksInSameDay($unix){
		
		$date_info = getDate($unix);
		$weekDay = $date_info['wday'];
		
		$shows = $this->getAllShows();
		
		$betterBlocksInDay = array();
		
		foreach ($shows as $show){
			foreach($show->times as $showTime){
				
				if($showTime['start_day'] == $weekDay){
					
					$start_unix = strtotime($showTime['start_time'], $unix);//strtotime($explodedStartTime,$unix);
					$thisBetterBlock = array();
					$thisBetterBlock['show_obj'] = $show;
					$thisBetterBlock['name'] = $show->name;
					$thisBetterBlock['unix_start'] = $start_unix;//$unix + strtotime($showTime['start_time'],0);
					$thisBetterBlock['unix_end'] = strtotime($showTime['end_time'], $unix);
					
					$betterBlocksInDay []= $thisBetterBlock;
				}	
			}	
		}
		
		
		
		return $betterBlocksInDay;
	}

	// Arg: $time - a UNIX timestamp
	// Returns an array of two weeks of show blocks, sorted by time
	// Show block format: a time row (see Show definition for more details)
	//					  appended with show_name, show_id, wdt
	
	function getAllShowBlocksByTime($time) {
		$lastSunday = strtotime("last Sunday", $time);
		$nextSunday = strtotime("next Sunday", $time);
		$start_wdt = ShowTime::createWeekdayTime(date("H:i:s", $time),date("w", $time));
		$start_weeknum = ShowTime::getWeekNum($time);
		$shows = $this->getAllShows();
		$showblocks = array();
		// Create timeblocks for each show, then add them to $timeblocks
		foreach ($shows as $show) {
			foreach ($show->times as $time_r) {
				$time_r['show_name'] = $show->name;
				$time_r['show_id'] = $show->id;
				$wdt = ShowTime::createWeekdayTime($time_r['start_time'],$time_r['start_day']);
				$wdt_end = ShowTime::createWeekdayTime($time_r['end_time'],$time_r['end_day']);
				$duration = ($wdt_end - $wdt)/3600; // 3600 seconds in an hour
					
				// If not alternating, create two rows
				if ($time_r['alternating'] == 0) {
					$time_r2 = $time_r; // Create duplicate time row
					// Before start date, so move to end
					if ($wdt < $start_wdt) {
						$time_r['wdt'] = ShowTime::addWeek($wdt);
						$time_r2['wdt'] = ShowTime::addWeek($wdt,2);
					}
					else {
						$time_r['wdt'] = $wdt;
						$time_r2['wdt'] = ShowTime::addWeek($wdt); // Only add one week
					}
					
					//tack on duration
					$time_r['duration'] = $duration;
					$time_r2['duration'] = $duration;
					
					
					
					$time_r['unixtime'] = $time_r['wdt'] + $lastSunday;
					$time_r2['unixtime'] = $time_r2['wdt'] + $lastSunday;

					// Add both time rows
					$showblocks[] = $time_r;
					$showblocks[] = $time_r2;
				}
				// Only one time row
				else {
					// Different week number
					if ($time_r['alternating'] != $start_weeknum) {
						$time_r['wdt'] = ShowTime::addWeek($wdt); // Add one week
					}
					// Same week number, but before start date
					else if ($wdt < $start_wdt) {
						$time_r['wdt'] = ShowTime::addWeek($wdt,2); // Add two weeks
					}
					// Same week number, after start date
					else {
						$time_r['wdt'] = $wdt; // unchanged
					}
					// tack on  duration
					$time_r['duration'] = $duration;
					
					
					$time_r['unixtime'] = $time_r['wdt'] + $lastSunday;
					
					// Add time row
					$showblocks[] = $time_r;
				}
				
			}
		}
		// All the timeblocks are now in $timeblocks - time to sort!
		usort($showblocks, array("ShowBlock","compShowBlocks"));
		
		return $showblocks;
	}
	
	// Arg: $inactive (optional) - set true to also show inactive shows (false by default)
	// Returns: an array of all shows in the database sorted alphabetically (only active by default)
	function getAllShows($inactive=false) {

		if($inactive){
			return $this->all_shows_inactive;
			
		} else {
			return $this->all_shows;
			
		}

	}
	
	// Returns: the current unix time
	function getCurrentTime() {
		return $this->curr_time;
	}
	
	// Returns: array(show name, show time (formatted))
	function getCurrentShownameAndTime() {
		$show = $this->getCurrentShow();
		return array($show->name, $show->getFormattedMatchingTime($this->getCurrentTime()));
	}
}

class ShowBlock {
	// Compares two show blocks
	// (-1 if $b1 before $b2, 0 if same time, 1 if $b1 after $b2)
	public static function compShowBlocks($b1, $b2) {
		$wdt1 = $b1['wdt'];
		$wdt2 = $b2['wdt'];
		if ($wdt1 == $wdt2) return 0;
		return ($wdt1 < $wdt2) ? -1 : 1;
	}
	
	public static function compBetterBlocks($b1, $b2) {
		$wdt1 = $b1['unix_start'];
		$wdt2 = $b2['unix_start'];
		if ($wdt1 == $wdt2) return 0;
		return ($wdt1 < $wdt2) ? -1 : 1;
	}
	
	// Arg: $b - a valid show block
	// Returns: a number representing the length of the show (in hours)
	public static function getShowBlockLength($b) {
		$start_wdt = ShowTime::createWeekdayTime($b['start_time'],$b['start_day']);
		$end_wdt = ShowTime::createWeekdayTime($b['end_time'],$b['end_day']);
		return ($end_wdt - $start_wdt)/3600; // 3600 seconds in an hour
	}
}

class ShowTime {
	public static $dow = array(0=>"Sunday",1=>"Monday",2=>"Tuesday",3=>"Wednesday",4=>"Thursday",5=>"Friday",6=>"Saturday");
	public static $dow_simp = array(0=>"Sun",1=>"Mon",2=>"Tue",3=>"Wed",4=>"Thu",5=>"Fri",6=>"Sat");
	
	// Args: $hhmmss - a time formatted in hh:mm:ss
	//		 $dow (integer) - the day of week (where Sunday is 0)
	// Returns: a custom formatted weekdaytime where
	//			wdt(weekdaytime) = number seconds elapsed from Sunday 12am
	public static function createWeekdayTime($hhmmss, $dow) {
		sscanf($hhmmss, "%d:%d:%d", $hours, $mins, $secs);
		$wdt = ($hours * 3600) + ($mins * 60) + $secs;
		$wdt += $dow * 86400; // Adds a certain amount of days to wdt (a day is 86400 seconds)
		return $wdt;
	}
	
	// Adds a week to a weekdaytime
	// Args: $wdt - a valid weekdaytime, $numweeks(optional/default 1) - number of weeks to add
	// Returns: a weekday time (+ $numweeks weeks)
	public static function addWeek($wdt, $numweeks=1) {
		return $wdt + $numweeks*7*86400;
	}
	
	// Gets day of the week as a number
	// Args: $wdt - a valid weekdaytime
	// Returns: day of the week (int)
	public static function getDayOfWeek($wdt) {
		return floor($wdt/86400)%7;
	}
	
	// Get week number of a unix time
	// Args: $time - a unix timestamp
	// Returns: 1 or 2
	public static function getWeekNum($time) {
		$weeks_elapsed = floor(($time - 1341100800)/(7*24*60*60));
		$week_num = ($weeks_elapsed%2) + 1;
		return $week_num;
	}
}

// Show class
class Show {
	public $id;
	public $name;
	public $host;
	public $genre;
	public $show_desc;
	public $img_url;
	public $lang_default;
	public $crtc_default;
	public $website;
	public $podcast;
	public $requirements = array();
	public $times = array();
	public $contact = array();
	public $active;
	
	// Constructors
	// Args: $show_r - an array of show info ($show_r["attr"] = val)
	//		 $times - an array of time rows ($times[0] = array("attr"=>val))
	//				- each row contains (show_id, start_day, start_time, end_day, end_time, alternating(0,1,2), duration)
	//		 $socials - an array of social rows ($socials[0] = array("attr"=>val))
	// 				  - each row contains (show_id, social_name, social_url, unlink (bool 0/1))
	function __construct($show_r, $times, $socials) {
		$this->setVar($this->id, $show_r['id']);
		$this->setVar($this->name, $show_r['name']);
		$this->setVar($this->host, $show_r['host']);
		$this->setVar($this->genre, $show_r['genre']);
		$this->setVar($this->lang_default, $show_r['lang_default']);
		$this->setVar($this->crtc_default, $show_r['crtc_default']);
		$this->setVar($this->show_desc, $show_r['show_desc']);
		$this->setVar($this->img_url, $show_r['show_img']);
		$this->setVar($this->website, $show_r['website']);
		$this->setVar($this->podcast, $show_r['rss']);
		$this->requirements = array("pl" => $show_r["pl_req"], "cc" => $show_r["cc_req"], "indy" => $show_r["indy_req"], "fem" => $show_r["fem_req"]);
		$this->times = $times;
		$this->contact = $socials;
		$this->sponsors = $this->getSponsorPairs($show_r);
		$this->active = $show_r['active'] ;

	}
	
	private function setVar(&$varName, $val) {
		if (!is_null($val) && $val != "") {
			$varName = $val;
		}
		else {
			$varName = null;
		}
	}
	
	// Receives show array and outputs array of sponsor information
	// formatted as [0]=>array("name"=>"name1", "url"=>"url1), etc
	private function getSponsorPairs($show_r) {
		$sponsor_names = array();
		if (!is_null($show_r['sponsor_name']) && $show_r['sponsor_name'] != "") {
			$sponsor_names = array_map("trim", explode(";",$show_r['sponsor_name']));
		}
		$sponsor_urls = array();
		if (!is_null($show_r['sponsor_url']) && $show_r['sponsor_url'] != "") {
			$sponsor_urls = array_map("trim", explode(";",$show_r['sponsor_url']));
		}
		$output = array();
		$count = 0;
		foreach ($sponsor_names as $name) {
			$output[$count]["name"] = $name;
			if (!is_null($sponsor_urls[$count]) && $sponsor_urls[$count] != "") {
				$output[$count]["url"] = $sponsor_urls[$count];
			}
			else {
				$output[$count]["url"] = null;
			}
			$count++;
		}
		return $output;
	}
	
	// Special
	
	// Finds the showtime from the show that contains a given time
	// Args: $time - a unix timestamp
	// Returns: array() - a time row
	//					- array of (show_id, start_day, start_time, end_day, end_time, alternating(0,1,2))
	// 					- where start_day and end_day are weekday numbers from 0-6
	//					- start_time and end_time are "hh:mm:ss" strings
	function getMatchingTime($time) {
		$target_time = ShowTime::createWeekdayTime(date("H:i:s", $time),date("w", $time));
		$target_week = ShowTime::getWeekNum($time);
		foreach($this->times as $time_r) {
			if ($time_r['alternating'] == 0 || $time_r['alternating'] == $target_week) {
				$start_wdt = ShowTime::createWeekdayTime($time_r['start_time'],$time_r['start_day']);
				$end_wdt = ShowTime::createWeekdayTime($time_r['end_time'],$time_r['end_day']);
				if ($start_wdt > $end_wdt) { // if start time later than end time, week wrap-around
					$end_wdt = ShowTime::addWeek($end_wdt);
				}
				if ($target_time >= $start_wdt && $target_time < $end_wdt) { // a match
					return $time_r;
				}
			}
		}
		return null; // No match
	}
	
	// Args: $time - a unix timestamp
	// Returns: formatted time string from show that matches given time
	function getFormattedMatchingTime($time) {
		if ($time_r = $this->getMatchingTime($time)) {
			// Since start_time and end_time are "hh:mm:ss" strings convert them to dates first
			$start_t = strtotime($time_r['start_time']);
			$end_t = strtotime($time_r['end_time']);
			if ($time_r['start_day'] == $time_r['end_day']) {
				return date("H:i",$start_t)." - ".date("H:i",$end_t); // They are now "hh:mm" strings
			}
			else {
				return "(".ShowTime::$dow_simp[$time_r['start_day']].") ".date("H:i",$start_t)." - "."(".ShowTime::$dow_simp[$time_r['end_day']].") ".date("H:i",$end_t);
			}
		}
		return "";
	}
}

?>