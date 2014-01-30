<?php

	/**
	 * all plugins will register themselves within this plugin system.
	 * plugins will then be notified when several events occur
	 */
	class PluginSystem {
		
		/* attributes */
		private $events;
		private $outputEvents;
		private static $instance = null;
		
		/** hidden ctor */
		private function __construct() {;}
		
		/** singleton access */
		public static function get() {
			if (self::$instance == null) {self::$instance = new PluginSystem();}
			return self::$instance;
		}
		
		/** register for an output event */
		public function registerOutputEvent($event, PluginHTML $plug) {
			
			// attach to list of output events
			$this->outputEvents[$event][] = $plug;
			
		}
		
		/**
		 * trigger the given output event and return the gathered HTML-data as array
		 * (one entry per plugin)
		 */
		public function getFromOutputEvent($event) {
			
			if ( !is_array($this->outputEvents[$event]) ) {return;}
			
			$ret = array();
			foreach ($this->outputEvents[$event] as $plugin) {
				$ret[] = $plugin->getHTML();
			}
			return $ret;
			
		}
		
		
		/** register the given plugin for the provided event */
		public function registerForEvent($evtName, PluginEvent $plugin) {
			$this->events[$evtName][] = $plugin;
		}
		
		/** trigger event with this name and inform all registered listeners */
		public function triggerEvent($evtName, $evtSrc) {
			if ( !is_array($this->events[$evtName]) ) {return;}
			foreach($this->events[$evtName] as $plugin) {
				$plugin->onEvent($evtName, $evtSrc);
			}
		}
		
		/** this will load all plugins and call their register() method */
		public function loadPlugins() {
			
			// plugin directory
			$plugDir = __DIR__ . '/../plugins/';
			
			// load plugin configuration
			$cfg = file_get_contents($plugDir . 'plugins.cfg');
			
			// load all plugins
			$lines = explode("\n", $cfg);
			foreach ($lines as $line) {
				
				// line cleanup
				$line = trim($line);
				
				// skip comments and empty lines
				if (empty($line))		{continue;}
				if (@$line[0] == '#')	{continue;}
				
				// split plugin and arguments
				$plug = explode(':', $line, 2);
				
				// get filename and classname
				$plugName = trim($plug[0]);
				$pluginFile = $plugDir . $plugName . '/' . $plugName . '.php';
				$pluginClass = $plugName;
				
				// attributes for this plugin
				$attrs = @$plug[1] ? explode(',', trim($plug[1])) : array();
				
				// include the plugin's file once
				// (plugins may be added multiple times! (e.g. for the dashboard)
				require_once $pluginFile;
		
				
				// instantiate class
				$p = new $pluginClass;
				$p->onLoad($this, $attrs);
					
			}
	
		}
		
	}

?>
