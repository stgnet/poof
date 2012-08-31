<?php

class uiList extends uiElement
{
	private $list;

	function __construct($list)
	{
		parent::__construct();
		$this->list=$list;
	}

	function __toString()
	{
		$list='';
		foreach ($this->list as $name => $href)
			$list.=$this->Tag("li",
				$this->Tag("a href=\"$href\"",htmlentities($name))
			);

		return($this->Tag("ul",$list).$this->GenerateContent());
	}
}
