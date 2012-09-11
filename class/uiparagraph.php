<?php

class uiParagraph extends uiElement
{
	private $text;

	function __construct($text=false)
	{
		parent::__construct();
		$this->ui_tag="p";
		$this->ui_text=$text;
	}
}
