<?php

	/**
	 * this class represents the genre of a show
	 * //http://www.etsi.org/deliver/etsi_en/300400_300499/300468/01.11.01_60/en_300468v011101p.pdf
	 */
	class VdrEpgGenre {
	
		/* attributes */
		private $code;
		private static $majors = null;
		private static $minors = null;
		
		public function __construct($code) {
			$this->code = $code;
		}
		
		/** create only once */
		public static function init() {
			$arr = array();
			$arr[0x1] = LANG_GENRE_MOVIE;
			$arr[0x2] = LANG_GENRE_NEWS;
			$arr[0x3] = LANG_GENRE_SHOW;
			$arr[0x4] = LANG_GENRE_SPORTS;
			$arr[0x5] = LANG_GENRE_CHILDREN;
			$arr[0x6] = LANG_GENRE_MUSIC;
			$arr[0x7] = LANG_GENRE_CULTURE;
			$arr[0x8] = LANG_GENRE_POLITICAL;
			$arr[0x9] = LANG_GENRE_SCIENCE;
			$arr[0xA] = LANG_GENRE_LEISURE;
			$arr[0xB] = LANG_GENRE_SPECIAL;
			VdrEpgGenre::$majors = $arr;
			
			$arr = array();
			VdrEpgGenre::fill($arr, 0x10, 0, 8, 'LANG_GENRE_MOVIE');
			VdrEpgGenre::fill($arr, 0x20, 0, 4, 'LANG_GENRE_NEWS');
			VdrEpgGenre::fill($arr, 0x30, 0, 3, 'LANG_GENRE_SHOW');
			VdrEpgGenre::fill($arr, 0x40, 0, 11, 'LANG_GENRE_SPORTS');
			VdrEpgGenre::fill($arr, 0x50, 0, 5, 'LANG_GENRE_CHILDREN');
			VdrEpgGenre::fill($arr, 0x60, 0, 6, 'LANG_GENRE_MUSIC');
			VdrEpgGenre::fill($arr, 0x70, 0, 11, 'LANG_GENRE_CULTURE');
			VdrEpgGenre::fill($arr, 0x80, 0, 3, 'LANG_GENRE_POLITICAL');
			VdrEpgGenre::fill($arr, 0x90, 0, 7, 'LANG_GENRE_SCIENCE');
			VdrEpgGenre::fill($arr, 0xA0, 0, 7, 'LANG_GENRE_LEISURE');
			VdrEpgGenre::fill($arr, 0xB0, 0, 4, 'LANG_GENRE_SPECIAL');
			
			
			VdrEpgGenre::$minors = $arr;
			
		}

		/** fill the array */
		private static function fill(array &$arr, $offset, $start, $end, $const) {
			for ($i = $start; $i <= $end; ++$i) {
				$arr[$offset + $i] = constant($const . '_' . $i);
			}
		}
	
		/** return then one-byte-code of the genre as provided via EPG-data */
		public function getCode() {return $this->code;}
	
		/** get the major genre (movie, news, children, ..) */
		public function getMajor() {
			$pos = ($this->code) >> 4;
			return @VdrEpgGenre::$majors[$pos];
		}
		
		
		/** get the minor genre (=details) */
		public function getMinor() {
			$pos = ($this->code);
			return @VdrEpgGenre::$minors[$pos];
		}
	
	}

	/** static construction */
	VdrEpgGenre::init();
	
?>