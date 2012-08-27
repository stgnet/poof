<?php

spl_autoload_register('poof_autoload');

require_once(dirname(__FILE__)."/error_handler.php");
require_once(dirname(__FILE__)."/ui_functions.php");

// pass all php errors through our handler
set_error_handler('poof_error_handler');
error_reporting(-1);

// security considerations
libxml_disable_entity_loader(true);

function poof_locate()
{
	global $POOF_DIR;
	global $POOF_URL;

	// locate the poof library itself, set global

	$paths=array(".","poof","../poof","modules/stgnet_poof","../stgnet_poof");

	foreach ($paths as $path)
	{
		$fullpath="$path/poof.php";
		if (file_exists($fullpath))
		{
			$POOF_URL=dirname($_SERVER['SCRIPT_NAME'])."/".$path;
			if ($path==".") $POOF_URL=substr($POOF_URL,0,-2);
			if (substr($path,0,3)=="../") $POOF_URL=dirname(dirname($_SERVER['SCRIPT_NAME'])).substr($path,2);
			return($POOF_DIR=$path);
		}
	}

	Fatal("unable to locate file path to poof library. \nLooking in: ".implode(", ",$paths)."\n");
}

// automatically load class files from the library when instantiated
function poof_autoload($class)
{
	global $POOF_DIR;

	if (empty($POOF_DIR))
		poof_locate();

	$file=strtolower("{$class}.class.php");

	if (!empty($POOF_DIR))
	{
		$path="{$POOF_DIR}/$file";
		if (is_file($path))
		{
			require_once($path);
			return(true);
		}

	}

	Fatal("unable to locate file path to poof/$file. \nLooking in: $POOF_DIR\n");
}

?>
