<?php

	/**
	 * this class provides access to VDR's "epg.data" file which contains
	 * all retrieved EPG entries.
	 * 
	 * to enhance the system's performance and to provide fast searches,
	 * the "epg.data" is just parsed and inserted into a (sqlite) database.
	 * 
	 * the "epg.data" should use UTF-8 as character encoding.
	 * 
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
								
				// check the data-type for this line
				// (each line is preceded by a character indicating its 'type')
				$type = $line{0};
				
				if ($type == 'C') {
					// channel info (all following entries belong to this channel!)
					$this->curChannelStr = $line;
					
				} else if ($type == 'e') {
					// EPG entry (for the current channel) complete
					// the buffer $buf holds the whole data for this entry
					return VdrEpgParser::parseOneEntryString($this->curChannelStr . $buf . 'e');
				
				} else {
					$buf .= $line;
				
				}
				
			
			}
		
		}
	
	}

?>
