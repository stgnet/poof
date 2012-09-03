<?php

class uiHtml extends uiElement
{
	function __construct($html)
	{
		parent::__construct();
		$this->ui_tag="span";
		$this->ui_html=$html;
	}
}
