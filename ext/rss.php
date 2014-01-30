<?php

	include "../settings.php";

	$search = htmlentities( @$_GET['search'] );
	$title = "EPG: " . $search;
	$rss = new RSS_Creator($title, ROOT_URL, "TV RSS");
	
	header("Content-type:application/rss+xml");
	
	$date = date('D, d M Y H:i:s T', time());
	
	$rss->addChannelParameter('language', 'de-de');
	$rss->addChannelParameter('copyright', 'vdr php');
	$rss->addChannelParameter('pubDate', $date);
	$rss->addNamespace('vdr', 'http://vdr.org/');
	
	// open DB
	$db = new VdrEpgSqlite('../' . SQLITE_EPG_FILE);
	
	$entries = VdrEpgRequestFactory::get($db, $_GET); //$db->getEpgContainsString($search);
	$url = ROOT_URL;
	
	// get all entries
	foreach($entries as $entry) {
		$title = $entry->getChannel()->getName() . ': ' . $entry->getTitle() . ' - ' . $entry->getDescShort();
		$from = MyDate::getDateTime($entry->getEvent()->getTsStart());
		$to = MyDate::getDateTime($entry->getEvent()->getTsEnd());
		$desc  = "channel: " . $entry->getChannel()->getName() . "<br>";
		$desc .= "date: " . $from . "<br>";
		$desc .= "desc: " . $entry->getDescLong() . "<br>";
		$guid = urlencode($entry->getChannel()->getName()) . $entry->getEvent()->getTsStart();
		$recUrl = ROOT_URL . '/index.php?page=TimerEdit&amp;action=newFromShow&amp;showID=' . $entry->getDbId();
		
		// create the item
		$item = $rss->addItem($title, $recUrl, $desc);
		$item->addElement('guid', ($url . $guid));
		$item->addElement('vdr:channel', $entry->getChannel()->getName());
		$item->addElement('vdr:start', $entry->getEvent()->getTsStart());
		$item->addElement('vdr:end', $entry->getEvent()->getTsEnd());
		
	}
	
	
	$rss->EchoRSS();
	
?>
