<?php

class uiNavList extends uiElement
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
		{
			$extra="";
			if (basename($href)==basename($_SERVER['SCRIPT_NAME']))
				$extra=" class=\"active\"";
			$list.=$this->Tag("li$extra",
				$this->Tag("a href=\"$href\"",htmlentities($name))
			);
		}

		return($this->Tag("ul class=\"nav\"",$list).$this->GenerateContent());
	}
}
