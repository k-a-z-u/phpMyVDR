<?php

	/**
	 * own class for exceptions using language constants
	 */
	class MyException extends Exception {
		
		public function __construct($msg) {
			parent::__construct($msg, 0);
		}
		
//		/* attributes */
//		private $const;
//	
//		/** create */
//		public function __construct($const) {
//			$this->const = $const;
//		}
//		
//		/** return the message behind the constant's name */
//		public function getConst() {return $this->const;}

	}
	
?>