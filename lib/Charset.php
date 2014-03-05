<?php

	/**
	 * contains some charset helper methods for a global access
	 */
	class Charset {
	
		/** convert given string to UTF8 */
		public static function toUTF8($str) {
			$lst = 'UTF-8, ISO-8859-1';
			$cur = mb_detect_encoding($str, $lst);
			return mb_convert_encoding($str, 'UTF-8', $cur);
		}
	
	}
	
?>
