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
		$output=$this->Indent(1)."<ul class=\"nav\">";
		foreach ($this->list as $name => $href)
			$output.=$this->Indent()."<li><a href=\"$href\">".htmlentities($name)."</a></li>";
		$output.=$this->Indent(-1)."</ul>";
		return($output.$this->GenerateContent());
	}
}
