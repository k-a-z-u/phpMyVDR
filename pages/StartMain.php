<?php

	class StartMain implements Content {
	
		public function getHTML() {
			
			// get HTML of all plugins registered for the dashboard
			$entries = PluginSystem::get()->getFromOutputEvent('dashboard');
			
		
			// get templates for dashboard and each tile
			$tpl = new Template('dashboard');
			
			// construct dashboard-tiles
			$ret = '';
			foreach ($entries as $entry) {
				$ret .= $entry;				
			}
			
			// construct dashaboard
			$tpl->setUnsafe('CONTENT', $ret);
			return $tpl->get();
			
		}
		
	}

?>
