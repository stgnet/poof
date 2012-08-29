<?php

class uiButton extends uiElement
{
	private $text;
	private $href;

	function __construct($text,$href)
	{
		$this->UniqName();
		//$this->ui_class="navbar";
		$this->text=$text;
		$this->href=$href;
	}

	function __toString()
	{
		$output="<a class=\"btn\" href=\"{$this->href}\">".htmlentities($this->text)."</a>";
		return($output.$this->GenerateContent());
	}
}
