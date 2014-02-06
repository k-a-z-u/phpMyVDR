<?php
	
	ob_start('ob_gzhandler');
	
	header('Content-Type: text/html; charset=iso-8859-1');
	
	$start = microtime(true);
	
	include "settings.php";
	include "pages/menu.php";
	include "lib/PluginSystem.php";
	
	// initialize all plugins
	PluginSystem::get()->loadPlugins();
	
	// check what to create
	$page = stripslashes( @$_GET['page'] );
	if (!$page) {$page = "Start";}
	// FIXME: we need a better way to do this
	if(file_exists("./ext/tmp_vdr_epg_progress.dat") || file_exists("tmp_vdr_epg_rebuild.lock")){
		$page = "EPGupdate";
	}
	
	// FIXME: what to do with this old rss thing?
	$scripts = '
		<link href="./rss.php" rel="alternate" type="application/rss+xml" title="EPG RSS" />
	';
	
	Factory::getPage()->addCSS('{PATH}/style.css');
	Factory::getPage()->addScript('lib/tooltip/tooltip.js');
	Factory::getPage()->addScript('functions.js');
	Factory::getPage()->addScript('timeline.js');
	
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
		$file = 'pages/'.$className.'.php';
		if (!file_exists($file)) {return;}
		include $file;
		$cls = new $className();
		return $cls->getHTML();
	}
	
	
	
?>
