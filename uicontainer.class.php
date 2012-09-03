<?php

class uiContainer extends uiElement
{
	function __construct($class=false)
	{
		parent::__construct();
		$this->ui_tag="div";
		$this->ui_class="container";
		if ($class)
			$this->ui_class.=" ".$class;
	}
}
