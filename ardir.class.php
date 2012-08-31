<?php

	class arDir extends arBase
	{
		public function __construct($path)
		{
			$d=dir($path);
			while ($file=$d->read())
				$this[]=$file;
		}
	}
