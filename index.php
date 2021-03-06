<?php
	
	// use gzip compression
	ob_start('ob_gzhandler');
	
	// set content-type and charset
	//header("Content-Type: text/html; charset=utf-8");
	
	$start = microtime(true);
	
	// needed includes
	include "settings.php";
	include "pages/menu.php";
	include "lib/PluginSystem.php";
	
	// initialize all plugins
	PluginSystem::get()->loadPlugins();
	
	// check what to create
	$page = stripslashes( @$_GET['page'] );
	if (!$page) {$page = "Start";}
	
	// FIXME: we need a better way to do this
	// FIXED: using one big transaction
	// ALTERNATIVE: epg-update into new database and overwriting old-one on completion
	//if(file_exists("./ext/tmp_vdr_epg_progress.dat") || file_exists("tmp_vdr_epg_rebuild.lock")){
	//	$page = "EPGupdate";
	//}
	
	// FIXME: what to do with this old rss thing?
	$scripts = '
		<link href="./rss.php" rel="alternate" type="application/rss+xml" title="EPG RSS" />
	';
	
	// add necessary scripts and stylesheets
	Factory::getPage()->addCSS('{PATH}/style.css');
	Factory::getPage()->addScript('lib/tooltip/tooltip.js');
	Factory::getPage()->addScript('functions.js');
	Factory::getPage()->addScript('timeline.js');
	
	// add some elements to the statusbar
	Factory::getPage()->getStatusBar()->addLeft( System::getHostname() );
	Factory::getPage()->getStatusBar()->addRight( MyDate::getDateTime(time()) );
	
	// classnames for main, top menu, etc.
	$strMain = $page . 'Main';
	$content = getContent($strMain);
	Factory::getPage()->setContent($content);
	
	// create the main menu
	Factory::getPage()->setMenu(new Menu());
	
	// create output and flush
	echo Factory::getPage()->getHTML();
	ob_end_flush();
	
	
	
	function getContent($className) {
		$file = 'pages/' . addslashes($className) . '.php';
		if (!file_exists($file)) {return;}
		include $file;
		$cls = new $className();
		return $cls->getHTML();
	}
	
	
	
?>
