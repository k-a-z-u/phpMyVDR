<?php
	
	/**
	 * the page to display the complete (EPG)-timeline
	 * for the next few hours of several channels.
	 * 
	 * TODO: make starting-time selectable (e.g. show starting from 20:15)
	 * 
	 */
	class EPGTimelineMain implements Content {
	
		/* attributes */
		private $totalTime;
		private $retVal = '';
		private $curData = '';
		private $tplPage;
		private $tplEntry;
		private $searchTime;
		
		/**
		 * ctor
		 * intitalize the comparators
		 */
		public function __construct() {
			
			$this->totalTime = 90*60;				// display the next 2 hours
			$this->pastTime = 15*60;				// display 15 minutes of the past
			$this->pixel = 900;
			
			// get template
			$this->tplPage = new Template('timeline_page');
			$this->tplEntry = new Template('timeline_entry');
			
			// attach controls
			$this->searchTime = @$_POST['search_time'];
			if ( empty($this->searchTime) ) {$this->searchTime = VdrEpgRequestFactoryParams::SEARCH_TIME_NOW;}
			$this->tplPage->setUnsafe('CMB_TIME', Combos::getEpgTime('search_time', $this->searchTime));
			
		}
	
		/** get HTML output */
		public function getHTML() {
		
			
			// get entries
			$db = new VdrEpgSqlite(SQLITE_EPG_FILE);
			$params = new VdrEpgRequestFactoryParams();
			//$params->setSearchTime( time() - $this->pastTime );
			//$params->setSearchDuration( $this->totalTime );
			
			// use either "now" minus a few minutes (currenlty running stuff)
			// or use the given time in format "hhmm" (e.g. 2015 for 20:15)
			$time = (strlen($this->searchTime) == 4) ? ($this->searchTime) : ( time() - $this->pastTime );
			$params->setSearchBetweenTime( $time, $this->totalTime );
			$params->sortBy( VdrEpgRequestFactory::SORT_BY_CHAN_NAME );
			$entries = VdrEpgRequestFactory::getByParams($db, $params);
			
			
			// map each entry to the template 
			$data = '';
			$ret = '';
			$curChannel = null;
			$curSumTs = 0;
			$percentError = 0;
			$startTime = time() - $this->pastTime;
			
			// add all entries
			foreach ($entries as $entry) {
			
				// get attributes
				$eventID = $entry->getDbId();
				$chan = $entry->getChannel();
				$startTs = $entry->getEvent()->getTsStart();
				$endTs = $entry->getEvent()->getTsEnd();
				$title = HTML::getSafe($entry->getTitle());
				$isRunning = $startTs <= time() && $endTs > time();
								
				// does this entry belong to a new channel? -> start over with next row
				if ($chan != $curChannel && $curChannel != null) {
					$this->attachChannel($curChannel);
					$curSumTs = 0;
					$percentError = 0;
				}
				$curChannel = $chan;
				
				
				// duration of the entry to add
				$dispTs = $entry->getEvent()->getDuration();
				
				// check some special cases
				if ($startTs < $startTime) {$dispTs -= $startTime - $startTs;}								// entry started before the displayed start-time
				if ($curSumTs + $dispTs > $this->totalTime) {$dispTs = $this->totalTime - $curSumTs;}		// cut if show goes past totalTime
				if ($dispTs <= 0) {continue;}
				
				// calculate width in percent
				$percent = $dispTs / $this->totalTime;
				$p0 = ($percent * 98) + $percentError;
				$p1 = round($p0, 1);
				$percentError += ($p0 - $p1);
				
				// add to sum
				$curSumTs += $dispTs;
				
				// append entry to current row
				$infoBox = $entry->getInfoBox();
				$class = ($isRunning) ? ('epg_timeline_entry_act') : ('epg_timeline_entry');
				$this->curData .= '<div class="'.$class.'" title="'.$infoBox.'" style="width:'.$p1.'%;">';
				$this->curData .= '<a href="?page=EPG&amp;event_id='.$eventID.'">' . $title . '</a>';
				$this->curData .= '</div>';
				
			}
			
			// attach last entry
			$this->attachChannel($curChannel);
		
			// configure timeline
			$timelinePercent = intval($this->pixel * $this->pastTime / $this->totalTime);
			$this->tplPage->set('CUR_TIME_POS', $timelinePercent);
		
			// return created data
			$this->tplPage->setUnsafe('ENTRIES', $this->retVal);
			return $this->tplPage->get();
		
		}
		
		
		/** attach the currently processed channel to the output */
		private function attachChannel($channel) {
			$icon = MyHTML::getChannelIconFile($channel->getName());
			$this->tplEntry->set('URL_WATCH', "ext/m3u.php?channel={$channel->getCode()}");
			$this->tplEntry->setUnsafe('CHANNEL_ICON', $icon);
			$this->tplEntry->set('CHANNEL', $channel->getName());
			$this->tplEntry->setUnsafe('SHOWS', $this->curData);
			$this->retVal .= $this->tplEntry->get();
			$this->curData = '';
		}

	
	}

?>
