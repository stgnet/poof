<?php

spl_autoload_register('poof_autoload');

require_once(dirname(__FILE__)."/class_constructors.php");
require_once(dirname(__FILE__)."/error_handler.php");

// pass all php errors through framework for better diagnostics
set_error_handler('poof_error_handler');
error_reporting(-1);

// security considerations
libxml_disable_entity_loader(true);

function poof_locate()
{
	global $POOF_DIR;
	global $POOF_URL;

	// locate the poof library itself, set globals

	$POOF_DIR=dirname(__FILE__);
	if (!file_exists("$POOF_DIR/poof.php"))
		Fatal("unable to locate file path to poof library");

	$DocumentRoot=str_replace($_SERVER['SCRIPT_NAME'],"",$_SERVER['SCRIPT_FILENAME']);
	$POOF_URL=str_replace($DocumentRoot,"",dirname(__FILE__));

	return($POOF_DIR);
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

	Fatal("unable to locate file path to poof/$file in $POOF_DIR\n");
}
