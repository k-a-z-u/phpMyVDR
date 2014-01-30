<?php

	class VdrEpgTimerException extends MyException {
		public function __construct($const) {parent::__construct($const);}
    }

	/**
	 * this class can read all timer-entries from VDR and can also
	 * disable/enable/delete/edit/create them
	 */
	class VdrEpgTimer {
	
		/** attributes */
		private $vdrConn;
	
		/** create */
		public function __construct(VdrSvdrpConnection $vdrConn) {
			$this->vdrConn = $vdrConn;
		}
	
		/** get all timers */
		public function getAll() {
			
			// read $when and assert the correct code
			$resp = $this->vdrConn->request('LSTT');
			$resp->assertCode(250);
			
			// get result as line-array and parse it
			$ret = array();
			foreach ($resp->getLinesAsArray() as $line) {
				$ret[] = VdrEpgTimerEntry::getFromSvdrp($line);
			}
			
			// return result
			return $ret;
			
		}
		
		/** get the schedule for all timers for the next x days */
		public function getSchedule($days) {
			$timers = $this->getAll();
			$ret = array();
			foreach ($timers as $timer) {
				$channel = $timer->getChannel(Factory::getSqlite());
				if ($timer->isPeriodical()) {
					$tmp = $timer->getNextRuntimes($days);
					//foreach ($tmp as $tmp1) {
					//	$ret[] = new VdrEpgTimerSchedule($channel, $timer->getTitle(), $tmp1, $timer->getDuration());
					//}
					$ret = array_merge($tmp, $ret);
				} else {
					$ret[] = $timer->getNextRuntime();
				}
			}
			return $ret;
		}
		
		/** get one timer entry by it's index */
		public function getByIndex($index) {
		
			// read $when and assert the correct code
			$resp = $this->vdrConn->request("LSTT {$index}");
			$resp->assertCode(250);
			
			// get response and return result
			$lines = $resp->getLinesAsArray();
			return VdrEpgTimerEntry::getFromSvdrp($lines[0]);
		
		}
		
		/** store the given timer (new or edited) */
		public function store(VdrEpgTimerEntry $timer) {
		
			$settings = $timer->getSvdrpData();
			
			// create or edit timer
			if ($timer->isNew()) {
				$resp = $this->vdrConn->request("NEWT {$settings}");
			} else {
				$resp = $this->vdrConn->request("MODT {$timer->getIndex()} {$settings}");
			}
			
			// check
			if (strpos($resp->getLinesAsString(), 'Error in timer settings') !== false) {
				throw new VdrEpgTimerException('TIMER_WRONG_PARAMETERS');
			}
		
		}
	
		/** delete the timer with the given index */
		public function delete($index) {
			$resp = $this->vdrConn->request('MODT ' . $index . ' off');		// disable first, else "recording" timers can not be deleted!
			$resp = $this->vdrConn->request('DELT ' . $index);
		}
		
		/** enable/disable the timer with the given index */
		public function setEnabled($index, $en) {
			$resp = $this->vdrConn->request('MODT ' . ($en) ? ('on') : ('off') );
		}
	
	}


?>