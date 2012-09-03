<?php

class uiList extends uiElement
{
	private $list;

	function __construct($list)
	{
		parent::__construct();
		$this->ui_tag="ul";
		$this->list=$list;
	}

	function __toString()
	{
		$list='';
		foreach ($this->list as $name => $href)
			$list.=$this->Tag("li",
				$this->Tag("a href=\"$href\"",htmlentities($name))
			);

		return($list.$this->GenerateContent());
	}
}
