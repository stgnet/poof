<?php

class uiHero extends uiElement
{
	function __construct()
	{
		$this->UniqName();
		$this->ui_class="hero-unit";
	}

	function __toString()
	{
		return($this->GenerateContent());
	}
}
