<?php
	// run this after library changes to regenerate support files
	require("error_handler.php");

	function make_constructors()
	{
		// update the convenience function list from classes
		$output='<'.'?php'."\n// generated by make.php - do not edit\n";

		// scan files in the directory
		$d=dir("class");
		$files=array();
		while ($file=$d->read())
			$files[]=$file;
		sort($files);

		foreach ($files as $file)
		{
			// skip anything that isn't an autoload-able class
			if (!preg_match('/^(.*)\.php$/',$file,$match))
			{
				if ($file[0]!='.') print("WARNING: skipping $file\n");
				continue;
			}

			$class=$match[1];
//print($class."\n");
			$contents=file_get_contents("class/$file");

			// locate the construct function to get args - and warn if not found
			if (!preg_match('/function\s+__construct\((.*)\)/',$contents,$match))
			{
				print("$class: __construct() not found!\n");
				continue;
			}
			$args=$match[1];

			// break apart the argument list and remove default assignments
			$pairs=explode(',',$args);
			foreach ($pairs as &$pair)
			{
				$exp=explode('=',$pair);
				$pair=$exp[0];
			}
			$justargs=implode(',',$pairs);

			$output.="
function $class($args)
{
	return new $class($justargs);
}
";


			preg_match_all('/POOF_CONSTRUCT:\s+(\S+)/',$contents,$matches,PREG_SET_ORDER);
			foreach ($matches as $match)
			{
				$alternate=strtolower($match[1]);
				//print("Adding alternate $alternate for $class\n");

				$output.="
function $alternate($args)
{
	return new $class($justargs);
}
";

			}
		}

		file_put_contents("class_constructors.php",$output);
	}

	// make all the components needed
	make_constructors();


