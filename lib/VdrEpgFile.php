<?php

	/**
	 * instead of SVDRP this class directly accesses the epg.data
	 * for full featured data retreival
	 */
	class VdrEpgFile {
	
		/* attributes */
		private $entries = null;
		private $handle;
		private $curChannelStr;
		private $cnt = 0;
		
		
		/** create for the given file */
		public function __construct($file) {
			$this->open($file);
		}
		
		/** parse the EPG file */
		private function open($file) {
		
			// open the file
			$this->handle = @fopen($file, 'r');
			if (!$this->handle) {throw new Exception("could not open file {$file}");}
			
		}
	
		/** get the current file-position in percent */
		public function getPercent() {
			$curPos = ftell($this->handle);
			$stats = fstat($this->handle);
			$length = $stats['size'];
			return $curPos * 100 / $length;
		}
	
		/** get the entries */
		public function getNext() {
		
			$buf = '';
			
			while (true) {
			
				// read next line
				$line = fgets($this->handle, 4096);
								
				// check for end of file
				if ($line === false) {
					fclose($this->handle);
					return null;
				}
								
				// check what this line means
				$type = $line{0};
				
				if ($type == 'C') {
						$this->curChannelStr = $line;
				} else if ($type == 'e') {
						return VdrEpgParser::parseOneEntryString($this->curChannelStr . $buf . 'e');
				} else {
						$buf .= $line;
				}
								
			
			}
		
		}
	
	}

?>