<?php

	class arFile extends arBase
	{
		public function __construct($file)
		{
			foreach (file($file) as $line)
				$this[]=rtrim($line);
		}
	}
