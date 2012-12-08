<?php

class uiRelead extends uiElement
{
    protected $trackingid;

    public function __construct($trackingid)
    {
        parent::__construct();
        $this->trackingid=$trackingid;
        $this->ui_tag=false;
    }
    public function PreGenerate($page)
    {
        $trackingid=$this->trackingid;
        $page->TailScript($this->ui_id,"var releadTrackingId = '$trackingid';
	(function() {
		var rl = document.createElement('script');
		rl.type = 'text/javascript';
		rl.async = true;
		rl.src = (document.location.protocol == 'https:' ? 'https://' : 'http://') + 'relead.s3.amazonaws.com/tracking.relead.js';
		var e = document.getElementsByTagName('script')[0];
		e.parentNode.insertBefore(rl, e);
	})();
");

    }
}
