<?php

class uiDropdown extends uiElement
{
	private $trigger;
	private $list;

	function __construct($text,$list=false)
	{
		parent::__construct();
		$this->ui_tag="div";
		$this->ui_class="dropdown";
		$this->list=$list;

		$this->trigger=uiLink("#")->AddClass("dropdown-toggle")->Add(
			$text,
			"<b class=\"caret\"></b>"
		);
	}

	function __toString()
	{
		global $poof_ui_collapse;

		$triggerid=$this->trigger->ui_name;
		$list='';

/*
		if (empty($poof_ui_collapse))
		{
			$list.='<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				</a>';
			$poof_ui_collapse=true;
		}
*/

		if ($this->list) foreach ($this->list as $name => $href)
			$list.=$this->Tag("li",
				$this->Tag("a href=\"$href\"",htmlentities($name))
			);

		foreach ($this->GenerateContentArray() as $element)
			$list.=$this->Tag("li",$element);

		return($this->Tag($this->trigger->GenerateTag(),$this->trigger).
				$this->Tag("ul class=\"dropdown-menu role=\"menu\"
						aria-labelledby=\"$triggerid\"",
				$list
				)
		);
	}
}
