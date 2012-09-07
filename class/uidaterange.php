<?php

// plan: implement http://www.dangrossman.info/2012/08/20/a-date-range-picker-for-twitter-bootstrap/

class uiDateRange extends uiElement
{
	function __construct()
	{
	}

	function __toString()
	{
		$output="<html goes here>";

		return($output.$this->GenerateContent());
	}
}
