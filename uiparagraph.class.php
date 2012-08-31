<?php

class uiParagraph extends uiElement
{
	private $text;

	function __construct($text)
	{
		parent::__construct();
		$this->text=$text;
	}

	function __toString()
	{
		return($this->Tag("p",htmlentities($this->text).$this->GenerateContent()));
	}
}
