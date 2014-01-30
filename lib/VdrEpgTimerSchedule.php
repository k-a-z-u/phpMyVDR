<?php

	/**
	 * one schedule entry of a timer (e.g. channel + show-name + time + duration)
	 */
	class VdrEpgTimerSchedule {
		
		/* attributes */
		private $channel;
		private $title;
		private $startTS;
		private $duration;
		
		/** create */
		public function __construct(VdrChannel $channel, $title, $startTS, $duration) {
			$this->channel = $channel;
			$this->title = $title;
			$this->startTS = $startTS;
			$this->duration = $duration;
		}
		
		/** returns true if both events overlap by recording time */
		public function overlapsWith(VdrEpgTimerSchedule $other) {
			if ($this->startTS < $other->startTS) {$a = $this; $b = $other;} else {$a = $other; $b = $this;}
			return ($a->startTS + $a->duration > $b->startTS);
		}
		
		/** get the starting timestamp */
		public function getTsStart() {return $this->startTS;}
		
		/** get the duration */
		public function getDuration() {return $this->duration;}
		
		/** get the title */
		public function getTitle() {return $this->title;}
		
		/** get the channel */
		public function getChannel() {return $this->channel;}
		
	}
	
?>