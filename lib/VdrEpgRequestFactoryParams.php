<?php


/**
 * describes parameters for a to-be-executed search-request.
 * those params may provide e.g. a sorting-order, a time region
 * to search for, etc..
 */
class VdrEpgRequestFactoryParams {
	
	const GET_BY_NULL = 0;
	const GET_BY_STRING	= 1;
	const GET_BY_CHANNEL = 2;
	const GET_BY_TIME = 4;
	const GET_BY_DURATION = 8;
	
	const SEARCH_TIME_NULL = 0;
	const SEARCH_TIME_NOW = 1;
	const SEARCH_TIME_NEXT = 2;
	
	
	/**
	 * convert the given starting point (e.g. SEARCH_TIME_NOW, SEARCH_TIME_NEXT, '2015')
	 * to a real timestamp
	 */
	public function getStartTS() {
		if		(empty($this->searchTime))						{return time();}						// not set? -> now!
		else if	($this->searchTime == self::SEARCH_TIME_NOW)	{return time();}						// now
		else if	($this->searchTime == self::SEARCH_TIME_NEXT)	{return time();}						// now + modified search (later)
		else if	($this->searchTime == '2015')					{return self::getTsToday(20, 15);}		// 20:15 on the current day
		else if	($this->searchTime == '2200')					{return self::getTsToday(22, 00);}		// 22:00 on the current day
		else													{return $this->searchTime;}				// is already a valid timestamp
	}
	
	public function getSearchTime() {
		return $this->searchTime;
	}
	
	/**
	 * modify the search-string (if any):
	 * if it starts with '^', do not perform wildcard-search before the search string
	 * if it ends with '$', do not perform wildcard-search after the serach string
	 */
	public function getSearchStr() {
		if (empty($this->searchStr)) {return null;}
		$searchStr = $this->searchStr;
		$firstChar = $searchStr[0];
		$lastChar = $searchStr[strlen($searchStr) - 1];
		$searchStr = ($firstChar != '^')	? ('%' . $searchStr) : (substr($searchStr, 1));
		$searchStr = ($lastChar != '$')		? ($searchStr . '%') : (substr($searchStr, 0, strlen($searchStr) - 1));
		return $searchStr;
	}
	
	/** what is the searching base? a string? a channel? etc.. */
	public function getBy() {return $this->getBy;}
	
	/** get the comparator used for sorting */
	public function getComparator() {return $this->sorting;}

	/** get the channel to search for */
	public function getChannel() {return $this->channel;}
	
	/** get the searching duration (e.g. 2 hours (since hh:mm)) */
	public function getSearchDuration() {return $this->searchDuration;}
	
	/** get the requested channel filter list (if any) */
	public function getChannelFilterList() {return $this->channelFilerList;}
	
	
	
	/** use a channel-filter-list or not? */
	public function useChannelFilterList() {return $this->channelFilerList != null;}
	
	
	
	
	/** get all entries matching the given string */
	public function setSearchString($str) {
		if (!isset($str)) {return;}
		$this->searchStr = $str;
		$this->getBy = self::GET_BY_STRING;
	}
	
	/** get all entries by time (e.g. SEARCH_TIME_NOW, SEARCH_TIME_NEXT, '2015') */
	public function setSearchTime($time) {
		if (!isset($time)) {return;}
		$this->searchTime = $time;
		$this->getBy = self::GET_BY_TIME;
	}
	
	/** set the channel to search for */
	public function setSearchChannel($channelStr) {
		if (!isset($channelStr)) {return;}
		$this->channel = $channelStr;
		$this->getBy = self::GET_BY_CHANNEL;
	}
	
	/** set the duration to search for (since starting time. e.g. 20:15 - 22:00) */
	public function setSearchDuration($duration) {
		if (!isset($duration)) {return;}
		$this->searchDuration = $duration;
		$this->getBy = self::GET_BY_DURATION;
	}
	
	/** set the channel-filter-list to use */
	public function setChannelFilterList($list) {
		if (!isset($list)) {return;}
		$this->channelFilerList = $list;
	}
	
	/** select the sorting algorithm.. one of SORT_BY_XXX */
	public function sortBy($const) {
		$this->sorting = $const;
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
	
	
	
	
		/** select the sorting order (if any) */
	private $sorting = VdrEpgRequestFactory::SORT_BY_DAY_AND_CHAN_NAME;
	
	/** select the starting time for the search (e.g. 'now', 'next', '2015', ...) */
	private $searchTime = self::SEARCH_TIME_NULL;
	
	/** search for a specific textual content */
	private $searchString = null;
	
	/** set the channel to search for */
	private $searchChannel = null;
	
	/** search for a given time region? */
	private $searchDuration = 0;

	/** use a channel filter list? */
	private $channelFilerList = null;
	
	/** searching base */
	private $getBy = self::GET_BY_NULL;
	
}


?>
