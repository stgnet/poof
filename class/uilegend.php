<?php

class uiLegend extends uiElement
{
	function __construct($text=false)
	{
		parent::__construct();
		$this->ui_tag="legend";
		$this->ui_text=$text;
	}
}
