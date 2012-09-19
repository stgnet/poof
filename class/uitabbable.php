<?php

class uiTabbable extends uiElement
{
	private $list;

	function __construct($list)
	{
		parent::__construct();
		$this->ui_tag="div";
		$this->ui_class="tabbable";
		$this->list=$list;
	}

	function PreGenerate($page)
	{
		$page->ReadyScript('button',"\$('.nav-tabs').button();");
	}

	function __toString()
	{
		$tabs='';
		$content='';
		$active=' active';
		if ($this->list) foreach ($this->list as $name => $element)
		{
			$content.="\n<!-- TAB: $name -->";

			$div=uiDiv("tab-pane$active");

			$tabs.=$this->Tag($active?"li class=\"active\"":"li",
				$this->Tag("a href=\"#{$div->ui_name}\" data-toggle=\"tab\"",$name)
			);

			$content.=$this->Tag($div->GenerateTag(),$element);

			$active='';
		}
			$content.="\n<!-- END TABS -->";

		return($this->Tag($this->GenerateTag(),
			$this->Tag("ul class=\"nav nav-tabs\"",$tabs).
			$this->Tag("div class=\"tab-content\"",$content).
			$this->GenerateContent()
		));

		//return($this->Tag("ul class=\"nav\"",$list).$this->GenerateContent());
	}
}
