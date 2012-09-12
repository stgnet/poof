<?php

class uiLink extends uiElement
{
	function __construct($href,$text=false)
	{
		parent::__construct();
		$this->ui_tag="a";
		$this->ui_text=$text;
		$this->ui_attr="href=\"$href\"";
	}
}