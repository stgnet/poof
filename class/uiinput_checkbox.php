<?php

class uiInput_Checkbox extends uiInput_Base
{
	function __construct($attr=false)
	{
		$valid=array('type','name');
		parent::__construct($attr,$valid);

		$this->ui_tag="input";
	}
	function SetInlineDescription($desc)
	{
		$this->Add(htmlentities($desc));
	}
}
