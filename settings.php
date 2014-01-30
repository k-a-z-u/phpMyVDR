<?php

	
	/** ensure setup is present */
	$SETUP_FILE = __DIR__ . '/setup.php';
	if (!file_exists( $SETUP_FILE )) {
		echo 'please create a "setup.php" first.<br/>';
		echo 'for hints see "setup.php.example"<br/>';
		echo '(in future versions there might be a convenient setup-dialog)';
		die();
	}


	/** load setup configuration */
	include $SETUP_FILE;

	/** ensure propper configuration */
	if (!file_exists(EPG_FILE)) {
		echo 'could not find the requested EPG-file: "' . EPG_FILE . '"!';
		die();
	}
	if (!is_dir(RECORDS_FOLDER)) {
		echo 'could not find the configured records folder: "' . RECORDS_FOLDER . '"!';
		die();
	}
	
	/** ensure language file is present */
	$LANG_FILE = __DIR__ . '/lang/lang_' . LANGUAGE . '.php';
	if (!file_exists($LANG_FILE)) {
		echo 'could not find the configured language file: "' . $LANG_FILE . '"!';
		die();
	}

	/** load language file */
	include $LANG_FILE;
	
	/** apply setup */
	Template::$PATH = TEMPLATE_PATH;

	/** automatically load needed classes */
	function __autoload($clsName) {
		include 'lib/' . $clsName . '.php';
	}
	
	/** configure the exception handling */
	function exception_handler($e) {
		if ($e instanceof MyException) {
			Message::showError($e->getMessage());
		} else if ($e instanceof Exception) {
			Message::showError($e->getMessage());
		}
	}
	set_exception_handler('exception_handler');

?>
