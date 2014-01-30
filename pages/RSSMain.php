<?php
	
	/** the RSS-Main page */
	class RSSMain implements Content {
	
		/** ctor */
		public function __construct() {}
	
		/** get HTML output */
		public function getHTML() {
			$tpl = new Template('rss_page');
			return $tpl->get();
		}
		
	}

?>