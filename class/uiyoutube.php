<?php

class uiYoutube extends uiElement
{
	function __construct($url,$width=640,$height=false)
	{
		if (!$height)
			$height=round($width/1.777);

		parent::__construct();
		$this->ui_tag="iframe";
		$this->ui_attr="width=\"$width\" height=\"$height\" src=\"$url\" frameborder=\"0\" allowfullscreen";
	}
}
