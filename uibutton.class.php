<?php

class uiButton extends uiElement
{
	function __construct($text,$href)
	{
		parent::__construct();
		$this->ui_tag="a";
		$this->ui_class="btn";
		$this->ui_attr="href=\"$href\"";
		$this->ui_text=$text;
	}
}
