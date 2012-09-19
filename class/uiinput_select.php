<?php

class uiInput_Select extends uiInput_Base
{
	function __construct($attr=false)
	{
		if (empty($attr['options']))
			Fatal("no options provided");

		$valid=array('type','name');
		parent::__construct($attr,$valid);

		$this->ui_tag="select";

		foreach ($attr['options'] as $option)
			$this->ui_html.=$this->Tag("option",$option);
	}
}
