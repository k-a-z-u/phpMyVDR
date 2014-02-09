<?php

	/**
	 * displays a box within dashboard containing currently 
	 * shows for requested channels
	 */
	class UpNow implements PluginHTML {
	
		/* attributes */
		private $channels;
		
	
		/** @Override */
		public function getHTML() {
		
			// now/next
			$box = "";
			$tpl = new Template('plug_upnow_entry');
			$db = Factory::getSqlite();
			$params = new VdrEpgRequestFactoryParams();
			$params->sortBy( VdrEpgRequestFactory::SORT_BY_CHAN_NAME );
			$params->setSearchTime( VdrEpgRequestFactoryParams::SEARCH_TIME_NOW );
			$res = VdrEpgRequestFactory::getByParams($db, $params);
			
			foreach ($res as $entry) {
				
				// filter this channel?
				if (!in_array( strtolower($entry->getChannel()->getName()), $this->channels)) {continue;}
				
				$tpl->clear();
				$this->attach($tpl, $entry);
				$box .= $tpl->get();	
			}
			
			
			$tpl = new Template('dashboardTile');
			$tpl->set('TILE_SIZE', "4x1");
			$tpl->setUnsafe('CONTENT', "<table>{$box}</table>");
			$tpl->set('HEADER', 'up now');
			return $tpl->get();
		
		}
	
		/** attach the currently processed channel to the output */
		private function attach($tpl, $entry) {
			$icon = MyHTML::getChannelIconFile($entry->getChannel()->getName());
			$tpl->set('URL_WATCH', "ext/m3u.php?channel={$entry->getChannel()->getCode()}");
			$tpl->setUnsafe('CHANNEL_ICON', $icon);
			$tpl->set('CHANNEL', $entry->getChannel()->getName());
			$tpl->set('TITLE', $entry->getTitle());
			$tpl->set('DETAILS', $entry->getInfoBox(true));
			$tpl->setUnsafe('TIME', MyHTML::getPlayTime($entry, true));
			return $tpl->get();
		}
	
		/** @Override */
		public function onLoad(PluginSystem $sys, array $attrs) {
			
			// load requested channel-list
			$tmp = file_get_contents(__DIR__ . '/channels');
			$tmp = strtolower($tmp);
			$tmp = str_replace("\r", '', $tmp);
			$this->channels = explode("\n", $tmp);
			
			// display this plugin on the dashboard
			$sys->registerOutputEvent('dashboard', $this);
			
		}
	
	}

?>
