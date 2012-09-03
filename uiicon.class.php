<?php

class uiIcon extends uiElement
{
	function __construct($name)
	{
		parent::__construct();
		$this->ui_tag="i";
		$this->ui_class=$name;
	}
}
