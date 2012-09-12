<?php

class uiButton extends uiElement
{
	function __construct($text,$href=false)
	{
		parent::__construct();
		if ($href)
		{
			$this->ui_tag="a";
			$this->ui_class="btn";
			$this->ui_attr="href=\"$href\"";
			$this->ui_text=$text;
		}
		else
		{
			$this->ui_tag="button";
			$this->ui_class="btn";
			$this->ui_text=$text;
		}
	}
}