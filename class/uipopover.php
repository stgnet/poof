<?php

class uiPopover extends uiElement
{
	function __construct($text)
	{
		parent::__construct();
		$text=htmlentities($text);
		$this->ui_tag="a";
		$this->ui_attr="href=\"#\" rel=\"popover\" data-content=\"$text\"";
	}
}
