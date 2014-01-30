<?php
	
	/**
	 * represents the whole page
	 */
	class Page {
		
		/* attributes */
		private $tpl;
		private $scripts = '';
		private $statusBar;
		private $startTS;
		
		/** ctor */
		public function __construct() {
			$this->startTS = microtime(true);
			$this->tpl = new Template('page');
			$this->statusBar = new StatusBar();
		}
		
		/** get the status-bar for the page */
		public function getStatusBar() {
			return $this->statusBar;
		}
		
		/** add one script to load */
		public function addScript($filename) {
			$this->scripts .= '<script type="text/javascript" src="' . $filename . '"></script>';
		}
		
		/** add one css file to load */
		public function addCSS($filename) {
			$this->scripts .= '<link rel="stylesheet" type="text/css" href="' . $filename . '" />';
		}
		
		/** set the menu to use */
		public function setMenu(Menu $menu) {
			$this->tpl->setUnsafe('MENU', $menu->getHTML());
		}
		
		/** set the content to display */
		public function setContent($content) {
			$this->tpl->setUnsafe('CONTENT', $content);
		}
		
		/** get as HTML */
		public function getHTML() {
			
			$this->tpl->setUnsafe('SCRIPTS', $this->scripts);
			$this->tpl->setUnsafe('STATUSBAR', $this->statusBar->getHTML());
			
			$this->tpl->set('CHARSET', 'iso-8859-1');
			$this->tpl->set('EXEC_TIME', round( (microtime(true) - $this->startTS) * 1000, 1) );
			
			return $this->tpl->get();
		
		}
		
	}

?>
