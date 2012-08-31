<?php

class uiButton extends uiElement
{
	private $text;
	private $href;

	function __construct($text,$href)
	{
		parent::__construct();
		//$this->ui_class="navbar";
		$this->text=$text;
		$this->href=$href;
		$this->ui_class="btn";
	}

	function __toString()
	{
		return($this->Tag("a href=\"{$this->href}\"",htmlentities($this->text)).$this->GenerateContent());
	}
}
