<?php
	
	/** retrieve Femon information from VDR */
	class VdrFemon {
		
		/** attributes */
		private $vdrConn;
	
		/** create */
		public function __construct(VdrSvdrpConnection $vdrConn) {
			$this->vdrConn = $vdrConn;
		}
	
		/** get Femon data */
		public function get() {
			
			// read "now"
			$resp = $this->vdrConn->request('PLUG femon INFO');
			$resp->assertCode(900);
			return $this->parse($resp);
			
		}
		
		/** parse an EPG response */
		private function parse($resp) {
		
			$ret = array();
			$lines = $resp->getLinesAsArray();
			$sig = 0;
			$noise = 0;
			$card = '';
			
			// read data for all cards
			for ($i = 0; $i < count($lines); ++$i) {
				$line = $lines[$i];
				$key = substr($line, 0, 4);
				$val = substr($line, 5);
				switch ($key) {
					case "NAME": $card = $val; break;
					case "SGNL": $sig = hexdec($val); break;
					case "SNRA": $noise = hexdec($val); break;
					case "CHAN": $ret[] = new VdrFemonResult($card, $sig, $noise);
				}
			}
			
			// return all results
			return $ret;
			
		}
	
	}

?>