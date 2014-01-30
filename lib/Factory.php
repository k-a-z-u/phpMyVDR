<?php

	class Factory {
		
		/** attributes */
		private static $db;
		private static $vdrConn;
		private static $page;
		
		/** get the connection to the VDR */
		public static function getVdrConnection() {
			if (self::$vdrConn == null) {
				self::$vdrConn = new VdrSvdrpConnection(VDR_SRV_IP, VDR_SRV_PORT);
			}
			return self::$vdrConn;
		}
		
		/** get the sqlite DB */
		public static function getSqlite() {
			if (Factory::$db == null) {
				$file = dirname(__FILE__) . '/../' . SQLITE_EPG_FILE;
				Factory::$db = new VdrEpgSqlite($file);
			}
			return Factory::$db;
		}
		
		
		/** get the main page */
		public static function getPage() {
			if (self::$page == null) {
				self::$page = new Page();
			}
			return self::$page;
		}
	
	}

?>
