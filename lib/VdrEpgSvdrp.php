<?php

	/** retrieve EPG information from VDR via SVDRP*/
	class VdrEpgSvdrp {
	
		/** attributes */
		private $vdrConn;
	
		/** create */
		public function __construct(VdrSvdrpConnection $vdrConn) {
			$this->vdrConn = $vdrConn;
		}
	
		/** get EPG data */
		public function get($when) {
			
			// read $when and assert the correct code
			$resp = $this->vdrConn->request("LSTE {$when}");
			$resp->assertCode(215);
			
			// get result as line-array and parse it
			$lines = $resp->getLinesAsArray();
			return VdrEpgParser::parseLineArray($lines);
			
		}
	
	}

?>