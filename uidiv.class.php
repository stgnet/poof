<?php

class uiDiv extends uiElement
{
	function __construct($class)
	{
		$this->UniqName();
		$this->ui_class=$class;
	}

	function __toString()
	{
		return($this->GenerateContent());
	}
}
