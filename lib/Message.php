<?php

	/**
	 * display messages
	 */
	class Message {
	
		public static function showError($txt) {
			$msg = urlencode($txt);
			header("location:?page=Message&type=error&txt={$msg}");
		}
	
		public static function showInfo($txt) {
			$msg = urlencode($txt);
			header("location:?page=Message&type=info&txt={$msg}");
		}
	
	}
	
?>