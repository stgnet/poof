<?php

class uiDebug extends uiElement
{
	private $what;

	function uiDebug($what=false)
	{
		$this->UniqName();
		$this->what=$what;
	}

	function Generate()
	{
		echo "<style type=\"text/css\">pre {background: #eee;}</style>";

		echo "<pre>";
		if (empty($this->what))
			echo htmlentities(print_r($GLOBALS,true));
		else
		{
			echo "[".$this->what."] => ";
			if (isset($GLOBALS[$this->what]))
				echo htmlentities(print_r($GLOBALS[$this->what],true));
			else
				echo "** ERROR: NOT SET **";
		}
		echo "</pre>\n";

		$this->GenerateContent();
	}
}
