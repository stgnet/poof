<?php

class uiScript extends uiElement
{
	private $js;

	function __construct($code)
	{
		$this->UniqName();
		$this->js=$code;
	}

	function __toString()
	{
		$output=$this->Indent()."<script type=\"text/javascript\">\n";
		$output.=$this->js;
		$output.=$this->Indent()."</script>";

		return($output.$this->GenerateContent());
	}
}
