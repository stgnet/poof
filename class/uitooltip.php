<?php

class uiTooltip extends uiElement
{
	function __construct($text)
	{
		parent::__construct();
		$this->ui_tag="a";
		//$this->ui_attr="rel=\"tooltip\" title=\"".htmlentities($text)."\"";
		$this->AddAttr('rel',"tooltip");
		$this->AddAttr('title',$text);
	}
	function PreGenerate($page)
	{
		$page->ReadyScript('tooltip',"\$('a[rel=\"tooltip\"]').tooltip();");
	}
}
