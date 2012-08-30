<?php

class uiDiv extends uiElement
{
	function __construct($class)
	{
		parent::__construct();
		$this->ui_class=$class;
	}

	function __toString()
	{
		return($this->GenerateContent());
	}
}
