<?php

	// update the ui convenience function list from classes

	$output='<'.'?php';

	$d=dir(".");
	while ($file=$d->read())
	{
		if (preg_match('/^(ui.*)\.class\.php$/',$file,$match))
		{
			$func=$match[1];
			$contents=file_get_contents($file);

			if (preg_match('/function\s+__construct\((.*)\)/',$contents,$match))
				$args=$match[1];
			else
				die("$func: no __construct!\n");

			$exp=explode('=',$args);
			$callargs=$exp[0];


			$output.="
function $func($args)
{
	return new $func($callargs);
}
";
	
		}
	}

	file_put_contents("ui_functions.php",$output);


