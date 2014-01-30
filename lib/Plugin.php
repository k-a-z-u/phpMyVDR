<?php


	/**
	 * interface for all plugins
	 */
	interface Plugin {
		
		/**
		 * method will be called when the plugin is loaded by the PluginSystem.
		 * every plugin may register itself for all desired events.
		 * @param PluginSystem $sys the PluginSystem that loaded the plugin
		 * @param array $attrs the attributes set for this plugin
		 */
		public function onLoad(PluginSystem $sys, array $attrs);
			
	}

?>
