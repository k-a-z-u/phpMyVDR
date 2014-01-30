<?php
	
	/**
	 * represents the status bar within the page
	 */
	class StatusBar {
		
		/* attributes */
		private $tplEntry;
		private $html = '';
		
		/** ctor */
		public function __construct() {
			$this->tplEntry = new Template('statusbar_entry');
		}
		
		/** add html to the left half of the status bar */
		public function addLeft($html) {
			$this->tplEntry->set('SIDE', 'left');
			$this->tplEntry->setUnsafe('CONTENT', $html);
			$this->html .= $this->tplEntry->get();
		}
		
		/** add html to the right half of the status bar */
		public function addRight($html) {
			$this->tplEntry->set('SIDE', 'right');
			$this->tplEntry->setUnsafe('CONTENT', $html);
			$this->html .= $this->tplEntry->get();
		}
		
		/** get the content */
		public function getHTML() {
			
			// trigger event
			PluginSystem::get()->triggerEvent('onStatusbar', $this);
			
			return $this->html;
			
		}
		
	}

?>
