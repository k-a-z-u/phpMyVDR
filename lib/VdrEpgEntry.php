<?php

	/** an EPG entry */
	class VdrEpgEntry {
	
		/* attributes */
		private $id = -1;						// only used if retrieved via database
		private $channel;
		private $event;
		private $streams = array();
		private $title;
		private $descShort;
		private $descLong;
		private $genre;
		
		
		
		/** construction only via static methods! */
		private function __construct() {;}
		
		/** parse and create from epg.data strings */
		public static function getFromEpgDataStrings($channel, $event, $title, $descShort, $descLong, $genreHex, array $streams) {

			$entry = new VdrEpgEntry();
		
			$attr = explode(' ', $channel, 2);
			$entry->channel = new VdrChannel($attr[0], $attr[1]);
		
			$attr = explode(' ', $event, 5);
			$entry->event = new VdrEpgEvent($attr[0], $attr[1], $attr[2], $attr[3], $attr[4]);
			
			$numStreams = count($streams);
			for ($i = 0; $i < $numStreams; ++$i) {
				$attr = explode(' ', $streams[$i], 4);
				$entry->streams[] = new VdrStream($attr[0], $attr[1], $attr[2], @$attr[3]);
			}
		
			$entry->title = $title;
			$entry->descShort = $descShort;
			$entry->descLong = str_replace('|', "\n", $descLong);
			$entry->genre = new VdrEpgGenre(hexdec($genreHex));
			
			return $entry;

		}
		
		/** directly create using the provided objects */
		public static function getFromDB($id, $title, $descShort, $descLong, $genreInt, $channel, $event, array $streams = null) {
			$entry = new VdrEpgEntry();
			$entry->id = $id;
			$entry->channel = $channel;
			$entry->event = $event;
			$entry->title = $title;
			$entry->descShort = $descShort;
			$entry->descLong = $descLong;
			$entry->genre = new VdrEpgGenre($genreInt);
			return $entry;
		}
		

		/** get the database-id of this entry (only used if retrieved from database! */
		public function getDbId() {return $this->id;}
	
		
		/** get the title */
		public function getTitle() {return $this->title;}
		
		/** get the short-desc */
		public function getDescShort() {return $this->descShort;}
		
		/** get the long-desc */
		public function getDescLong() {return $this->descLong;}
		
		
		/** get the channel */
		public function getChannel() {return $this->channel;}
	
		/** get the streams */
		public function getStreams() {return $this->streams;}
	
		/** get the event */
		public function getEvent() {return $this->event;}
		
		/** get the genre */
		public function getGenre() {return $this->genre;}
	
		
		/** check if this transmission has AC3 stream */
		public function hasAC3() {
			for ($i = 0; $i < count($this->streams); ++$i) {
				$stream = $this->streams[$i];
				$type = $stream->getStreamType();
				if (!$type || !$stream->isAudio()) {continue;}
				if ($type->getCodec() == Codec::AC3) {return true;}
			}
			return false;
		}
	

	
		/** check if this transmission has AC3 stream */
		public function isHD() {
			//if (++$this->cnt == 10) {die();}
			for ($i = 0; $i < count($this->streams); ++$i) {
				$stream = $this->streams[$i];
				$type = $stream->getStreamType();
				if (!$type || !$stream->isVideo()) {continue;}
				if ($type->getCodec() == Codec::MPEG2_HD) {return true;}
				if ($type->getCodec() == Codec::H264_HD) {return true;}
			}
			return false;
		}
	
	
		/** get an info-box for the given entry */
		public function getInfoBox($includeDesc = false) {
		
			$start = $this->getEvent()->getTsStart();
			$end = $this->getEvent()->getTsEnd();
			$duration = intval ($this->getEvent()->getDuration() / 60);
			
			
			$infoBox  = '';
			$infoBox .= HTML::getSafe($this->getTitle()) . '&#13;';
			$infoBox .= MyDate::getHourMinute($start) . ' - ' . MyDate::getHourMinute($end) . ' (' . $duration . ' ' . LANG_DATE_MINUTES . ')';
			
			if ($end < time()) {
				// already over
				
			} else if ($start <= time()) {
				// currently running?
				$running = intval((time() - $start) / 60);
				$percent = 0;
				if ($duration) {
					$percent = intval($running * 100 / $duration);
				}
				$infoBox .= '&#13;' . LANG_TIMELINE_RUNNING_SINCE . ': ' . $running . ' ' . LANG_DATE_MINUTES . ' (' . $percent . '%)';
				
			} else {
				// starting later
				$start = intval(($start - time()) / 60);
				$infoBox .= '&#13;' . LANG_TIMELINE_STARTING_IN . ': ' . $start . ' ' . LANG_DATE_MINUTES;
				
			}
			
			// include long description of this show?
			if ($includeDesc) {
				$infoBox .= '&#13;' . $this->getDescShort();
			}
						
			return $infoBox;
			
		}
	
	
	
	}
	
?>
