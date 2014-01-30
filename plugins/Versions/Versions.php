<?php

	/**
	 * this plugin displays a box within the dashboard
	 * showing versioning information of the server's software
	 */
	class Versions implements PluginHTML {
		
		/** @Override */
		public function getHTML() {
		
			// get template
			$tpl = new Template('dashboardTile');
			$content = '';
			
			// get connection
			$con = Factory::getVdrConnection();
			
			// read version info
			$resp = $con->request('HELP');
			$arr = $resp->getLinesAsArray();
			$vdrVer = $arr[0];
			$content .= $vdrVer;
			
			// read plugins info
			$resp = $con->request('PLUG');
			$arr = $resp->getLinesAsArray();
			unset($arr[count($arr)-1]);
			unset($arr[0]);
			$plugins = implode('<br/>', $arr);
			$content .= '<br/>' . $plugins;
		
			$tpl->set('HEADER', 'Software');
			$tpl->set('TILE_SIZE', '2x1');
			$tpl->setUnsafe('CONTENT', $content);
			return $tpl->get();
		
		}
		
		/** @Override */
		public function onLoad(PluginSystem $sys, array $attrs) {
			
			// display this plugin on the dashboard
			$sys->registerOutputEvent('dashboard', $this);
			
		}
	
	}

?>
