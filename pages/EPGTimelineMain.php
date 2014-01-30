<?php
	
	/**
	 * the page to display an EPG-timeline
	 */
	class EPGTimelineMain implements Content {
	
		/* attributes */
		private $totalTime;
		private $retVal = '';
		private $curData = '';
		private $tplPage;
		private $tplEntry;
		
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
			
		}
	
		/** get HTML output */
		public function getHTML() {
		
			
			// get entries
			$db = new VdrEpgSqlite(SQLITE_EPG_FILE);
			$data = array();
			$data['search_time'] = time() - $this->pastTime;
			$data['search_duration'] = $this->totalTime;
			$data['sort_by'] = 'chan_name';
			$entries = VdrEpgRequestFactory::get($db, $data);
			
			
			// map each entry to the template 
			$data = '';
			$ret = '';
			$curChannel = null;
			$curSumTs = 0;
			$startTime = time() - $this->pastTime;
			
			// add all entries
			foreach ($entries as $entry) {
			
				// get attributes
				$chan = $entry->getChannel();
				$startTs = $entry->getEvent()->getTsStart();
				$endTs = $entry->getEvent()->getTsEnd();
				$title = HTML::getSafe($entry->getTitle());
				$isRunning = $startTs <= time() && $endTs > time();
								
				// new channel?
				if ($chan != $curChannel && $curChannel != null) {
					$this->attachChannel($curChannel);
					$curSumTs = 0;
				}
				$curChannel = $chan;
				
				
				// calculate the width
				$dispTs = $entry->getEvent()->getDuration();
				if ($startTs < $startTime) {$dispTs -= $startTime - $startTs;}								// entry started before the displayed start-time
				if ($curSumTs + $dispTs > $this->totalTime) {$dispTs = $this->totalTime - $curSumTs;}		// cut if show goes past totalTime
				if ($dispTs <= 0) {continue;}
				$percent = round($dispTs * 100 / ($this->totalTime + 150), 2);
				
				// add to sum
				$curSumTs += $dispTs;
				
				// append
				$infoBox = $entry->getInfoBox();
				//$this->curData .= "<div class='epg_timeline_entry' title='{$infoBox}' style='width:{$percent}%'>{$title}</div>";
				$width = $this->pixel * $percent / 100;
				$class = ($isRunning) ? ('epg_timeline_entry_act') : ('epg_timeline_entry');
				$this->curData .= '<div class="'.$class.'" title="'.$infoBox.'" style="width:'.$width.'px;">'.$title.'</div>';
				
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