<?php

	// array manipulation functions in base class for all arXyz classes to use

	class arBase extends ArrayObject
	{
		public function __construct()
		{
		}

		// eliminate entries that don't match pattern (* and ? wildcards)
		public function Match($pattern)
		{
			$pattern=str_replace("*",".*",$pattern);
			$pattern=str_replace("?",".",$pattern);
			$pattern="^$pattern$";
			return($this->PregMatch($pattern));
		}
		// eliminate entries that don't match regular expression
		public function PregMatch($regexp)
		{
			$separator="|";
			if (substr_count($regexp,$separator))
				$separator="_";
			if (substr_count($regexp,$separator))
				$separator="/";
			if (substr_count($regexp,$separator))
				$separator="~";
			$pattern=$separator.$regexp.$separator;

			$remove=array();
			foreach ($this as $index => $item)
			{
				if (!preg_match($pattern,$item))
					$remove[]=$index;
			}
			foreach ($remove as $index)
				unset($this[$index]);

			return($this);
		}
		public function Sort()
		{
			$this->asort();
			return($this);
		}
		public function __call($func,$argv)
		{
			Fatal("Unsupported call to array class: $func()");
		}
	}
