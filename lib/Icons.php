<?php

	/**
	 * handles (channel) icons stored in files
	 */
	 
	class Icons {
	
		/* attributes */
		private $mapChannelIcons;
	
		/** constructor */
		private function __construct() {
			$this->loadMaps();
		}
		
		
		/** load all maps */
		private function loadMaps() {
			$this->mapChannelIcons = $this->loadMap(__DIR__ . '/../tvicons/iconToChannel.dat');
		}
		
		
		/** load map-data from file */
		private function loadMap($file) {
			$map = array();
			$content = file_get_contents($file);
			$content = str_replace("\r", "", $content);
			$arr = explode("\n", $content);
			
			foreach ($arr as $line) {
			
				// skip empty lines
				if (!$line) {continue;}
				$dat = explode(':', $line);
				$img = $dat[0];
				$channels = explode(';', $dat[1]);
				foreach ($channels as $channel) {
					$map[strtolower($channel)] = $img;
				}
			}
			return $map;
		}
		
		
		/** get image for channel */
		public function getImgForChannel($name) {
			return @$this->mapChannelIcons[strtolower($name)];
		}
		
		
		
		
		/** singleton */
		private static $instance = null;
		
		/** singleton */
		public static function get() {
			if (Icons::$instance == null) {Icons::$instance = new Icons();}
			return Icons::$instance;
		}
	
	}

?>