<?php

	/**
	 * this class provides access to all channels using SVDRP
	 */
	class VdrChannelSvdrp {
	
		/** attributes */
		private $vdrConn;
	
		/** create */
		public function __construct(VdrSvdrpConnection $vdrConn) {
			$this->vdrConn = $vdrConn;
		}
	
		/** get all channels via SVDRP */
		public function getAll() {
			
			// read $when and assert the correct code
			$resp = $this->vdrConn->request('LSTC');
			$resp->assertCode(250);
			
			// get result as line-array and parse it
			$lines = $resp->getLinesAsArray();
			
			// parse all
			$ret = array();
			foreach ($lines as $line) {
				$ret[] = VdrChannel::getFromSvdrp($line);
			}
			
			// return all channels
			return $ret;
			
		}
	
	}

?>