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

// POOF:
// POOF_CONSTRUCT: uiInput_key

class uiInput_Text extends uiInput_Base
{
    public function __construct($attr=false)
    {
        if (empty($attr['type']))
            $attr['type']="text";
        if ($attr['type']=="key")
        {
            $attr['type']="text";
            $attr['disabled']=true;
        }

        $valid=array('type','value','disabled');
        parent::__construct($attr,$valid);

        $this->ui_tag="input";

        if (!empty($attr['options'])) 
        {
            $listname=$this->ui_id."_list";
            $this->AddAttr('list',$listname);
            $list='';
            foreach($attr['options'] as $option)
                $list.=$this->Tag("option value=\"$option\"");
            $this->ui_html.=$this->Tag("datalist id=\"$listname\"",$list);
        }

    }
}
