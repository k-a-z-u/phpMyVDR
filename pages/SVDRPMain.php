<?php


	
	
	
	/** the SVDRP-Main page */
	class SVDRPMain implements Content {
	
		/**
		 * ctor
		 * intitalize the comparators
		 */
		public function __construct() {}
	
		/** get HTML output */
		public function getHTML() {
			$out = '';
			$out .= $this->getHtmlRequest();
			$out .= $this->getHtmlResponse();
			return $out;			
		}
		
		/** get the HTML request box */
		private function getHtmlRequest() {
			$tpl = new Template('svdrp_request');
			return $tpl->get();
		}
		
		
		
		/** get the HTML response if request has been sent */
		private function getHtmlResponse() {
		
			// check if a request has been made, else skip!
			$cmd = @$_POST['request'];
			if (!$cmd) {return '';}
			
			// get the connection
			$con = Factory::getVdrConnection();
			$resp = $con->request($cmd);
			
			// create the template
			$tpl = new Template('svdrp_response');
			$tpl->set('HEADER', LANG_SVDRP_RESPONSE_HEADER);
			$tpl->set('CONTENT', $resp->getLinesAsString());
			return $tpl->get();
			
		}
			
	}

?>