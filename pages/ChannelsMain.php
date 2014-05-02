<?php


	
	
	
	/** the Channels-Main page */
	class ChannelsMain implements Content {

		/** ctor */
		public function __construct() {}
	
		/** get HTML output */
		public function getHTML() {
			
			$index = @$_GET['index'];
			if ($index) {
				return $this->deleteChannel($index);
			} else {
				return $this->getList();
			}
			
		}
		
		/** get a list of channels */
		private function getList() {
		
			$tpl = new Template('channels_page');
			$tplChan = new Template('channels_channel');
			$db = Factory::getSqlite();
			$out = '';
						
			// add each channel
			$chans = $db->getAllChannels();
			foreach ($chans as $channel) {
				
				$tplChan->set('URL_DELETE', '?page=Channels&index='.$channel->getIndex());
				$tplChan->set('URL_WATCH', "ext/m3u.php?channel={$channel->getCode()}");
				$tplChan->set('NAME', $channel->getName());
				$tplChan->set('INDEX', $channel->getIndex());
				$tplChan->set('CODE', $channel->getCode());
				$out .= $tplChan->get();
				
			}
			
			// set in main template
			$tpl->setUnsafe('ENTRIES', $out);
			return $tpl->get();
			
		}
		
		
		/** delete the given channel and show a response */
		private function deleteChannel($index) {
		
			// delete the channel from VDR
			$con = Factory::getVdrConnection();
			$resp = $con->request('DELC ' . $index);
			$resp->assertCode(250);
			
			// as this will change the channel indexes, the database has to be changed
			$db = Factory::getSqlite();
			$db->deleteChannelAndUpdate($index);
		
			// display message for confirmation and to inform the user to run an EPG-update
			Message::showInfo(LANG_CHANNELS_MSG_DELETED);
		
		}
		
	}

?>
