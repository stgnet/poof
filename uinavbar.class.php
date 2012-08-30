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
		$output=$this->Indent(1)."<ul class=\"nav\">";
		foreach ($this->menu as $name => $href)
			$output.=$this->Indent()."<li><a href=\"$href\">".htmlentities($name)."</a></li>";
		$output.=$this->Indent(-1)."</ul>";
		return($output.$this->GenerateContent());
	}
}
