<?php

class uiLink extends uiElement
{
	function __construct($href,$text=false)
	{
		parent::__construct();
		$this->ui_tag="a";
		$this->ui_text=$text;
		$this->ui_attr="href=\"$href\"";
	}
/*
	function __toString()
	{
		return($this->Tag("a href=\"{$this->href}\"",
			$this->text.$this->GenerateContent()
		));
	}
*/
}
