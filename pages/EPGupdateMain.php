<?php
	
	class EPGupdateMain implements Content {
	
		public function getHTML() {
			
			$tpl = new Template('epg_update');
			
			return $tpl->get();
		
		}
	
	}

?>