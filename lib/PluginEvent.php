<?php
	
	interface PluginEvent extends Plugin {
			
		/** and event named evtName has been triggered by evtSrc */
		public function onEvent($evtName, $evtSrc);
		
	}

?>
