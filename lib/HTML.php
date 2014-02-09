<?php

	class HTML {
	
		/** get an image */
		public static function getImage($src, $alt='', $title='') {
			return '<img src="'.$src.'" alt="'.$alt.'" title="'.$title.'" />';
		}

		/** get an image link */
		public static function getImageLink($src, $href, $alt='', $title='') {
			return '<a href="'.$href.'">'.HTML::getImage($src, $alt, $title).'</a>';
		}
		
		/** get a progress-bar */
		public static function getProgressBar($value, $class, $text='&nbsp;', $title='') {
			if (empty($text)) {$text = '&nbsp;';}
			$tpl  = '<span class="'.$class.'" title="'.$title.'">';
			$tpl .= '<span class="progressbar_bar '.$class.'_bar" style="width:'.$value.'%;">'.$text.'</span>';
			$tpl .= '</span>';
			return $tpl;
		}
		
		/** get a combox-box */
		public static function getCombo($keyVal, $id, $selected="") {
			$tpl  = "<select name='{$id}' id='{$id}'>";
			foreach ($keyVal as $key => $val) {
				$sel = ($selected == $key) ? ("selected='selected'") : ('');
				$tpl .= "<option {$sel} value='{$key}'>{$val}</option>";
			}
			$tpl .= "</select>";
			return $tpl;
		}
		
		/** create a link using urlStart and attaching all array parameters */
		public static function getUrlFromArray($urlStart, array $attrs) {
			$cnt = 0;
			foreach ($attrs as $key => $val) {
				$urlStart .= ($cnt == 0) ? ('?') : ('&');
				$urlStart .= $key . '=' . urlencode($val);
				++$cnt;
			}
			return $urlStart;
		}

		/** get a HTML-safe representation of the provided string */
		public static function getSafe($str) {
			$str = htmlentities($str);
			return $str;
		}
		
	}
	
?>
