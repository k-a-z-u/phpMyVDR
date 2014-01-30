<?php

	/**
	 * provides filtering for channels for better visibility within EPG
	 */
	interface ChannelFilter {
		
		/** return only the entries of the provided array, that match the filter-criteria */
		public function getFiltered(array $entries);
		
		/** check if one specific entry matches the filter */
		public function matches($entry);
		
	}

?>