<?php

	/** this interface provides filtering (selection) of VdrEpgEntries */
	interface VdrEpgFilter {
		
		public function filter(array $entries);
		
		public static function getStringFilter($str) {
			return new VdrEpgFilterText($str);
		}
		
	}
	
	class VdrEpgFilterText implements VdrEpgFilter {
		
		/* attributes */
		private $searchString;
		
		public function __construct($searchString) {
			$this->searchString = $searchString;
		}
		
		public function filter(array $entries) {
			$ret = array();
			$ret = entries;
		}
		
	}


?>