<?php

	/**
	 * provides channel-filtering by using pre-defined user-lists.
	 * the user can e.g. provide a list of channels he watches most
	 * and thus has a quick overview for them
	 */
	class ChannelFilterUserlist implements ChannelFilter {
	 
		/* attributes */
		private $selectedChannels;
		private static $PATH;
		
		/** static ctor */
		public static function init() {
			ChannelFilterUserlist::$PATH = realpath(__DIR__) . '/../filter/channellist/';
		}
	 
		/** ctor */
		public function __construct($file) {
			$this->selectedChannels = file_get_contents(ChannelFilterUserlist::$PATH . $file);
		}
		
		/** filter the provided entries */
		public function getFiltered(array $entries) {
		
			$out = array();
		
			// filter all entries
			foreach ($entries as $entry) {
				$channel = $entry->getChannel()->getName();
				if (strpos($this->selectedChannels, $channel) !== false) {
					$out[] = $entry;
				}
			}
			
			return $out;
			
		}
		
		/** check if the provided entry matches */
		public function matches($entry) {
			$channel = $entry->getChannel()->getName();
			//return strcasecmp($this->selectedChannels, $channel) == 0;
			return (stripos($this->selectedChannels, $channel) !== false);
		}
		
		/**
		 * this method returns a list of all available user-defined filters
		 * = all files within a given directory
		 */
		public static function getAvailableFilters() {
		
			$handle = opendir(ChannelFilterUserlist::$PATH);
			$ret = array();
			
			while ($file = readdir($handle)) {
				if ($file == "." || $file == "..") {continue;}
				if ( substr($file, strlen($file)-4) != '.lst') {continue;}
				$ret[] = $file;
			}
			
			return $ret;
			
		}
	 
	}

	
	/** static ctor */
	ChannelFilterUserlist::init();

?>
