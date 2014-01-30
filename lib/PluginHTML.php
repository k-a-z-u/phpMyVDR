<?php
	
	/**
	 * a plugin that can produce HTML output
	 */
	interface PluginHTML extends Plugin {
		
		/**
		 * the plugin has been triggered
		 * and it must return it's html-output here
		 */
		public function getHTML();
		
	}

?>
