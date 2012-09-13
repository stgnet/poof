<?php

class uiRow extends uiElement
{
	function __construct($class=false)
	{
		parent::__construct();
		$this->ui_tag="div";
		$this->ui_class="row";
		if ($class)
			$this->AddClass($class);
	}
}
