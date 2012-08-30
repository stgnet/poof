<?php

class uiContainer extends uiElement
{
	function __construct($class=false)
	{
		$this->UniqName();
		$this->ui_class="container";
		if ($class)
			$this->ui_class.=" ".$class;
	}

	function __toString()
	{
		return($this->GenerateContent());
	}
}
