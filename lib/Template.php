<?php

	function startsWith($haystack, $needle) {
		return strpos($haystack, $needle) === 0;
	}

	
	/** conditions for template's "IF" */
	interface TemplateCond {
		function matches(Template $tpl, $cond);
		function isTrue(Template $tpl, $cond);
	}
	
	/** check if variable is set */
	class TemplateCondIsset implements TemplateCond{
		function matches(Template $tpl, $cond)	{return startsWith($cond, 'IS_SET');}
		function isTrue(Template $tpl, $cond)	{$arr = explode(' ', $cond); $key = $arr[1]; return @$tpl->getVal($key);}
	}
	Template::addCondition(new TemplateCondIsset());
	
	/** check if variable is true */
	class TemplateCondIsTrue implements TemplateCond{
		function matches(Template $tpl, $cond)	{return startsWith($cond, 'IS_TRUE');}
		function isTrue(Template $tpl, $cond)	{$arr = explode(' ', $cond); $key = $arr[1]; return @$tpl->getVal($key) == true;}
	}
	Template::addCondition(new TemplateCondIsTrue());
	
	
	/** convert template output e.g. "to-uppwer-case" or "stripslashes" */
	interface TemplateConverter {
		function convert($str);
	}
	
	/** make safe */
	class TemplateConverterStrip implements TemplateConverter {
		function convert($str) {
			$str = str_replace('"', '&#34;', $str);
			$str = str_replace("'", '&#39;', $str);
			return $str;
		}
	}
	Template::addConverter('JS_SAFE', new TemplateConverterStrip());
	
	
	/** the template class */
	class Template {
	
		/* statics */
		public static $PATH = '';
		private static $conditions = array();
		private static $converter = array();
		
		/** register a new condition the template class may use for output */
		public static function addCondition(TemplateCond $cond) {
			Template::$conditions[] = $cond;
		}
		
		/** register a new converter the template class may use for output */
		public static function addConverter($keyWord, TemplateConverter $converter) {
			Template::$converter[$keyWord] = $converter;
		}
	
		/* attributes */
		private $tpl;
		private $keyVal;
		
		
		/** create */
		public function __construct($file) {
			$this->tpl = file_get_contents(Template::$PATH.'/'.$file.'.tpl');
			$this->tpl = $this->replaceLanguageConstants($this->tpl);
			$this->clear();
			$this->createConditions();
		}
		
		/** create conditions (command pattern) if not yet done */
		private function createConditions() {
			if (Template::$conditions) {return;}
		}
		
		/** clear the bindings */
		public function clear() {
			$this->keyVal = array();
		}
		
		/** set a new parameter (html safe) */
		public function set($key, $val) {
			$this->keyVal[$key] = $this->makeHtmlSafe($val);
		}
		
		/** set a new text-parameter (replaces \r\n etc..) */
		public function setText($key, $val) {
			$val = $this->makeHtmlSafe($val);
			$val = str_replace("\n", '<br/>', $val);
			$val = str_replace('\n', '<br/>', $val);
			$this->keyVal[$key] = $val;
		}
		
		/** set a new parameter (html UNSAFE) */
		public function setUnsafe($key, $val) {
			$this->keyVal[$key] = $val;
		}
		
		/** get a configured parameter */
		public function getVal($key) {
			return $this->keyVal[$key];
		}
		
		
		
		/** make content HTML safe */
		private function makeHtmlSafe($val) {
			$val = htmlentities($val);
			//$val = str_replace('&', '&amp;', $val);
			return $val;
		}
		
		/** replace all consnstants {*FOO_BAR} used for languages etc. */
		private function replaceLanguageConstants($data) {
			return preg_replace_callback('#({\*)(.*?)(})#', create_function('$matches','return constant($matches[2]);'), $data);
		}
		
		/** compute IF .. ENDIF occurences within template */
		private function computeIf($data) {
			$regEx = '#{IF ([^}]+)}(.*?)({ELSE}(.*?))?{ENDIF}#ism';
			$callback = array('Template','callbackIf');
			return preg_replace_callback($regEx, $callback, $data); 
		}
		
		/** compute IF .. ENDIF matches */
		private function callbackIf(array $matches) {
			$cond = $matches[1];											// this is the condition.. (e.g. ISSET KEY)
			$contentTrue = $matches[2];										// the content to display if TRUE
			$contentFalse = (isset($matches[4])) ? ($matches[4]) : ('');	// the content to display if FALSE (the 'else' part, if set)
			$isOK = (startsWith($cond, '!')) ? (false) : (true);			// to check against 'true' or 'false'
			$cond = str_replace('!', '', $cond);
			foreach (Template::$conditions as $class) {
				if ($class->matches($this, $cond)) {return $class->isTrue($this, $cond) == $isOK ? $contentTrue : $contentFalse;}
			}
		}
		
		/** called for the real replacement of elements within the template */
		private function callbackInsert(array $matches) {
			$convKey = $matches[1];
			$converter = Template::$converter[$convKey];
			$key = $matches[2];
			$val = @$this->keyVal[$key];
			return $converter->convert($val);			
		}
		
		/** get the output */
		public function get() {
		
			// get the template from "cache"
			$ret = $this->tpl;
			
			// compute if
			$ret = $this->computeIf($ret);
			
			// simply replace all elements that match the given {KEY}
			// we use this for simple keys as this is much faster than using regular expressions
			foreach($this->keyVal as $key => $val) {
				$ret = str_replace('{'.$key.'}', $val, $ret);
			}
						
			// special replaces that also use a converter
			$callback = array('Template','callbackInsert');
			$ret = preg_replace_callback('#{([A-Z_]+):([A-Z_]+)}#', $callback, $ret);
			
			// replace the path
			$ret = str_replace("{PATH}", Template::$PATH, $ret);
			
			// finally remove unused keys
			$ret = preg_replace('#{[A-Z_]+}#', '', $ret);
			
			// return
			return $ret;
						
		}
		
		
		
	}
	
?>