<?php

spl_autoload_register('poof_autoload');

require_once(dirname(__FILE__)."/error_handler.php");
require_once(dirname(__FILE__)."/class_constructors.php");

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

	//$paths=array(dirname(__FILE__),".","poof","../poof","modules/stgnet_poof","../stgnet_poof");

	//foreach ($paths as $path)
	//{
	$path=dirname(__FILE__);
	$fullpath="$path/poof.php";
	if (!file_exists($fullpath))
		Fatal("unable to locate file path to poof library. \nLooking in: ".implode(", ",$paths)."\n");

/*
	$POOF_URL=dirname($_SERVER['SCRIPT_NAME'])."/".$path;
	if ($path==".") $POOF_URL=substr($POOF_URL,0,-2);
	if (substr($path,0,3)=="../") $POOF_URL=dirname(dirname($_SERVER['SCRIPT_NAME'])).substr($path,2);
*/

	$DocumentRoot=str_replace($_SERVER['SCRIPT_NAME'],"",$_SERVER['SCRIPT_FILENAME']);
	$POOF_URL=str_replace($DocumentRoot,"",dirname(__FILE__));

/*
echo "<pre>";
echo "DIR=".$path."\n";
echo "URL=".$POOF_URL."\n";
echo "FILE=".__FILE__."\n";
echo "ROOT=".$DocumentRoot."\n";
print_r($_SERVER);
exit(0);
*/

	return($POOF_DIR=$path);
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
