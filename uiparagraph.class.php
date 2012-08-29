<?php

class uiParagraph extends uiElement
{
	private $text;

	function __construct($text)
	{
		$this->UniqName();
		//$this->ui_class="navbar";
		$this->text=$text;
	}

	function __toString()
	{
		$output=$this->Indent()."<p>".htmlentities($this->text)."</p>";
		return($output.$this->GenerateContent());
	}
}
