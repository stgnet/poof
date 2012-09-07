<?php
	// run this after library changes to regenerate support files

	function make_constructors()
	{
		// update the convenience function list from classes
		$output='<'.'?php';

		// scan files in the directory
		$d=dir("class");
		while ($file=$d->read())
		{
			// skip anything that isn't an autoload-able class
			if (!preg_match('/^(.*)\.php$/',$file,$match))
				continue;

			$class=$match[1];
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
				$pair=explode('=',$pair)[0];
			$justargs=implode(',',$pairs);

			$output.="
function $class($args)
{
	return new $class($justargs);
}
";
		}
		file_put_contents("class_constructors.php",$output);
	}

	// make all the components needed
	make_constructors();


