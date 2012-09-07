<?php

class uiDebug extends uiElement
{
	private $what;

	function __construct($what=false)
	{
		parent::__construct();
		$this->what=$what;
	}

	function __toString()
	{
		if (isset($GLOBALS[$this->what]))
			$text.=print_r($GLOBALS[$this->what],true);
		else
			$text.="** ERROR: {$this->what} NOT SET **";

		return($this->Tag("pre",htmlentities($text)));

	}
}
