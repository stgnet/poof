<?php

class uiHero extends uiElement
{
	function __construct()
	{
		parent::__construct();
		$this->ui_class="hero-unit";
	}

	function __toString()
	{
		return($this->GenerateContent());
	}
}
