<?php

	//http://www.vdr-wiki.de/wiki/index.php/Epg.data#X

	class VDRStream {
	
		/* attributes */
		public $major;			// audio/video
		public $minor;			//ETSI EN 300 468  //http://www.google.de/url?sa=t&rct=j&q=&esrc=s&source=web&cd=1&ved=0CFkQFjAA&url=http%3A%2F%2Fwww.etsi.org%2Fdeliver%2Fetsi_en%2F300400_300499%2F300468%2F01.11.01_60%2Fen_300468v011101p.pdf&ei=QTLLT_u1CobetAbEm43LBg&usg=AFQjCNGbRf3XVzSij7AVrMiXwWkByWTomw
		private $language;		// audio/video language
		private $desc;			// descriptuion
		private $streamType;	// detailed streamtype
		
		/** create */
		public function __construct($major, $minor, $language, $desc) {
			$this->major = $major;
			$this->minor = $minor;
			$this->language = $language;
			$this->desc = $desc;
			$this->streamType = VdrStreamType::get($major, $minor);
		}
		
		
		/** is this a video stream? */
		public function isVideo() {return $this->major == 1 || $this->major == 5;}
		
		/** is this an audio stream? */
		public function isAudio() {return $this->major == 2 || $this->major == 4;}
		
		/** is subtitle stream? */
		public function isSubtitle() {return $this->major == 3;}
		
		/** get the detailed streamtype */
		public function getStreamType() {return $this->streamType;}
	
	}
	
?>