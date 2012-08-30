<?php

class uiPre extends uiElement
{
	private $text;

	function __construct($text)
	{
		parent::__construct();
		$this->text=$text;
	}

	function __toString()
	{
		$output=$this->Indent()."<pre>".htmlentities($this->text);

		return($output.$this->GenerateContent()."</pre>");
	}
}
