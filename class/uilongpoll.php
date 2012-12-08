<?php

/**
 * div element with a long poll
*/

class uiLongPoll extends uiElement
{
    private $every;
    private $timeout;

    public function __construct()
    {
        parent::__construct();
        $this->ui_tag="div";

        $this->every=1*1000;
        $this->timeout=15*1000;
    }
    public function Every($secs)
    {
        $this->every=$secs*1000;
        return($this);
    }
    public function PreGenerate($page)
    {
        $url=$this->GetAction();
        $target='#'.$this->ui_id;
        $id=$this->ui_id;

        $every=$this->every;
        $timeout=$this->timeout;

        $page->HeadScript("post_$id","
            function post_$id()
            {
                \$.ajax({
                    type: 'POST',
                    url: '$url',
                    async: true,
                    cache: false,
                    timeout: $timeout,

                    success: function(data)
                    {
                        \$('$target').empty().append(data);
                        setTimeout(post_$id,$every);
                    },
                    error: function(xhr)
                    {
                        \$('$target').empty().append(xhr.status+' '+xhr.statusText+': '+xhr.responseText);
                        console.log('xhr=%o',xhr);
                        setTimeout(post_$id,$timeout);
                    }
                });
            };
        ");
                    /*
                    error: function(xhr) {
                        alert(xhr.status+\" \"+xhr.statusText+\": \"+xhr.responseText);
                        */

        $page->ReadyScript("ready_$id","post_$id();");
    }
}
