<?php

class uiLead extends uiElement
{
	function __construct($text)
	{
		parent::__construct();
		$this->ui_tag="p";
		$this->ui_class="lead";
		$this->ui_text=$text;
	}
}
