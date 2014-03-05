<?php

	/**
	 * this class handles a direct connection the VDR
	 * server using its SVDRP protocol.
	 */
	class VdrSvdrpConnection {
	
		/** attributes */
		private $sock;
	
		/** create an new (persistent?) SVDRP connection */
		public function __construct($host, $port, $persist = false) {
			$this->create($host, $port, $persist);
		}
		
		/** create and open connection */
		private function create($host, $port, $persist) {

			// create the socket
			//$this->sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
			$this->sock = fsockopen($host, $port, $errno, $errstr);
			if (!$this->sock) {
				throw new Exception('error while connecting to VDR server '.$host.':'.$port.' via SVDRP: ' . $errstr . ' (code ' . $errno . ')');
			}
			
			// bind to host:port
			//if (socket_connect($this->sock, $host, $port) === false) {
			//	throw new Exception(socket_strerror(socket_last_error($this->sock)));
			//}
			
			// read hello and check code
			$resp = $this->readResponse();
			$resp->assertCode('220');
			
		}
		
		/** read the (multiline) response from the server */
		private function readResponse() {
			$resp = null;
			while(true) {
			
				// read one line (until "\r" => "\n" must be stripped!");
				//$line = socket_read($this->sock, 2048, PHP_NORMAL_READ);
				$line = fgets($this->sock, 2048);
				//$line = utf8_decode($line);
				if (!$line) {return $resp;}
				
				// trim the line (remove "\n") and skip empty lines
				$line = trim($line);
				if (empty($line)) {continue;}
				
				// create response (once)
				if ($resp == null) {$resp = new VdrSvdrpResponse(substr($line, 0, 3));}
				
				// add line to response
				$resp->addLine($line);								// append line without response code
				if (@$line{3} == ' ') {return $resp;}				// end of (multiline) response?
			}
					
			return $resp;
		
		}
		

		/** request response to one command */
		public function request($cmd) {
		
			// send command and ensure it is converted as UTF8
			// (however: all data SHOULD already use UTF8 as charset...)
			$cmd .= "\r\n";
			$cmd = Charset::toUTF8($cmd);
			
			//socket_write($this->sock, $cmd, strlen($cmd));
			fputs($this->sock, $cmd, strlen($cmd));
		
			// read response
			return $this->readResponse();
						
		}
		
	}
	
?>
