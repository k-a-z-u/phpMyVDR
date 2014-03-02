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
			
			return self::getByParams($db, $params);
		
		}
	
		public static function getByParams(VdrEpgSqlite $db, VdrEpgRequestFactoryParams $params) {
		
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
