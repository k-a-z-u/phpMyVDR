<?php

	/**
	 * displays a box within dashboard showing stats
	 * of the servers tv-cards
	 */
	class Femon implements PluginHTML {
	
		/** @Override */
		public function getHTML() {
		
			// add output into this box
			$tplBox = new Template('dashboardTile');
		
			// get template
			$tpl = new Template('plug_femon_entry');
	
			// get Femon stats
			$var = new VdrFemon(Factory::getVdrConnection());
			$resp = $var->get();
			
			// set values
			$tpl->clear();
			$sig = $resp[0]->getSignalStrength();
			$noise = $resp[0]->getSignalNoiseRatio();
			$sigPerc = (int) ($sig * 100 / 65536);
			$noisePerc = (int) ($noise * 100 / 65536);
			$tpl->set('SIG', $sig);
			$tpl->set('NOISE', $noise);
			$tpl->setUnsafe('SIG_BAR', HTML::getProgressBar($sigPerc, 'bar1', $sigPerc.'%'));
			$tpl->setUnsafe('NOISE_BAR', HTML::getProgressBar($noisePerc, 'bar1', $noisePerc.'%'));
			$tpl->set('CARD', $resp[0]->getCard());
			
			$tplBox->setUnsafe('CONTENT', $tpl->get());
			$tplBox->set('TILE_SIZE', "2x1");
			$tplBox->set('HEADER', 'Femon');
			return $tplBox->get();
		
		}
		
		/** @Override */
		public function onLoad(PluginSystem $sys, array $attrs) {
			
			// display this plugin on the dashboard
			$sys->registerOutputEvent('dashboard', $this);
			
		}
		
	}

?>
