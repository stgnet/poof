<?php

class uiLead extends uiElement
{
	private $text;

	function __construct($text)
	{
		parent::__construct();
		$this->ui_class="lead";
		$this->text=$text;
	}

	function __toString()
	{
		$output=$this->Indent()."<p>".htmlentities($this->text);

		return($output.$this->GenerateContent()."</p>");
	}
}
