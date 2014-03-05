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
			$dayDiff = ($a->getEvent()->getDayOfYear() - $b->getEvent()->getDayOfYear());		// sort by day
			//if ($a->getEvent()->getTsStart() == 0 || $b->getEvent()->getTsStart()) {$dayDiff = 0;}// if no epg-data available
			if ($a->getEvent()->getTsStart() == 0) {return +1;}										// no-epg-data => end of list
			if ($b->getEvent()->getTsStart() == 0) {return -1;}										// no-epg-data => end of list
			if ($dayDiff != 0) {return $dayDiff;}
			$strDiff = strcasecmp($a->getChannel()->getName(), $b->getChannel()->getName());		// sort by channel
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
	 * "what is running now" or "which show contains the given string" etc.
	 * to use them for displaying within the web-interface or and RSS-feed.
	 */
	class VdrEpgRequestFactory {
		
		
		/** constants */
		const SORT_BY_NOTHING = 1;
		const SORT_BY_DAY_AND_CHAN_NAME = 2;
		const SORT_BY_CHAN_NAME = 3;
		const SORT_BY_TIME = 4;
		const SORT_BY_DURATION = 5;
		
		
		/** statics */
		private static $comparators;
	
		/** static ctor */
		public static function init() {
			VdrEpgRequestFactory::$comparators = array();
			VdrEpgRequestFactory::$comparators[self::SORT_BY_DAY_AND_CHAN_NAME] = new SortByDayAndChannelName();
			VdrEpgRequestFactory::$comparators[self::SORT_BY_CHAN_NAME] = new SortByChannelName();
			VdrEpgRequestFactory::$comparators[self::SORT_BY_TIME] = new SortByStartTime();
			VdrEpgRequestFactory::$comparators[self::SORT_BY_DURATION] = new SortByDuration();
		}
	
	
		/**
		 * get response for the requests within the provide array
		 */ 
		public static function get(VdrEpgSqlite $db, array $attrs) {
			
			// get possible parameters
			$params = new VdrEpgRequestFactoryParams();
			if (@$attrs['search_time'])		{$params->setSearchTime( $attrs['search_time'] );}
			if (@$attrs['by_channel'])		{$params->setSearchChannel( $attrs['by_channel'] );}
			if (@$attrs['search_duration'])	{$params->setSearchDuration( $attrs['search_duration'] );}
			if (@$attrs['search_text'])		{$params->setSearchString( $attrs['search_text'] );}
			if (@$attrs['channelfilter'])	{$params->setChannelFilterList( $attrs['channelfilter'] );}
			if (@$attrs['sort_by'])			{$params->sortBy( $attrs['sort_by'] );}
			if (@$attrs['event_id'])			{$params->setEventID( $attrs['event_id'] );}
			
			return self::getByParams($db, $params);
		
		}
	
		/** get database statement for the given params */
		private static function getStatement(VdrEpgSqlite $db, VdrEpgRequestFactoryParams $params, $explain = false) {
			
			$sql  = '';
			if ($explain) {$sql .= 'EXPLAIN QUERY PLAN ';}
			$sql .= 'SELECT s.*,c.name as channelName, c.code as channelCode FROM show s LEFT JOIN channel c on c.idx = s.channelIdx WHERE ';
			
			// search string given -> show entries matching the given string within either desc or title)
			if ($params->getSearchStr())		{$sql .= '(title LIKE :txt OR descLong LIKE :txt) AND ';}
			
			// channel given -> only show entries running on this channel
			if ($params->getChannel())			{$sql .= '(s.channelIdx = :chanIdx) AND ';}
			
			// select show directly by database event ID
			if ($params->getEventID())			{$sql .= '(s.id = :eventID) AND ';}
			
			// search for shows running within the given time-region?
			if ($params->getEndTS() && $params->getStartTS()) {
				$sql .= '(eventTsStart < :tsEnd) AND ((eventTsEnd) > :tsStart) AND '; 
			}
			
			// search for shows that are UP at the given time (e.g. 20:15) ?
			else if ($params->getStartTS()) {
				$sql .= '(eventTsStart <= :tsStart) AND ((eventTsEnd) > :tsStart) AND ';
			}
			
			// just ensure the show did not run in the past
			else {
				$sql .= '((eventTsEnd) > :ts) AND ';
			}
			
			// search time given (e.g. 20:15) -> show starts before 20:15 AND runs after 20:15
			// if no search time is given, ensure we do not list shows running in the past
			//if ($params->getSearchTime())		{$sql .= '(eventTsStart <= :tsStart) AND ';}
			//else								{$sql .= '((eventTsStart+eventDuration) > :ts) AND ';}
			
			// search duration given (needs search time) -> shows running between search time and search time + duration
			// if no duration is given, list only shows running EXACTLY at the given start search time
			// (e.g. shows starting before 20:15 and still up after 20:15 -> only one result possible)
			//if ($params->getEndTS())			{$sql .= '(eventTsStart < :tsEnd) AND ';}
			//else								{$sql .= '((eventTsStart + eventDuration) > :tsStart) AND ';}
			
			// remove trailing AND
			$sql = substr($sql, 0, strlen($sql)-4);
			//$sql .= 'LIMIT 100';
			
			if ($explain) {echo $sql;}
			
			// prepare the statement
			$stmt = $db->prepare($sql);
			
			// bind params
			$ts = time();
			$filter = false;
											{$stmt->bindValue(':ts', $ts, SQLITE3_INTEGER);}
			if ($params->getStartTS())		{$stmt->bindValue(':tsStart', $params->getStartTS(), SQLITE3_INTEGER);	$filter = true;}
			if ($params->getEndTS())		{$stmt->bindValue(':tsEnd', $params->getEndTS(), SQLITE3_INTEGER);		$filter = true;}
			if ($params->getEventID())		{$stmt->bindValue(':eventID', $params->getEventID(), SQLITE3_INTEGER);	$filter = true;}
			if ($params->getSearchStr())	{$stmt->bindValue(':txt', $params->getSearchStr(), SQLITE3_TEXT);		$filter = true;}
			if ($params->getChannel())		{$stmt->bindValue(':chanIdx', $params->getChannel(), SQLITE3_INTEGER);	$filter = true;}
			
			// done. ensure at least one filter is set.. else the whole DB would be retrieved!
			if (!$filter) {throw new Exception("no filter active. result would be huge!");}
			return $stmt;
			
		}
	
		public static function getByParams(VdrEpgSqlite $db, VdrEpgRequestFactoryParams $params) {
			
			/*
			$stmt = self::getStatement($db, $params, true);
			$res = $stmt->execute();
			$arr = $res->fetchArray();
			echo '<pre>';
			var_dump($arr);
			echo '</pre>';
			*/
			
			try {
				$entries = array();
				$stmt = self::getStatement($db, $params);
				$res = $stmt->execute();
				$entries = new VdrEpgSqliteIterator( $res );
			} catch (Exception $e) {
				;
			}
		
			/*
			// get the timestamp for the starting-time to search for
			$startTs = $params->getStartTS();
			
			// check what was requested
			if ($params->getBy() == VdrEpgRequestFactoryParams::GET_BY_STRING) {
				$searchStr = $params->getSearchStr();
				$entries = $db->getEpgByString($searchStr);
				
			} else if ($params->getBy() == VdrEpgRequestFactoryParams::GET_BY_CHANNEL) {
				$entries = $db->getEpgByChannel($params->getChannel());
				
			} else if ($params->getBy() == VdrEpgRequestFactoryParams::GET_BY_DURATION) {
				$entries = $db->getEpgBetweenTs($startTs, $startTs + $params->getSearchDuration());
				
			} else if ($params->getBy() == VdrEpgRequestFactoryParams::GET_BY_TIME || 1==1) {
				if		($params->getSearchTime() == VdrEpgRequestFactoryParams::SEARCH_TIME_NEXT)	{$entries = $db->getEpgAtTime($startTs, false);}
				else																				{$entries = $db->getEpgAtTime($startTs, true);}
				
			}
			*/
			
			// check if filtering is selected
			$filtered = array();
			if ($params->useChannelFilterList()) {
				
				$filter = new ChannelFilterUserlist($params->getChannelFilterList());
				foreach ($entries as $entry) {
					if (!$filter->matches($entry)) {continue;}
					$filtered[] = $entry;
				}
				
			} else {
				
				foreach($entries as $entry) {$filtered[] = $entry;}
				
			}
			
			// sort using selected sorter
			VdrEpgRequestFactory::sort($filtered, $params->getComparator());
			
			// return created data
			return $filtered;
			
		}
		
		/**
		 * stort the provided array using the given comparator.
		 * @comparator must be one of the SORT_BY_XXX constants
		 */
		private static function sort(array &$entries, $comparator = self::SORT_BY_DAY_AND_CHAN_NAME) {
			if ($comparator == self::SORT_BY_NOTHING) {return;}
			$comp = @VdrEpgRequestFactory::$comparators[$comparator];
			if ($comp == null) {throw new Exception("unsupported comparator '{$comparator}' selected!");}
			Sorter::sort($entries, $comp);
		
		}
	
	}

	/** static ctor */
	VdrEpgRequestFactory::init();

?>
