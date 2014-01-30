<?php

	/** this class represents one timer entry */
	class VdrEpgTimerEntry {
	
		/* attributes */
		private $index;				// the index (number) of this timer
		private $status;			// 1 = active, 0 = inactive, 9 = recording
		private $channelIndex;
		private $channel;
		private $when;				// day-of-month ("19"), date ("2005-03-19"), schedule ("MTWTFSS",  "M-W-FSS")
		private $startTime;			// "2010" => 20:10
		private $endTime;			// "2215" => 22:15
		private $priority = 99;
		private $durability = 99;
		private $title;
		
		
		/** create */
		public function __construct($channelIndex, $title, $when, $startTime, $endTime, $status, $priority = 99, $durability = 99, $index = -1) {
			$this->channelIndex = $channelIndex;
			$this->title = $title;
			$this->when = $when;
			$this->startTime = $startTime;
			$this->endTime = $endTime;
			$this->status = $status;
			$this->priority = $priority;
			$this->durability = $durability;
			$this->index = $index;
			$this->channel = Factory::getSqlite()->getChannelByIndex($this->channelIndex);
		}
		
		/** create a timer from the given SVDRP response-line */
		public static function getFromSvdrp($line) {
			$arr = explode(' ', $line, 2);
			$data = explode(':', $arr[1]);
			return new VdrEpgTimerEntry($data[1], $data[7], $data[2], $data[3], $data[4], $data[0], $data[5], $data[6], $arr[0]);
		}
	
		/** get the SVDRP data to create this timer */
		public function getSvdrpData() {
			return "{$this->status}:{$this->channelIndex}:{$this->when}:{$this->startTime}:{$this->endTime}:{$this->priority}:{$this->durability}:{$this->title}:";
		}
		
		
		
		/** is this a new timer or does it already exist? */
		public function isNew() {return $this->index == null || $this->index < 1;}
		
		
		/** get the index of this timer-entry */
		public function getIndex() {return $this->index;}
		
		/** get the index of the channel */
		public function getChannelIndex() {return $this->channelIndex;}
		
		/** get the channel of this timer */
		public function getChannel() {return $this->channel;}
	
		/** get the title of this timer */
		public function getTitle() {return $this->title;}
	
	
		/** get the start-time of this timer: "2015" */
		public function getStartTime() {return $this->startTime;}
	
		/** get the starting hour */
		public function getStartHour() {return substr($this->startTime, 0, 2);}
		
		/** get the starting minute */
		public function getStartMinute() {return substr($this->startTime, 2, 2);}
	
		/** get the start-time of this timer: "20:15" */
		public function getStartTimeFormatted() {return substr($this->startTime, 0, 2) . ':' . substr($this->startTime, 2, 2);}
		
		
		/** get the end-time of this timer: "2200" */
		public function getEndTime() {return $this->endTime;}
	
		/** get the ending hour */
		public function getEndHour() {return substr($this->endTime, 0, 2);}
		
		/** get the ending minute */
		public function getEndMinute() {return substr($this->endTime, 2, 2);}
	
		/** get the end-time of this timer: "22:00" */
		public function getEndTimeFormatted() {return substr($this->endTime, 0, 2) . ':' . substr($this->endTime, 2, 2);}
		
		
		/** get the duration of the given timer */
		public function getDuration() {
			$sh = $this->getStartHour();
			$sm = $this->getStartMinute();
			$eh = $this->getEndHour();
			$em = $this->getEndMinute();
			if ($eh < $sh) {$eh += 24;}
			return ($eh - $sh) * 60 * 60 + ($em - $sm) * 60;
		}
		
		
		/** check if this timer entry is scheduled more than once */
		public function isPeriodical() {return strlen($this->when) == 7;}
		
		/** get the date of this timer */
		public function getDate() {return $this->when;}
		
		/** get the date formatted as a readable strng */
		public function getDateFormatted() {
			$arr = date_parse($this->when);
	
			if ($this->when == (String) intval($this->when)) {
				return "FIXME";
				
			} else if ($arr['errors']) {
				$out = '';
				for ($i = 0; $i < 7; ++$i) {
					if ($this->when{$i} != '-') {$out .= MyDate::getDayNameShort($i) . ',';}
				}
				return substr($out, 0, strlen($out)-1);
				
			} else {
				$ts = mktime(0, 0, 0, $arr['month'], $arr['day'], $arr['year']);
				return strftime(LANG_DATE_DAYMONTH, $ts);
				
			}
			
		}
		
		/** get all runtimes of this periodical timer that occur within the given range (in seconds) */
		public function getNextRuntimes($days) {
			
			$times = array();
			$scheduled = $this->when;
			$curDay = MyDate::getDayOfMonth();
			$hour = $this->getStartHour();
			$minute = $this->getStartMinute();
			$month = MyDate::getMonth();
			
			for ($add = 0; $add < $days; ++$add) {									// check the next x days
				$dayOfMonth = $curDay + $add;
				$ts = mktime($hour, $minute, 0, $month, $dayOfMonth);			// get timestamp for the to-be-checked day
				$dayOfWeek = MyDate::getDayOfWeek($ts) - 1;						// $dayOfWeek: 0 = monday, 6 = sunday
				if ($scheduled[$dayOfWeek] == '-') {continue;}					// check if the show is scheduled to run on this day-of-week
				$times[] = new VdrEpgTimerSchedule($this->getChannel(), $this->getTitle(), $ts, $this->getDuration());													// scheduled => add
			}
	
			return $times;
	
		}
		
		/** get the next timestamp this element will be recorded (e.g. for periodical timers) */
		public function getNextRuntime() {
			if (!$this->isPeriodical()) {
				$date = explode('-', $this->when);				// day-of-month ("19") or date ("2005-03-19")
				$day =		(count($date) == 3) ? ($date[2]) : ($date[0]);
				$month =	(count($date) == 3) ? ($date[1]) : (MyDate::getMonth());
				$year = 	(count($date) == 3) ? ($date[0]) : (MyDate::getYear());
				$hour = $this->getStartHour();
				$minute = $this->getStartMinute();
				$ts = mktime($hour, $minute, 0, $month, $day, $year);
				return new VdrEpgTimerSchedule($this->getChannel(), $this->getTitle(), $ts, $this->getDuration());
			} else {
				$runtimes = $this->getNextRuntimes(7);		// get runtimes for the next 7 days;
				return $runtimes[0];						// get the next runtime
			}
		}
		
		/** check if this timer is active */
		public function isActive() {return ($this->status & 1) != 0;}
	
		/** check if this timer is currently recording */
		public function isRecording() {return ($this->status & 8) != 0;}
	
	}

?>