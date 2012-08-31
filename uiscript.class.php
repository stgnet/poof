<?php

class uiScript extends uiElement
{
	private $js;

	function __construct($code)
	{
		parent::__construct();
		$this->js=$code;
	}

	function __toString()
	{
		return($this->Tag("script type=\"text/javascript\"",$this->js).$this->GenerateContent());
	}
}
