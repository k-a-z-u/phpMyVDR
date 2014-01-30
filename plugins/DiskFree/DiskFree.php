<?php


	/**
	 * this plugin shows the free disk space within the upper bar
	 */
	class DiskFree implements PluginEvent {
		
		/** @Override */
		public function onLoad(PluginSystem $sys, array $attrs) {
			$sys->registerForEvent('onStatusbar', $this);
		}
		
		/** @Override */
		public function onEvent($evtName, $evtSource) {
			
			// evtSource is the Statusbar
			$total = disk_total_space(RECORDS_FOLDER);
			$free = disk_free_space(RECORDS_FOLDER);
			$percUsed = 100 - ($free * 100 / $total);
			$percFree = ($free * 100 / $total);
			$str = (int) ($free / 1024 / 1024 / 1024) . 'GB free';
			$html = 'disk: ' . HTML::getProgressBar($percUsed, 'barDiskFree', $str);
			$evtSource->addLeft($html);
			
		}
		
	}

?>
