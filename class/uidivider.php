<?php

class uiDivider extends uiElement
{
	// this is meant to be added to a List object
	function __construct()
	{
		parent::__construct();
		$this->ui_tag="li";
		$this->ui_class="divider-vertical";
	}
}
