<?php

	/**
	 * this class contains additional HTML methods that are specific for this project
	 */
	class MyHTML {
	
		/** this will return the channels name, and (if found) an image */
		public static function getChannelIcon($name) {
			$file = 'tvicons/' . Icons::get()->getImgForChannel($name); //MyHTML::getChannelIconFile($name);
			if (is_file($file)) {return HTML::getImage($file, $name, $name);}
			return '';
		}
		
		/** this will return the channels name, and (if found) an image */
		public static function getChannelIconFile($name) {
		
			$file = Icons::get()->getImgForChannel($name);
			if ($file) {return 'tvicons/' . $file;}
			return '';
		
		/*
			// remove special characters and make lowercase
			$fname = str_replace('/', '', $name);
			$fname = strtolower($fname);
			
			$file = 'tvicons/' . $fname . '.png';
			if (is_file($file)) {return $file;}
			$file = 'tvicons/' . str_replace(' hd', '', $fname) . '.png';
			if (is_file($file)) {return $file;}
			$file = 'tvicons/' . str_replace(' sd', '', $fname) . '.png';
			if (is_file($file)) {return $file;}
			return '';
		*/
		}
	
		/** get start/stop time and progress */
		public static function getPlayTime(VdrEpgEntry $entry, $barOnly = false, $includeDate = false) {
		
			$curTime = time();
		
			if ($entry->getEvent()) {
				
				$running = $curTime - $entry->getEvent()->getTsStart();
				$duration = $entry->getEvent()->getDuration();
				
				$dateFormat = ($includeDate) ? (LANG_DATE_SHOW_DATETIME) : (LANG_DATE_SHOW_TIME);			
				$str  = date($dateFormat, $entry->getEvent()->getTsStart()) . " - ";
				$str .= date(LANG_DATE_SHOW_TIME, $entry->getEvent()->getTsEnd());
				
				$htmlTitle = $entry->getInfoBox();
				
				if ($running >= 0) {
					$percent = ($duration != 0) ? ($running * 100 / $duration) : (0);
					$bar = HTML::getProgressBar((int) $percent, 'bar0', '&nbsp;', $htmlTitle);
					return $bar . ((!$barOnly) ? ($str) : (''));
				} else {
					$percent = 0;
					return $str;
				}	
			}
			
			return '';
			
		}
	
	}
	
	
?>
