<?php

	class System {
	
		/** get the hostname or ip of this machine */
		public static function getHostname() {
			return gethostname();
		}
	
	}

?>