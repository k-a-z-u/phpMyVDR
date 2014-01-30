<?php

	class TimerEditMain implements Content {
	
	
		public function getHTML() {
		
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				return $this->getPOST();
			} else {
				return $this->getGET();
			}
			
		}
		
		
		/** now store the edited / newly created timer */
		private function getPOST() {
		
			// get values
			$index = ($_POST['timer_index']) ? ($_POST['timer_index']) : (-1);
			$chanIndex = $_POST['timer_channel'];
			$status = $_POST['timer_active'] ? (1) : (0);			// timer enabled or disabled?
			$date = $_POST['timer_date'];
			$startTime = $_POST['timer_start_hour'].$_POST['timer_start_minute'];
			$endTime = $_POST['timer_end_hour'].$_POST['timer_end_minute'];
			$title = $_POST['timer_title'];
		
			// make the title safe for storing (remove some characters, etc)
			$title = $this->makeSafe($title);
			
			// create the timer to store
			$timer = new VdrEpgTimerEntry($chanIndex, $title, $date, $startTime, $endTime, $status, 99, 99, $index);
		
			// store the timer
			$timers = new VdrEpgTimer(Factory::getVdrConnection());
			$timers->store($timer);
			
			// if ok (no exception here) redirect to timers page
			$this->jumpToTimers();
			
		}
		
		
		/** edit or create timer */
		private function getGET() {
		
			// get the template
			$tplPage = new Template('epg_timer_edit_entry');
			
			// get the element (if edit) or do nothing (new timer)
			$index = @$_GET['index'];
			$showID = @$_GET['showID'];
			$action = @$_GET['action'];
			
			switch ($action) {

				case 'delete':
				
					$timer = new VdrEpgTimer(Factory::getVdrConnection());
					$timer->delete($index);
					$this->jumpToTimers();
					break;
					
				case 'edit':
				
					$timer = new VdrEpgTimer(Factory::getVdrConnection());
					$entry = $timer->getByIndex($index);
				
					$tplPage->set('INDEX', $index);
					$tplPage->setUnsafe('SEL_ACTIVE_YES', $entry->isActive() ? 'checked="checked"' : '');
					$tplPage->setUnsafe('SEL_ACTIVE_NO', !$entry->isActive() ? 'checked="checked"' : '');
					$tplPage->set('TITLE', $entry->getTitle());
					$tplPage->set('START_HOUR', $entry->getStartHour());
					$tplPage->set('START_MINUTE', $entry->getStartMinute());
					$tplPage->set('END_HOUR', $entry->getEndHour());
					$tplPage->set('END_MINUTE', $entry->getEndMinute());
					$tplPage->set('DATE', $entry->getDate());
					$tplPage->setUnsafe('CMB_CHANNELS', Combos::getChannels('timer_channel', $entry->getChannelIndex()));
					$tplPage->set('ACTION', LANG_TIMER_EDIT_EDIT . ' \'' . $entry->getTitle() . '\'');
					break;
					
				case 'newFromShow':
					
					$epg = new VdrEpgSqlite(SQLITE_EPG_FILE);
					$epgEntry = $epg->getEpgEntryById($showID);
					$start = $epgEntry->getEvent()->getTsStart() - 60 * 10;
					$end = $epgEntry->getEvent()->getTsEnd() + 60 * 10;
					
					$tplPage->set('SEL_ACTIVE_YES', 'checked="checked"');
					$tplPage->set('TITLE', $this->makeSafe($epgEntry->getTitle()));
					$tplPage->set('DATE', MyDate::getDayOfMonth($epgEntry->getEvent()->getTsStart()));
					$tplPage->set('START_HOUR',  MyDate::getHour($start));
					$tplPage->set('START_MINUTE', MyDate::getMinute($start));
					$tplPage->set('END_HOUR', MyDate::getHour($end));
					$tplPage->set('END_MINUTE', MyDate::getMinute($end));
					$tplPage->setUnsafe('CMB_CHANNELS', Combos::getChannels('timer_channel', $epgEntry->getChannel()->getIndex()));
					$tplPage->set('ACTION', LANG_TIMER_EDIT_NEW . ' \'' . $epgEntry->getTitle() . '\'');
					break;
					
				case 'new':
					
					$tplPage->setUnsafe('SEL_ACTIVE_YES', 'checked="checked"');
					$tplPage->set('START_HOUR', MyDate::getHour());
					$tplPage->set('START_MINUTE', MyDate::getMinute());
					$tplPage->set('DATE', MyDate::getDayOfMonth());
					$tplPage->setUnsafe('CMB_CHANNELS', Combos::getChannels('timer_channel', 0));
					$tplPage->set('ACTION', LANG_TIMER_EDIT_NEW);
					break;
				
				default:
					$this->jumpToTimers();
					break;
				
			}
			
			// return
			return $tplPage->get();
		
		}
		
		/** jump back to the timers page */
		private function jumpToTimers() {
			header('location:?page=Timer');
		}
		
		
		/** make the provided string safe for VDR-format (remove ':' etc.) */
		private function makeSafe($str) {
			$str = str_replace(':', '', $str);
			return $str;
		}
		
	}

?>