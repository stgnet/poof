<?php
	// run this after library changes to regenerate support files

	function make_constructors()
	{
		// update the convenience function list from classes
		$output='<'.'?php';

		$d=dir(".");
		while ($file=$d->read())
		{
			if (preg_match('/^(.*)\.class\.php$/',$file,$match))
			{
				$class=$match[1];
				$contents=file_get_contents($file);

				if (!preg_match('/function\s+__construct\((.*)\)/',$contents,$match))
				{
					print("$class: __construct() not found!\n");
					continue;
				}
				$args=$match[1];

				// break apart the argument list and remove default assignments
				$justargs=array();
				$pairs=explode(',',$args);
				foreach ($pairs as $pair)
				{
					$exp=explode('=',$pair);
					$justargs[]=$exp[0];
				}
				$justargs=implode(',',$justargs);

				$output.="
function $class($args)
{
	return new $class($justargs);
}
";
	
			}
		}
		file_put_contents("class_constructors.php",$output);
	}

	make_constructors();


