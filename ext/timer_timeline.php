<?php

	/** generate the timeline for the timers */

	include "../settings.php";

	// number of days to fetch
	$days = @$_GET['days'];
	
	// get access to the timers
	$timer = new VdrEpgTimer(Factory::getVdrConnection());
	
	// get schedule for next x days
	$schedule = $timer->getSchedule($days);
	$str = '';
	foreach ($schedule as $item) {
		$isRecording = $item->getTsStart() > time() == $item->getTsStart() + $item->getDuration() < time();
		$hasConflict = conflictsSchedule($item, $schedule);
		$str .= $item->getChannel()->getName().':';
		$str .= $item->getTitle().':';
		$str .= $item->getTsStart().':';
		$str .= $item->getDuration().':';
		$class = '';
		$class .= ($hasConflict) ? (' conflicts') : ('');
		$class .= ($isRecording) ? (' recording') : ('');
		$str .= $class . "\n";
	}
	
	echo $str;
	
			
	/** check if the entry conflicts with another entry within the array */
	function conflictsSchedule(VdrEpgTimerSchedule $entry, array $entries) {
		foreach ($entries as $otherEntry) {
			if (!$entry->overlapsWith($otherEntry)) {continue;}
			if (!$otherEntry->getChannel()->canBeUsedSimultaneously($entry->getChannel())) {
				return true;
			}
		}
		return false;
	}
	
?>