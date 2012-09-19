<?php

class uiInput_Text extends uiInput_Base
{
	function __construct($attr=false)
	{
		$valid=array('type','name','rows','cols');
		parent::__construct($attr,$valid);

		$this->ui_tag="textarea";
	}
}
