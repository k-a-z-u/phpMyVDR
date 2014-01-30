<?php

	require_once '../setup.php';

	// set the content type
	header('Content-Type: audio/x-mpegurl');
	
	// set the filename
	header('Content-Disposition: inline; filename="channel.m3u"');

	// get the channel
	$channel = $_GET['channel'];

	// output "playlist"
	echo (STREAMDEV_SERVER . $channel);

?>
