<?php

	/**
	 * date formatting
	 */
	class MyDate {
		
		public static function getDate($ts) {
			return strftime(LANG_DATE_DATE, $ts);
		}
		
		public static function getTime($ts) {
			return strftime(LANG_DATE_TIME, $ts);
		}
		
		public static function getHourMinute($ts) {
			return strftime(LANG_DATE_HOURMINUTE, $ts);
		}
		
		public static function getDateTime($ts) {
			return strftime(LANG_DATE_DATETIME, $ts);
		}
		
		/** get the current hour */
		public static function getHour($ts = null) {
			if ($ts == null) {$ts = time();}
			return date('H', $ts);
		}
		
		/** get the current minute */
		public static function getMinute($ts = null) {
			if ($ts == null) {$ts = time();}
			return date('i', $ts);
		}
		
		/** get the current day of month */
		public static function getDayOfMonth($ts = null) {
			if ($ts == null) {$ts = time();}
			return date('j', $ts);
		}
		
		/** get the current day of week (1 = monday, 7 = sunday) */
		public static function getDayOfWeek($ts = null) {
			if ($ts == null) {$ts = time();}
			return date('N', $ts);
		}
		
		/** get the current month (01 - 12) */
		public static function getMonth($ts = null) {
			if ($ts == null) {$ts = time();}
			return date('m', $ts);
		}
		
		/** get the current year */
		public static function getYear($ts = null) {
			if ($ts == null) {$ts = time();}
			return date('Y', $ts);
		}
		
//		/** get current day of month */
//		public static function getDayOfMonth($ts = null) {
//		if ($ts == null) {$ts = time();}
//			return date('j', $ts);
//		}
		
		/** get name of day by index */
		public static function getDayName($index) {
			$names = LANG_DATE_DAYNAMES;
			$names = explode(',', $names);
			return $names[$index];
		}
		
		/** get short name of day by index */
		public static function getDayNameShort($index) {
			$names = LANG_DATE_DAYNAMES_SHORT;
			$names = explode(',', $names);
			return $names[$index];
		}
		
		/**
		 * get the next-possible timestamp having the given (int) weekday and hour:minute
		 * 0 = sunday, 6 = saturday
		 */
		public static function getNextTs($hour, $minute, $weekday) {
			$curDate = getDate(time()+86400);
			$curWeekday = $curDate['wday'];
			$diff = $weekday - $curWeekday;
			$diff = ($diff < 0) ? ($diff+7) : ($diff);
			$add = $diff * 60*60*24;
			return mktime($hour, $minute, 0) + $add;
		}
	
	}