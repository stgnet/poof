<?php

class uiNavBar extends uiElement
{
	private $menu;

	function __construct($menu)
	{
		parent::__construct();
		$this->ui_class="navbar";
		$this->menu=$menu;
	}

	function __toString()
	{
		$list='';
		foreach ($this->menu as $name => $href)
			$list.=$this->Tag("li",
				$this->Tag("a href=\"$href\"",htmlentities($name))
			);
		return($this->Tag("ul class=\"nav\"",$list).$this->GenerateContent());
	}
}
