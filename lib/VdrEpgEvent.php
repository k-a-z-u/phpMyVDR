<?php

	//http://www.vdr-wiki.de/wiki/index.php/Epg.data#E

	class VdrEpgEvent {
	
		/* attributes */
		private $eventID;			// uniqute event ID
		private $startTS;			// start timestamp
		private $endTS;				// end timestamp
		private $duration;			// duration in seconds
		private $tableID;			// the table ID
		private $version;			// the version 
			
		/** create */
		public function __construct($eventID, $startTS, $endTS, $tableID = null, $version = null) {
			$this->eventID = (int) $eventID;
			$this->startTS = (int) $startTS;
			$this->endTS = (int) $endTS;
			if ($this->endTS < $this->startTS) {throw new Exception("invalid end timestamp given");}
			$this->duration = $this->endTS - $this->startTS;
			$this->tableID = $tableID;
			$this->version = $version;
		}
		
		/** get the ID of this event */
		public function getID() {return $this->eventID;}
		
		/** get start timestamp */
		public function getTsStart() {return $this->startTS;}
		
		/** get stop timestamp */
		public function getTsEnd() {return $this->endTS;}
	
		/** get the duration in seconds */
		public function getDuration() {return $this->duration;}
	
		
		/** get the day of year this event takes place */
		public function getDayOfYear() {return date('z', $this->startTS);}
	
	}
	
?>
