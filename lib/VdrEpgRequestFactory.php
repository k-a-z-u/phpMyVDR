<?php
	
	/** sort EPG entries by channel-name */
	class SortByChannelName implements Comparator {
		public function compareTo($a, $b) {
			$strDiff = strcasecmp($a->getChannel()->getName(), $b->getChannel()->getName());		// sort by channel
			if ($strDiff != 0) {return $strDiff;}
			$timeDiff = $a->getEvent()->getTsStart() - $b->getEvent()->getTsStart();				// then sort by time
			return $timeDiff;	
		}
	}
	
	/** sort EPG entries by channel-name */
	class SortByDayAndChannelName implements Comparator {
		public function compareTo($a, $b) {
			$dayDiff = $a->getEvent()->getTsStart() - $b->getEvent()->getTsStart();				// sort by day
			//if ($a->getEvent()->getTsStart() == 0 || $b->getEvent()->getTsStart()) {$dayDiff = 0;}// if no epg-data available
			if ($a->getEvent()->getTsStart() == 0) {return +1;}										// no-epg-data => end of list
			if ($b->getEvent()->getTsStart() == 0) {return -1;}										// no-epg-data => end of list
			if ($dayDiff != 0) {return $dayDiff;}
			$strDiff = strcasecmp($a->getChannel()->getName(), $b->getChannel()->getName());		// then sort by channel
			if ($strDiff != 0) {return $strDiff;}
			$timeDiff = $a->getEvent()->getTsStart() - $b->getEvent()->getTsStart();				// then sort by time
			return $timeDiff;	
		}
	}
	
	/** sort EPG entries by starting-time */
	class SortByStartTime implements Comparator {
		public function compareTo($a, $b) {
			$dayDiff = $a->getEvent()->getTsStart() - $b->getEvent()->getTsStart();
			//if ($a->getEvent()->getTsStart() == 0 || $b->getEvent()->getTsStart()) {$dayDiff = 0;}// if no epg-data available
			if ($dayDiff != 0) {return $dayDiff;}
			if (!$a->getEvent()) {return 1;}
			if (!$b->getEvent()) {return -1;}
			return $a->getEvent()->getTsStart() - $b->getEvent()->getTsStart();
		}
	}
	
	/** sort EPG entries by duration */
	class SortByDuration implements Comparator {
		public function compareTo($a, $b) {
			$dayDiff = $a->getEvent()->getDayOfYear() - $b->getEvent()->getDayOfYear();
			if ($dayDiff != 0) {return $dayDiff;}
			if (!$a->getEvent()) {return 1;}
			if (!$b->getEvent()) {return -1;}
			return $a->getEvent()->getDuration() - $b->getEvent()->getDuration();
		}
	}
	
	
	
	/**
	 * this class can be used to create epg-search-requests like
	 * "what is running now" or "which show contains the given string" etc
	 * to use them for display within the web-interface or to use them for the RSS
	 */
	class VdrEpgRequestFactory {	
		
		/** statics */
		private static $comparators;
	
		/** static ctor */
		public static function init() {
			VdrEpgRequestFactory::$comparators = array();
			VdrEpgRequestFactory::$comparators['day_and_chan_name'] = new SortByDayAndChannelName();
			VdrEpgRequestFactory::$comparators['chan_name'] = new SortByChannelName();
			VdrEpgRequestFactory::$comparators['time'] = new SortByStartTime();
			VdrEpgRequestFactory::$comparators['duration'] = new SortByDuration();
		}
	
	
		/**
		 * get response for the requests within the provide array
		 */ 
		public static function get(VdrEpgSqlite $db, array $attrs) {
			
			// get possible parameters
			$sortByStr = htmlentities( @$attrs['sort_by'] );				// get the string that determines the sorting order (if any)
			$searchStr = htmlentities( @$attrs['search_text'] );			// get the serach-string (if any)
			$searchTime = htmlentities( @$attrs['search_time'] );			// get the search-time (if any)
			$searchDuration = htmlentities( @$attrs['search_duration'] );	// get the search-duration (if any)
			$channelFilter = htmlentities( @$attrs['channelfilter'] );		// get the channel-filter (if any)
			$byChannel = htmlentities( @$attrs['by_channel'] );				// get only a specific channel (if set);
		
			// get the timestamp for the starting-time to search for
			$startTs = VdrEpgRequestFactory::getStartTs($searchTime);
			
			// check what was requested
			if (!empty($searchStr)) {
				$searchStr = VdrEpgRequestFactory::getSearchStr($searchStr);
				$entries = $db->getEpgByString($searchStr);
				
			} else if (!empty($byChannel)) {
				$entries = $db->getEpgByChannel($byChannel);
				
			} else if (!empty($searchDuration)) {
				$entries = $db->getEpgBetweenTs($startTs, $startTs + $searchDuration);
				
			} else {
				if		($searchTime == 'next')	{$entries = $db->getEpgAtTime($startTs, false);}
				else							{$entries = $db->getEpgAtTime($startTs, true);}
				
			}
			
			// check if filtering is selected
			$filter = null;
			if (!empty($channelFilter)) {$filter = new ChannelFilterUserlist($channelFilter);}
			
			// create an array out of it
			$resp = array();
			$cnt = 0;
			foreach ($entries as $entry) {
			
				// filter entries if a filter is selected
				if ($filter && !$filter->matches($entry)) {continue;}
				$resp[] = $entry;
								
			}
			
			// sort using selected sorter
			VdrEpgRequestFactory::sort($resp, $sortByStr);
			
			// return created data
			return $resp;
			
		}
		
		/**
		 * modify the search-string (if any):
		 * if it starts with '^', do not perform wildcard-search before the search string
		 * if it ends with '$', do not perform wildcard-search after the serach string
		 */
		private static function getSearchStr($searchStr) {
			$firstChar = $searchStr[0];
			$lastChar = $searchStr[strlen($searchStr) - 1];
			$searchStr = ($firstChar != '^')	? ('%' . $searchStr) : (substr($searchStr, 1));
			$searchStr = ($lastChar != '$')		? ($searchStr . '%') : (substr($searchStr, 0, strlen($searchStr) - 1));
			return $searchStr;
		}
		
		/** calculate the start-ts timestamp from various possible strings */
		private static function getStartTs($searchTime) {
			if		(empty($searchTime))	{return time();}										// not set? -> now!
			else if	($searchTime == 'now')	{return time();}										// now
			else if	($searchTime == 'next')	{return time();}										// now + modified search (later)
			else if	($searchTime == '2015')	{return VdrEpgRequestFactory::getTsToday(20, 15);}		// 20:15 on the current day
			else if	($searchTime == '2200')	{return VdrEpgRequestFactory::getTsToday(22, 00);}		// 22:00 on the current day
			else							{return $searchTime;}									// is already a valid timestamp
		}
	
	
		/** get hour:minute on the current day as timestamp */
		private static function getTsToday($hour, $minute) {
			$second = 0;
			$ts = time();
			$month = date('m', $ts);
			$day = date('d', $ts);
			$year = date('Y', $ts);
			return mktime($hour, $minute, $second, $month, $day, $year);
		}
	
		/** stort the provided array using the comparator selected via HTTP-POST */
		private static function sort(array &$entries, $sortByStr) {
		
			$comparator = @VdrEpgRequestFactory::$comparators[$sortByStr];
			if ($comparator == null) {$comparator = VdrEpgRequestFactory::$comparators['day_and_chan_name'];}
			Sorter::sort($entries, $comparator);
		
		}
	
	}

	/** static ctor */
	VdrEpgRequestFactory::init();

?>
