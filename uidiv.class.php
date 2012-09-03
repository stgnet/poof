<?php

class uiDiv extends uiElement
{
	function __construct($class)
	{
		parent::__construct();
		$this->ui_tag="div";
		$this->ui_class=$class;
	}
}
