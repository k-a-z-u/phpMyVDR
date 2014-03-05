<?php

	error_reporting(E_ALL);

	// include settings
	include "../settings.php";
	
	// try to increase php's max exec time
	// (needed for slow platforms)
	set_time_limit(60);
	
	$conv = new EpgToSqlite();
	
	// check what to do
	$action = @$_GET['action'];
	if ($action == 'update') {$conv->update();}
	if ($action == 'progress') {echo $conv->getProgress();}
	

	
	class EpgToSqlite {
	
		/* attributes */
		private $lockFile;
		private $progressFile;
		private $sqlitePath = '../';
		
	
		/** create */
		public function __construct() {
			$this->lockFile = './tmp_vdr_epg_rebuild.lock';
			$this->progressFile = './tmp_vdr_epg_progress.dat';
		}
		
		/** ensure that this process is not running more than once! */
		private function checkLock() {
			$ts = @file_get_contents($this->lockFile);
			$diff = time() - $ts;
			if ($diff < 90) {die("already running! (since {$diff} seconds)");}
			file_put_contents($this->lockFile, time());
		}
		
		/** remove the lock */
		private function clearLock() {
			unlink($this->lockFile);
		}
		
		/** write the current progress to file */
		private function setProgress($percent) {
			$percent = intval($percent);
			if ($percent == 100) {
				unlink($this->progressFile);
			} else {
				file_put_contents($this->progressFile, intval($percent));
			}
		}
		
		/** get the current progress. returns -1 if not running */
		public function getProgress() {
			$val = @file_get_contents($this->progressFile);
			return ($val) ? ($val) : (-1);
		}
		
		/** this performs the actual update */
		public function update() {
		
			$this->checkLock();
		
			// open DB, channels and EPG-file
			$db = new VdrEpgSqlite($this->sqlitePath . SQLITE_EPG_FILE);
			$chan = new VdrChannelSvdrp(Factory::getVdrConnection());
			$epg = new VdrEpgFile(EPG_FILE);
			
			
			// clear databse
			$db->beginTransaction();
			$db->clear();
			
			// add all channels
			foreach ($chan->getAll() as $channel) {
				$db->addChannel($channel);
			}
			
			// get all EPG entries
			$cnt = 0;
			while (true) {
				
				// get next entry
				$entry = $epg->getNext();
				if ($entry == null) {break;}
				
				// add entry and update progress
				$db->addEpgEntry($entry);
				if (++$cnt % 250 == 0) {$this->setProgress($epg->getPercent());}
			
			}
			
			// finalize
			$db->endTransaction();
			
			// remove old shows and cleanup
			$db->removeOldEpgEntries();
			
			// done
			$db->close();
			
			
		
			// set done
			$this->setProgress(100);
			
			// remove the lock
			$this->clearLock();
		
		}
	
	}
	



	
	
	
?>
