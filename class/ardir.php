<?php

	class arDir extends arBase
	{
		public function __construct($path=false)
		{
			if (!$path)
				$path=".";
			$d=dir($path);
			if ($path=="." || $path=="./") $path="";
			if ($path && substr($path,-1,1)!="/") $path.="/";
			while ($file=$d->read())
				$this[]=$path.$file;
		}
	}
