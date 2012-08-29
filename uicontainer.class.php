<?php

class uiContainer extends uiElement
{
	function __construct()
	{
		$this->UniqName();
		$this->ui_class="container";
	}

	function __toString()
	{
		return($this->GenerateContent());
	}
}
