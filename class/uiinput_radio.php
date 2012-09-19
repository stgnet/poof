<?php

class uiInput_Radio extends uiInput_Base
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
