<?php

	/**
	 * this class handles retrieving and STORING epg data within an SQLite database
	 */
	class VdrEpgSqlite {
	
		/* attributes */
		private $db;
		private $stmtAddShow;
		private $stmtAddChannel;
		private $stmtGetChannelByCode;
		private $stmtGetChannelByIndex;
		private $stmtGetAllChannels;
		private $stmtGetEntryById;
		private $stmtGetEntriesByTS;
		private $stmtGetEntriesByTSNext;
		private $stmtGetEntriesByTSBetween;
		private $stmtGetEntriesBySearch;
		private $stmtGetEntriesByChannel;
		
		
		/** open */
		public function __construct($file) {
			$this->db = new SQLite3($file);
			$this->createIfNeeded();
		}
	
		/** cleanup on destruction */
		public function __destruct() {
			$this->close();
		}
		
		/** close the database */
		public function close() {
			$this->stmtAddShow->close();
			$this->stmtAddChannel->close();
			$this->stmtGetChannelByCode->close();
			$this->db->close();
		}
		
		/** create the database tables if needed */
		private function createIfNeeded() {
			
			// config
			$this->db->exec('PRAGMA synchronous=OFF;');
			$this->db->exec('PRAGMA journal_mode=OFF;');
			
			// create show-table
			$this->db->exec("
				CREATE TABLE IF NOT EXISTS show (
					id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
					channelIdx INTEGER NOT NULL,
					eventID INT NOT NULL,
					eventTsStart INT NOT NULL,
					eventDuration INT NOT NULL,
					title STRING NOT NULL,
					descShort STRING,
					descLong STRING,
					genre INT
				);");
			
			// create channel-table
			$this->db->exec("
				CREATE TABLE IF NOT EXISTS channel (
					idx INTEGER NOT NULL PRIMARY KEY,
					name STRING NOT NULL,
					code STRING NOT NULL
				);");
			
			// creat indexes
			//$this->db->exec('CREATE INDEX IF NOT EXISTS idx_show_channel_name ON show (channelName);');
			$this->db->exec('CREATE INDEX IF NOT EXISTS idx_show_event_time ON show (eventTsStart, eventDuration);');
			$this->db->exec('CREATE INDEX IF NOT EXISTS idx_show_channel_event_time ON show (channelIdx, eventTsStart, eventDuration);');
			//$this->db->exec('CREATE UNIQUE INDEX IF NOT EXISTS idx_unique ON show (channelIdx, eventTsStart)');
			
			$this->db->exec('CREATE INDEX IF NOT EXISTS idx_channel_name ON channel (name);');
			$this->db->exec('CREATE INDEX IF NOT EXISTS idx_channel_code ON channel (code);');
			
			$this->stmtAddShow = $this->db->prepare('INSERT OR IGNORE INTO show (channelIdx, eventID, eventTsStart, eventDuration, title, descShort, descLong, genre) VALUES (  (SELECT idx FROM channel WHERE code = ?), ?,?,?,?,?,?,?)');
			$this->stmtAddChannel = $this->db->prepare('INSERT OR IGNORE INTO channel (idx, name, code) VALUES (?,?,?)');
			$this->stmtGetChannelByCode = $this->db->prepare('SELECT name, code, idx FROM channel WHERE code = ?');
			$this->stmtGetChannelByIndex = $this->db->prepare('SELECT name, code, idx FROM channel WHERE idx = ?');
			$this->stmtGetAllChannels = $this->db->prepare('SELECT name, code, idx FROM channel');
			
			$this->stmtGetEntryById = $this->db->prepare('SELECT s.*,c.name as channelName, c.code as channelCode FROM show s LEFT JOIN channel c on c.idx = s.channelIdx WHERE id = ?');
			$this->stmtGetEntriesByTS = $this->db->prepare('SELECT s.*, c.name AS channelName, c.code AS channelCode FROM channel c LEFT OUTER JOIN show s ON c.idx = s.channelIdx AND eventTsStart <= ? AND (eventTsStart + eventDuration) > ?');
			$this->stmtGetEntriesByTSNext = $this->db->prepare('SELECT s.*, c.name AS channelName, c.code AS channelCode FROM show s LEFT JOIN channel c ON c.idx = s.channelIdx WHERE s.id IN (SELECT id+1 FROM show WHERE eventTsStart < ? AND (eventTsStart + eventDuration) >= ?)');
			$this->stmtGetEntriesByTSBetween = $this->db->prepare('SELECT s.*, c.name AS channelName, c.code AS channelCode FROM show s LEFT JOIN channel c ON c.idx = s.channelIdx WHERE (eventTsStart + eventDuration > ?) AND (eventTsStart < ?) ');
			$this->stmtGetEntriesBySearch = $this->db->prepare('SELECT s.*, c.name AS channelName, c.code AS channelCode FROM show s LEFT JOIN channel c ON c.idx = s.channelIdx WHERE (title LIKE :txt OR descLong LIKE :txt) AND (eventTsStart+eventDuration) > :ts');
			$this->stmtGetEntriesByChannel = $this->db->prepare('SELECT s.*, c.name AS channelName, c.code AS channelCode FROM show s LEFT JOIN channel c ON c.idx = s.channelIdx WHERE (s.channelIdx = ?) AND ((eventTsStart+eventDuration) > ?) LIMIT 100');
			
		
		}
		
		/** delete everything from DB */
		public function clear() {
			$this->db->exec("DELETE FROM channel;");
			$this->db->exec("DELETE FROM show;");
		}
		
		/** add a new show to the database */
		public function addEpgEntry(VdrEpgEntry $entry) {
			$cCode = $entry->getChannel()->getCode();
			$cName = $entry->getChannel()->getName();
			$eID = $entry->getEvent()->getID();
			$eTsStart = $entry->getEvent()->getTsStart();
			$eDuration = $entry->getEvent()->getDuration();
			$title = $entry->getTitle();
			$descS = $entry->getDescShort();
			$descL = $entry->getDescLong();
			$genre = $entry->getGenre()->getCode();
			$this->stmtAddShow->bindParam(1, $cCode, SQLITE3_TEXT);
			//$this->stmtAddShow->bindParam(2, $cName, SQLITE3_TEXT);
			$this->stmtAddShow->bindParam(2, $eID, SQLITE3_INTEGER);
			$this->stmtAddShow->bindParam(3, $eTsStart, SQLITE3_INTEGER);
			$this->stmtAddShow->bindParam(4, $eDuration, SQLITE3_INTEGER);
			$this->stmtAddShow->bindParam(5, $title, SQLITE3_TEXT);
			$this->stmtAddShow->bindParam(6, $descS, SQLITE3_TEXT);
			$this->stmtAddShow->bindParam(7, $descL, SQLITE3_TEXT);
			$this->stmtAddShow->bindParam(8, $genre, SQLITE3_INTEGER);
			$this->stmtAddShow->execute();
		}
		
		/** get an EPG-Entry by the given id */
		public function getEpgEntryById($id) {
			$this->stmtGetEntryById->bindParam(1, $id, SQLITE3_INTEGER);
			$res = $this->stmtGetEntryById->execute();
			$row = $res->fetchArray(SQLITE3_ASSOC);
			$res->finalize();
			return getEpgEntryByRow($row);
		}
		
		
		/** add a new channel to the database */
		public function addChannel(VdrChannel $chan) {
			@$this->stmtAddChannel->bindParam(1, $chan->getIndex(), SQLITE3_INTEGER);
			@$this->stmtAddChannel->bindParam(2, $chan->getName(), SQLITE3_TEXT);
			@$this->stmtAddChannel->bindParam(3, $chan->getCode(), SQLITE3_TEXT);
			$this->stmtAddChannel->execute();
		}
		
		/**
		 * delete the channel with the given index
		 * then shift all following channel's indexes up by 1
		 * and update all show's channel index as well
		 */
		public function deleteChannelAndUpdate($index) {
			$index = intval($index);
			$sql  = "BEGIN TRANSACTION;";
			$sql .= "DELETE FROM channel WHERE idx = {$index};";
			$sql .= "DELETE FROM show WHERE channelIdx = {$index};";
			$sql .= "UPDATE channel SET idx = idx - 1 WHERE idx > {$index};";
			$sql .= "UPDATE show SET channelIdx = channelIdx - 1 WHERE channelIdx > {$index};";
			$sql .= "COMMIT;";
			$this->db->exec($sql);
		}
		
		/** get channel by code "SIGNALQUELLE-NID-TID-SID" */
		public function getChannelByCode($code) {
			$this->stmtGetChannelByCode->bindParam(1, $code, SQLITE3_TEXT);
			$res = $this->stmtGetChannelByCode->execute();
			$row = $res->fetchArray(SQLITE3_ASSOC);
			$res->finalize();
			return new VdrChannel($row['code'], $row['name'], $row['index']);
		}
		
		/** get channel by index */
		public function getChannelByIndex($index) {
			$this->stmtGetChannelByIndex->bindParam(1, $index, SQLITE3_INTEGER);
			$res = $this->stmtGetChannelByIndex->execute();
			$row = $res->fetchArray(SQLITE3_ASSOC);
			$res->finalize();
			return new VdrChannel($row['code'], $row['name'], $row['idx']);
		}
		
		/** simply get all channels as array */
		public function getAllChannels() {
			$res = $this->stmtGetAllChannels->execute();
			$ret = array();
			while( ($row = $res->fetchArray(SQLITE3_ASSOC)) !== false) {
				$ret[] = new VdrChannel($row['code'], $row['name'], $row['idx']);
			}
			$res->finalize();
			return $ret;
		}
		
		/** remove entries from database which are already over */
		public function removeOldEpgEntries() {
			$ts = time();
			$this->db->exec("DELETE FROM show WHERE (eventTsStart + eventDuration) < {$ts}");
			$this->db->exec('VACUUM');
		}
		
		
		/** 
		 * get entries for the given timestamp (running now = true, or starting after TS = false)
		 * one can also supply a duration to get e.g. all shows running between timestamp and timestamp + duration
		 */
		public function getEpgAtTime($ts, $runningNow) {
		
			if ($runningNow) {
			
				$this->stmtGetEntriesByTS->bindParam(1, $ts, SQLITE3_INTEGER);
				$this->stmtGetEntriesByTS->bindParam(2, $ts, SQLITE3_INTEGER);
				$res = $this->stmtGetEntriesByTS->execute();
				return new VdrEpgSqliteIterator($res);
				
			} else {
			
				// the entry (id) AFTER the current.. MAY BE BUGGY!!!
				$this->stmtGetEntriesByTSNext->bindParam(1, $ts, SQLITE3_INTEGER);
				$this->stmtGetEntriesByTSNext->bindParam(2, $ts, SQLITE3_INTEGER);
				$res = $this->stmtGetEntriesByTSNext->execute();
				return new VdrEpgSqliteIterator($res);
				
			}
			
		}
		
		/** 
		 * get all shows running between tsStart and tsEnd
		 */
		public function getEpgBetweenTs($tsStart, $tsEnd) {
			$this->stmtGetEntriesByTSBetween->bindParam(1, $tsStart, SQLITE3_INTEGER);
			$this->stmtGetEntriesByTSBetween->bindParam(2, $tsEnd, SQLITE3_INTEGER);
			$res = $this->stmtGetEntriesByTSBetween->execute();
			return new VdrEpgSqliteIterator($res);			
		}
		
		/** get all epg entries that match the given search string that may contain wildcards */
		public function getEpgByString($string) {
			$ts = time();
			if (strlen($string) < 3) {return array();}
			$this->stmtGetEntriesBySearch->bindValue(':txt', $string, SQLITE3_TEXT);
			$this->stmtGetEntriesBySearch->bindValue(':ts', $ts, SQLITE3_INTEGER);
			$res = $this->stmtGetEntriesBySearch->execute();
			return new VdrEpgSqliteIterator($res);
		}
		
		/** get all epg entries of one channel */
		public function getEpgByChannel($idx) {
			$ts = time();
			$this->stmtGetEntriesByChannel->bindParam(1, $idx, SQLITE3_INTEGER);
			$this->stmtGetEntriesByChannel->bindParam(2, $ts, SQLITE3_INTEGER);
			$res = $this->stmtGetEntriesByChannel->execute();
			return new VdrEpgSqliteIterator($res);
		}
	
		/** get all entries */
		public function getEpgAll() {
			$res = $this->db->query("SELECT * FROM show");
			return new VdrEpgSqliteIterator($res);
		}	
	
	}
	
	/** create an EPG entry out of the given DB-row */
	function getEpgEntryByRow($row) {
		$id = $row['id'];
		$title = $row['title'];
		$descShort = $row['descShort'];
		$descLong = $row['descLong'];
		$channel = new VdrChannel($row['channelCode'], $row['channelName'], $row['channelIdx']);
		$event = new VdrEpgEvent($row['eventID'], $row['eventTsStart'], $row['eventDuration']);
		$genre = $row['genre'];
		return VdrEpgEntry::getFromDB($id, $title, $descShort, $descLong, $genre, $channel, $event);
	}
	
	
	class VdrEpgSqliteIterator implements Iterator {
		
		/* attributes */
		private $res;
		private $curRow;
		private $curPos = 0;
		
		/** create */
		public function __construct($res) {
			$this->res = $res;
		}
		
		
		/** rewind is not possible */
		public function rewind() {
			$this->next();
		}
		
		/** get the current element */
		public function current() {
			return getEpgEntryByRow($this->curRow);
		}
		
		/** get the index of the current element */
		public function key() {
			return $this->curPos;
		}
		
		/** move to the next element */
		public function next() {
			++$this->curPos;
			$this->curRow = $this->res->fetchArray(SQLITE3_ASSOC);
		}
		
		/** check if current element is valid */
		public function valid() {
			if ($this->curRow === false) {
				$this->res->finalize();
				return false;
			}
			return true;
		}
	
	}
	
	
?>