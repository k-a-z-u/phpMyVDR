<?php


	/**
	 * the page to display an EPG-content
	 */
	class EPGMain implements Content {
	
		/* attributes */
		
		private $sortByStr;
		private $searchTime;
		private $searchStr;
		private $channelFilter;
		private $db;
		
		private $tplSearch;
		
		/**
		 * ctor
		 * intitalize the comparators
		 */
		public function __construct() {
					
			// get configured options for options-box
			$this->sortByStr = @$_POST['sort_by'];
			$this->searchTime = @$_POST['search_time'];
			$this->channelFilter = @$_POST['channelfilter'];
			$this->searchStr = $_POST['search_text'] = (@$_POST['search_text']) ? ($_POST['search_text']) : (@$_GET['search_text']);
			$this->byChannel = $_POST['by_channel'] = (@$_POST['by_channel']) ? ($_POST['by_channel']) : (@$_GET['by_channel']);
			
			$this->db = new VdrEpgSqlite(SQLITE_EPG_FILE);
			$this->tplSearch = new Template('epg_opts');
				
		}
	
		/** get HTML output */
		public function getHTML() {
		
			// get template
			$tpl = new Template('epg_now_entry');
			$tplDay = new Template('epg_now_day');
			
			// sort the entries using the selected comparator
			$entries = VdrEpgRequestFactory::get($this->db, $_POST);
			
			// map each entry to the template 
			$data = '';
			$curDay = -1;
			
			// add all entries
			foreach ($entries as $entry) {
			
				// add a header for each day within the result
				$day = $entry->getEvent()->getDayOfYear();
				if ($day != $curDay) {
					$curDay = $day;
					$start = $entry->getEvent()->getTsStart();
					$date = ($start) ? (date('l, d.m.Y', $start)) : (LANG_EPG_NO_DATA);		// if ts => date else "no epg-data available"
					$tplDay->set('DATE', $date);
					$data .= $tplDay->get();
				}
			
				// get template attributes
				$title = ($entry->getTitle()) ? ($entry->getTitle()) : (LANG_EPG_NO_DATA);
				$channel = $entry->getChannel()->getName();
				$desc = ($entry->getDescShort()) ? ($entry->getDescShort()) : ('');
				$descLong = ($entry->getDescLong()) ? ($entry->getDescLong()) : ('');
				$imgs = '';
				$time = '';
				$isRecordable = $entry->getEvent()->getTsEnd() > time();
				$hasData = ($entry->getTitle()) ? (true) : (false);
				$icon = MyHTML::getChannelIconFile($channel);
				$genreMajor = ($entry->getGenre()) ? ($entry->getGenre()->getMajor()) : ('');
				$genreMinor = ($entry->getGenre()) ? ($entry->getGenre()->getMinor()) : ('');
				
				// get images
				if ($entry->hasAC3())	{$imgs .= HTML::getImage("{PATH}/ac3.png");}
				if ($entry->isHD())		{$imgs .= HTML::getImage("{PATH}/hd.png");}
				
				// get time info
				$time = MyHTML::getPlayTime($entry);
				
				// reset and then configure the template
				$tpl->clear();
				$tpl->set('DB_ID', $entry->getDbId());
				$tpl->set('TITLE', $title);
				$tpl->set('CHANNEL', $channel);
				$tpl->set('GENRE_MAJOR', $genreMajor);
				$tpl->set('GENRE_MINOR', $genreMinor);
				$tpl->setUnsafe('CHANNEL_ICON', $icon);
				$tpl->set('DESC_SHORT', $desc);
				$tpl->setText('DESC_LONG', $descLong);
				$tpl->setUnsafe('TIME', $time);
				$tpl->setUnsafe('IMGS', $imgs);
				
				$tpl->set('IS_RECORDABLE', $isRecordable);
				$tpl->set('HAS_DATA', $hasData);
				$tpl->set('URL_REPEATS', '?page=EPG&search_text=' . urlencode('^' . $entry->getTitle() . '$'));
				$tpl->set('URL_WATCH', "ext/m3u.php?channel={$entry->getChannel()->getCode()}");
				$tpl->set('URL_CREATE_TIMER', "?page=TimerEdit&action=newFromShow&showID={$entry->getDbId()}");
				
				$data .= $tpl->get();
				
			}
		
			// return created data
			return $this->getOptionBox() . $data;
		
		}
				

	
		/** get an option box fitting the search-request */
		private function getOptionBox() {
			
			// create the search
			$this->tplSearch->setUnsafe('CMB_SORT', Combos::getEpgSort('sort_by', $this->sortByStr));
			$this->tplSearch->setUnsafe('CMB_USERFILTER', Combos::getUserChannelFilter('channelfilter', $this->channelFilter));
			
			// check what to enable
			//~ if (isset($this->searchStr)) {
				//~ $this->tplSearch->set('EN_OPT_SORT', true);
				//~ $this->tplSearch->set('EN_OPT_CHANNELFILTER', true);
				//~ $this->tplSearch->set('SEARCH_TEXT', $this->searchStr);
			//~ } if (isset($this->byChannel)) {
				//~ $this->tplSearch->set('EN_OPT_SEARCH', false);
				//~ $this->tplSearch->set('EN_OPT_BY_CHANNEL', true);
				//~ $this->tplSearch->setUnsafe('CMB_CHANNEL', Combos::getChannels('by_channel', $this->byChannel));
			//~ } else {
				$this->tplSearch->set('EN_OPT_SEARCH', true);
				$this->tplSearch->set('EN_OPT_TIME', true);
				$this->tplSearch->set('EN_OPT_SORT', true);
				$this->tplSearch->set('EN_OPT_CHANNELFILTER', true);
				$this->tplSearch->set('EN_OPT_BY_CHANNEL', true);
				$this->tplSearch->set('SEARCH_TEXT', $this->searchStr);
				$this->tplSearch->setUnsafe('CMB_TIME', Combos::getEpgTime('search_time', $this->searchTime));
				$this->tplSearch->setUnsafe('CMB_CHANNEL', Combos::getChannels('by_channel', $this->byChannel));
			//~ }
			
			// rss url
			$this->tplSearch->set('URL_RSS', $this->getRssLink());
			
			// return the finalized template
			return $this->tplSearch->get();
			
		}
		
		
		/** construct the rss link */
		private function getRssLink() {
			return HTML::getUrlFromArray('ext/rss.php', $_POST);
		}
		

	
	}
		

?>
