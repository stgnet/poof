<?php

class uiSpan extends uiElement
{
	function __construct($number,$offset=false)
	{
		parent::__construct();
		$this->ui_tag="div";
		$this->ui_class="span".$number;
		if ($offset)
			$this->ui_class.=" offset".$offset;
	}
}
