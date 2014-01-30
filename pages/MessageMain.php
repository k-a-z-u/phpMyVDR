<?php

	/**
	 * display error messages
	 */
	class MessageMain implements Content {
	
		public function getHTML() {
		
			$tpl = new Template('error');
		
			$type = htmlentities(stripslashes($_GET['type']));
			$txt = htmlentities(stripslashes($_GET['txt']));
			$msg = $txt;//constant('LANG_EX_'.$txt);
			$msg = str_replace('\n', '<br/>', $msg);
			
			if ($type == 'error') {$tpl->set('BOX_HEADER', LANG_MSG_ERROR);}
			$tpl->setUnsafe('TEXT', $msg);
		
			return $tpl->get();
		
		}	
	
	}

?>
