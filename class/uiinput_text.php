<?php

// These are alternate names for this class:
// POOF_CONSTRUCT: uiInput_Password
// POOF_CONSTRUCT: uiInput_hidden
// POOF_CONSTRUCT: uiInput_image
// POOF_CONSTRUCT: uiInput_reset
// POOF_CONSTRUCT: uiInput_submit

// HTML5:
// POOF_CONSTRUCT: uiInput_color
// POOF_CONSTRUCT: uiInput_date
// POOF_CONSTRUCT: uiInput_datetime
// POOF_CONSTRUCT: uiInput_datetime_local
// POOF_CONSTRUCT: uiInput_email
// POOF_CONSTRUCT: uiInput_month
// POOF_CONSTRUCT: uiInput_number
// POOF_CONSTRUCT: uiInput_range
// POOF_CONSTRUCT: uiInput_search
// POOF_CONSTRUCT: uiInput_tel
// POOF_CONSTRUCT: uiInput_time
// POOF_CONSTRUCT: uiInput_url
// POOF_CONSTRUCT: uiInput_week

class uiInput_Text extends uiInput_Base
{
	function __construct($attr=false)
	{
		$valid=array('type','name');
		parent::__construct($attr,$valid);

		$this->ui_tag="input";
	}
}
