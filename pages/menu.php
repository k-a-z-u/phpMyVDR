<?php
	
	/** the side menu */
	class Menu implements Content {
		
		/* attributes */
		private static $tplEntry;
		private static $tplSub;
		
		/** static ctor */
		public static function init() {
			Menu::$tplSub = new Template('menu_sub');
			Menu::$tplEntry = new Template('menu_entry');
		}
		
		/** get the HTML */
		public function getHTML() {
		
			$html = '';
			$html .= $this->getSub('Main');
			$html .= $this->getEntry(Menu::$tplEntry, LANG_MENU_START, 'Start');
			$html .= $this->getSub('EPG');
			$html .= $this->getEntry(Menu::$tplEntry, LANG_MENU_EPG_NOW, 'EPG');
			$html .= $this->getEntry(Menu::$tplEntry, LANG_MENU_EPG_TIMELINE, 'EPGTimeline');
			$html .= $this->getEntry(Menu::$tplEntry, LANG_MENU_TIMER, 'Timer');
			
			//$html .= $this->getEntry(Menu::$tplEntry, LANG_MENU_RSS, 'RSS');
			$html .= $this->getSub('Service');
			$html .= $this->getEntry(Menu::$tplEntry, LANG_MENU_CHANNELS, 'Channels');
			$html .= $this->getEntry(Menu::$tplEntry, LANG_MENU_SVDRP, 'SVDRP');
			$html .= $this->getEntry(Menu::$tplEntry, LANG_MENU_EPG_UPDATE, 'EPGupdate');
			return $html;
		
		}

		/** get one entry */
		private function getEntry($tpl, $name, $pageRef, $params='') {
			$tpl->clear();
			$tpl->set('URL', '?page=' . $pageRef . $params);
			$tpl->set('NAME', $name);
			$tpl->set('PAGE', $pageRef);
			if (@$_GET['page'] == $pageRef) {$tpl->set('ACTIVE', 1);}
			return $tpl->get();
		}
		
		private function getSub($name) {
			Menu::$tplSub->set('NAME', $name);
			return Menu::$tplSub->get();
		}
		
		
		
//		/** get a link for the menu */
//		private function getEntry($name, $href) {
//			$page = @$_GET['page'];
//			$class = ($page == $href) ? ('active') : ('');
//			return '<a class="'.$class.'" href="?page='.$href.'">'.$name.'</a>';
//		}
		
	}
	
	Menu::init(); 

?>
