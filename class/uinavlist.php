<?php

class uiNavList extends uiElement
{
	private $list;

	function __construct($list=false)
	{
		parent::__construct();
		$this->ui_tag="span";
		$this->list=$list;
	}

	function __toString()
	{
		// if given a 'name'=>url list, display it
		$list='';
		if ($this->list) foreach ($this->list as $name => $href)
		{
			$extra="";
			if (basename($href)==basename($_SERVER['SCRIPT_NAME']))
				$extra=" class=\"active\"";
			$list.=$this->Tag("li$extra",
				$this->Tag("a href=\"$href\"",htmlentities($name))
			);
		}
		foreach ($this->GenerateContentArray() as $element)
		{
			// make exception where element tag is 'li'
			if ($element->ui_tag=="li")
				$list.=$element;
			else
				$list.=$this->Tag("li",$element);
		}
		return($this->Tag($this->GenerateTag(),
			$this->Tag("ul class=\"nav\"",$list)
		));

		//return($this->Tag("ul class=\"nav\"",$list).$this->GenerateContent());
	}
}
