<?php

	class Combos {
	
		/** get the EPG-sort combo */
		public static function getEpgSort($id, $selected) {
			$keyVal = array();
			$keyVal[VdrEpgRequestFactory::SORT_BY_DAY_AND_CHAN_NAME] = LANG_EPG_OPTS_SORT_BY_DAY_AND_CHANNEL;
			$keyVal[VdrEpgRequestFactory::SORT_BY_CHAN_NAME] = LANG_EPG_OPTS_SORT_BY_CHANNEL;
			$keyVal[VdrEpgRequestFactory::SORT_BY_TIME] = LANG_EPG_OPTS_SORT_BY_TIME;
			$keyVal[VdrEpgRequestFactory::SORT_BY_DURATION] = LANG_EPG_OPTS_SORT_BY_DURATION;
			return HTML::getCombo($keyVal, $id, $selected);
		}
		
		/** get the EPG-time combo */
		public static function getEpgTime($id, $selected) {
			$keyVal = array();
			$keyVal[VdrEpgRequestFactoryParams::SEARCH_TIME_NOW] = LANG_EPG_OPTS_TIME_NOW;
			$keyVal[VdrEpgRequestFactoryParams::SEARCH_TIME_NEXT] = LANG_EPG_OPTS_TIME_NEXT;
			$keyVal['2015'] = LANG_EPG_OPTS_TIME_2015;
			$keyVal['2200'] = LANG_EPG_OPTS_TIME_2200;
			return HTML::getCombo($keyVal, $id, $selected);
		}
	
		/** get all channels as combo box */
		public static function getChannels($id, $selected) {
			$keyVal = array();
			$channels = Factory::getSqlite()->getAllChannels();
			foreach ($channels as $channel) {
				$keyVal[$channel->getIndex()] = $channel->getName();
			}
			return HTML::getCombo($keyVal, $id, $selected);
		}
		
		/** get a list of user-configured channel-filters */
		public static function getUserChannelFilter($id, $selected) {
			$files = ChannelFilterUserlist::getAvailableFilters();
			$keyVal = array();
			$keyVal[''] = 'none';
			foreach ($files as $file) {
				$keyVal[$file] = $file;
			}
			return HTML::getCombo($keyVal, $id, $selected);
		}
	
	}
	

?>
