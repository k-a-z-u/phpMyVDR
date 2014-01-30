<?php
	
	/**
	 * this class can parse EPG string-data to EPG entries
	 */
	class VdrEpgParser {
	
		/** parse all entries (provided as array) */
		/*
		public static function parseAllEntriesArray(array $lines) {
				
			// the response array
			$resp = array();
			
			// as the format skips redundancy applying e.g. the channel only once
			// we must store this data temporarily
			$channelAndEvent = array();
			
			// get every single EPG-entry
			$start = 0;
			$cnt = 0;
			$streams = array();
			
			$time_start = microtime(true);
			
			gc_enable();

			$numLines = count($lines);
			$descLong = '';
			for ($i = 0; $i < $numLines; ++$i) {
		
				// get the code. (first char of each line)
				$line = $lines[$i];
				$code = $line{0};
				
				// check what to do
				switch ($code) {
					case 'C':	$channel =		substr($line, 2); break;		// store the current channel (for all upcoming entries)
					case 'E':	$event =		substr($line, 2); break;		// store the current event (for all upcoming entries)
					case 'X':	$streams[] =	substr($line, 2); break;		// add the current stream
					case 'T':	$title =		substr($line, 2); break;
					case 'D':	$descLong =		substr($line, 2); break;
					case 'S':	$descShort =	substr($line, 2); break;
					case 'e':	
						if (++$cnt > 20000) {echo $i; $i = 9999999; break;}
						//$newArr = array_slice($lines, $start, $i-$start);		// get only the processed area from the array
						//$newArr = array_merge($channelAndEvent, $newArr);		// append (previously stored) channel and event
						//$resp[] = new VdrEpgEntry($newArr);						// create an EPG entry
						$resp[] = new VdrEpgEntry($channel, $event, $title, $descShort, $descLong, $streams);
						$streams = array();										// reset streams;
						//$start = $i;
						break;
				}
				gc_enabled();
				gc_collect_cycles();
						
			}
			
			$time_end = microtime(true);
			$time = $time_end - $time_start;
			echo "In $time Sekunden nichts getan\n";
			
			// return result
			return $resp;
		
		}
		*/
	
		/** parse one entries (provided as array) only */
		public static function parseOneEntryArray($lines) {
		
			$numLines = count($lines);
			$descLong = '';
			$descShort = '';
			$streams = array();
			$genre = '';
			
			for ($i = 0; $i < $numLines; ++$i) {
		
				// get the code. (first char of each line)
				$line = $lines[$i];
				$code = $line{0};
				
				// check what to do
				if		($code == 'C') {$channel =		substr($line, 2);}
				else if	($code == 'E') {$event =		substr($line, 2);}
				else if ($code == 'X') {$streams[] =	substr($line, 2);}
				else if	($code == 'T') {$title =		substr($line, 2);}
				else if	($code == 'D') {$descLong =		substr($line, 2);}
				else if	($code == 'S') {$descShort =	substr($line, 2);}
				else if ($code == 'G') {$genre =		substr($line, 2, 2);}		// genre may contain up to 4 genres! use only the first one!
				else if ($code == 'e') {return VdrEpgEntry::getFromEpgDataStrings($channel, $event, $title, $descShort, $descLong, $genre, $streams);}
						
			}
			
			// no entry found
			return null;
		
		}
	
	
	
		/** parse all entries (provided as string) */
		public static function parseAllEntriesString($data) {
			return VdrEpgParser::parseAllEntriesArray();
		}
	
	
		/** parse one entry (provided as string) only */
		public static function parseOneEntryString($data) {
			return VdrEpgParser::parseOneEntryArray(explode("\n", $data));
		}
	
	
	}


?>