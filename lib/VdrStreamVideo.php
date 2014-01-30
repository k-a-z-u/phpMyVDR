<?php

	class VdrStreamVideo {
	
		/* attributes */
		private $codec;
		private $hz;
		private $aspect;
	
		/** create */
		public function __construct($codec, $aspect, $hz) {
			$this->codec = $codec;
			$this->hz = $hz;
			$this->aspect = $aspect;
		}
	
		/** get the codec */
		public function getCodec() {return $this->codec;}
	
	}
	
?>