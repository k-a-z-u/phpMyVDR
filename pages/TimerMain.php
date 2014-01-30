<?php

	/** sort timers by their next runtime */
	class TimerMainSort implements Comparator {
		public function compareTo($a, $b) {return $a->getNextRuntime()->getTsStart() - $b->getNextRuntime()->getTsStart();}
	}

	/**
	 * the page that lists all configured timers
	 */
	class TimerMain implements Content {
	
		public function getHTML() {
		
			// get templates
			$tplPage = new Template('epg_timer_page');
			$tplEntry = new Template('epg_timer_entry');
		
			// get access to the timers
			$timer = new VdrEpgTimer(Factory::getVdrConnection());
			$entries = $timer->getAll();
			
			// sort them
			Sorter::sort($entries, new TimerMainSort());
			
			// add to template
			$all = '';
			foreach ($entries as $entry) {
				
				$tplEntry->set('TITLE', $entry->getTitle());
				$tplEntry->set('START', $entry->getStartTimeFormatted());
				$tplEntry->set('END', $entry->getEndTimeFormatted());
				$tplEntry->set('DATE', $entry->getDateFormatted());
				$tplEntry->set('ACTIVE', $entry->isActive() ? LANG_TIMER_ACTIVE_YES : LANG_TIMER_ACTIVE_NO);
				$tplEntry->set('IS_RECORDING', $entry->isRecording());
				$tplEntry->set('CONFLICTS', $this->conflictsTimer($entry, $entries));
				$channel = Factory::getSqlite()->getChannelByIndex($entry->getChannelIndex());
				$tplEntry->set('CHANNEL', $channel->getName());
				$tplEntry->setUnsafe('CHANNEL_ICON', MyHTML::getChannelIconFile($channel->getName()));
				
				$idx = $entry->getIndex();
				$tplEntry->set('URL_WATCH', "ext/m3u.php?channel={$entry->getChannel()->getCode()}");
				$tplEntry->set('URL_EDIT', '?page=TimerEdit&action=edit&index='.$idx);
				$tplEntry->set('URL_DELETE', '?page=TimerEdit&action=delete&index='.$idx);
				
				$all .= $tplEntry->get();
			
			}
			
		
			
			// assign to page
			$tplPage->setUnsafe('ENTRIES', $all);
			$tplPage->setUnsafe('URL_NEW_TIMER', '?page=TimerEdit&amp;action=new');
			return $tplPage->get();
		
		}
				
		/** check if the entry conflicts with another entry within the array */
		private function conflictsTimer(VdrEpgTimerEntry $entry, array $entries) {
		
			// skip if $entry is disabled (not recording anyway)
			if (!$entry->isActive()) {return false;}
		
			// get my recording schedule
			$schedA = $entry->getNextRuntime();
		
		
			// check if the provided entry overlaps with another timer-entry
			foreach ($entries as $otherEntry) {
			
				// is the otherEntry disabled?
				if (!$otherEntry->isActive()) {continue;}
			
				// get schedule of the other timer
				$schedB = $otherEntry->getNextRuntime();
			
				// no conflict -> ok
				if (!$schedA->overlapsWith($schedB)) {continue;}
				
				if (!$otherEntry->getChannel()->canBeUsedSimultaneously($entry->getChannel())) {
					return true;
				}
				
			}
			
			return false;
		
		}
	
	}
	
	


?>