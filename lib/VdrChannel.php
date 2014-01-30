<?php

	/**
	 * this class represents one channel stored within e.g. channels.conf
	 * and assigned to shows, timers, etc.
	 */
	class VdrChannel {
	
		/* attributes */
		private $code;				// http://www.vdr-wiki.de/wiki/index.php/Epg.data#C		 SIGNALSOURCE-NID-TID-SID
		private $name;				// the readable name of this channel
		private $index;				// the index within channels.conf

		private $sigSource;			// the signal source (e.g. Astra 19.2E)
		private $sid;				// the service-id;
		private $nid;				// the network-id;
		private $tid;				// the transponder-id;
		
		/**
		 * create a new channel from the given code (SIGNALSOURCE-NID-TID-SID)
		 * that has the given name, and (if known) has the given
		 * index within the channels.conf file
		 */
		public function __construct($code, $name, $index=-1) {
			$this->code = $code;
			$this->name = $name;
			$this->index = $index;
			$arr = explode('-', $code);
			$this->sigSource = $arr[0];
			$this->nid = $arr[1];
			$this->tid = $arr[2];
			$this->sid = $arr[3];
		}
		
		/** create a new channel parsing the provided SVDRP response-line */
		public static function getFromSvdrp($line) {
		
			$arr = explode(' ', $line, 2);
			$index = $arr[0];
			$data = explode(':', $arr[1]);
			$tmp = explode(';', $data[0]);
			
			// the name (remove everything starting at ',')
			$arr = explode(',', $tmp[0]);
			$name = $arr[0];
			
			$provider = $tmp[1];
			$sigSrc = $data[3];
			$sid = $data[9];
			$nid = $data[10];
			$tid = $data[11];
			$code = $sigSrc.'-'.$nid.'-'.$tid.'-'.$sid;
			
			// create the channel
			return new VdrChannel($code, $name, $index);
			
		}
		
		/** get the name of this channel */
		public function getName() {return $this->name;}
		
		/** get the code of this channel */
		public function getCode() {return $this->code;}
		
		/** get the index of this channel */
		public function getIndex() {return $this->index;}
		
		
		/** check if this channel and the provided channel can be used at the same time (same-source AND same transponder) */
		public function canBeUsedSimultaneously(VdrChannel $chan) {
			return ($this->sigSource == $chan->sigSource) && ($this->tid == $chan->tid);
		}
		
		
	}
	
?>