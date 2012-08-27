<?php

class uiDebug extends uiElement
{
	private $what;

	function __construct($what=false)
	{
		$this->UniqName();
		$this->what=$what;
	}

	function __toString()
	{
		$output="<style type=\"text/css\">pre {background: #eee;}</style>";

		$output.="<pre>";
		if (empty($this->what))
			$output.=htmlentities(print_r($GLOBALS,true));
		else
		{
			$output.="[".$this->what."] => ";
			if (isset($GLOBALS[$this->what]))
				$output.=htmlentities(print_r($GLOBALS[$this->what],true));
			else
				$output.="** ERROR: NOT SET **";
		}
		$output.="</pre>\n";

		return($output.$this->GenerateContent());
	}
}
