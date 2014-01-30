<?php

	/** the request returned a wrong code */
	class VdrSvdrpWrongResponseException extends MyException {
		public function __construct($codeExpected, $codeReceived) {
			$str = LANG_EX_SVDRP_WRONG_CODE;
			$str = str_replace('{EXP}', $codeExpected, $str);
			$str = str_replace('{RCV}', $codeReceived, $str);
			parent::__construct($str);
		}
	}
	
	/** the request failed */
	class VdrSvdrpFailedException extends MyException {
		public function __construct($msg) {
			$str = LANG_EX_SVDRP_FAILED;
			$str = str_replace('{MSG}', $msg, $str);
			parent::__construct($str);
		}
	}
	
	/** plugin not found */
	class VdrSvdrpPluginNotFoundException extends MyException {
		public function __construct($plugName) {
			$str = LANG_EX_SVDRP_PLUGIN_NOT_FOUND;
			$str = str_replace('{PLUG}', $plugName, $str);
			parent::__construct($str);
		}
	}
	

	class VdrSvdrpResponse {
	
		/* attributes */
		private $code;
		private $lineArray = array();
		private $lineStr = '';
		
		/** create new response with the given code */
		public function __construct($code) {
			$this->code = $code;
		}
	
		/** add a new line to this response (strips the result code) */
		public function addLine($line) {
			$line = substr($line, 4);
			$this->lineArray[] = $line;
			$this->lineStr .= $line . "\n";
		}		
	
		/** get the response code */
		public function getCode() {return $this->code;}
	
		/** assert that the code is as expected */
		public function assertCode($code) {
			if ($this->code == 550) {
				if (strpos($this->getLinesAsString(), 'Plugin') === 0) {
					$plug = $this->getLinesAsString();
					$plug = substr($plug, 8, strpos($plug, '"', 10) - 8);
					throw new VdrSvdrpPluginNotFoundException($plug);
				} else {
					throw new VdrSvdrpFailedException($this->getLinesAsString());
				}
			} else if ($this->code != $code) {
				throw new VdrSvdrpWrongResponseException($code, $this->code);
			}
		}
		
		/** return lines as array */
		public function getLinesAsArray() {
			return $this->lineArray;
		}
		
		/** return lines as string ("\n" separated!) */
		public function getLinesAsString() {
			return $this->lineStr;
		}
		
	}
	
	
?>