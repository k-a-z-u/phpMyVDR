<?php

	class VdrStreamAudio {
	
		/* attributes */
		private $codec;
		private $channels;
	
		/** create */
		public function __construct($codec, $channels) {
			$this->codec = $codec;
			$this->channels = $channels;
		}
	
		/** get the codec */
		public function getCodec() {return $this->codec;}
	
	}
	
?>