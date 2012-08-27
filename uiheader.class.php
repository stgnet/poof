<?php

class uiHeader extends uiElement
{
	private $text;

	function __construct($text)
	{
		$this->UniqName();
		$this->text=$text;
	}

	function __toString()
	{
		return("<h1>".htmlentities($this->text)."</h1>". $this->GenerateContent());
	}
}
