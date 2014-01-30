<?php

	/**
	 * displays a box within dashboard showing all upcoming
	 * shows, matching a user-given filter criteria
	 */
	class Favorites implements PluginHTML {
	
		/* attributes */
		private $filter = '';
		private $limit = 5;
	
		/** @Override */
		public function getHTML() {
			
			// add output into this box
			$tplBox = new Template('dashboardTile');
			
			// template for each entry
			$tpl = new Template('plug_favorites_entry');
			
			// fetch from database
			$content = '';
			$db = Factory::getSqlite();
			$req = array('search_text' => $this->filter, 'sort_by' => 'timee');
			$res = VdrEpgRequestFactory::get($db, $req);
			
			// list all entries (within limit);
			$cnt = 0;
			foreach ($res as $entry) {
				
				$tpl->clear();
				$this->attach($tpl, $entry);
				$content .= $tpl->get();
				
				// limit reached?
				if (++$cnt >= $this->limit) {break;}
				
			}
			
			$tplBox->setUnsafe('CONTENT', "<table width='100%'>{$content}</table>");
			$tplBox->set('TILE_SIZE', "2x1");
			$tplBox->set('HEADER', "Shows matching '{$this->filter}'");
			return $tplBox->get();
		
		}
		
		/** attach the currently processed channel to the output */
		private function attach($tpl, $entry) {
			$icon = MyHTML::getChannelIconFile($entry->getChannel()->getName());
			$tpl->set('URL_WATCH', "ext/m3u.php?channel={$entry->getChannel()->getCode()}");
			$tpl->setUnsafe('CHANNEL_ICON', $icon);
			$tpl->set('CHANNEL', $entry->getChannel()->getName());
			$tpl->set('TITLE', $entry->getTitle());
			$tpl->set('DETAILS', $entry->getInfoBox(true));
			$tpl->setUnsafe('DATETIME', MyHTML::getPlayTime($entry, true, true));
			return $tpl->get();
		}
		
		/** @Override */
		public function onLoad(PluginSystem $sys, array $attrs) {
			
			// display this plugin on the dashboard
			$sys->registerOutputEvent('dashboard', $this);
			
			// first argument = match only shows containing this keyword
			$this->filter = $attrs[0];
			$this->limit = @$attrs[1] ? $attrs[1] : 5;
			
		}
		
	}

?>
