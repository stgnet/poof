<?php
class event extends pfBase
{
    protected $EventList;

    public function __construct()
    {
        $this->EventList=array();
    }

    public function EventNotify($who,$which=null)
    {
        $this->EventList[]=array('who'=>$who,'which'=>$which);
    }
    public function EventTrigger($which,$args)
    {
        if ($this->EventList) foreach ($this->EventList as $event) {
            if (!$event['which'] || in_array($which,$event['which'])) {
/*
                if (is_array($event['who']))
                    ($event['who'][0])->($event['who'][1])($which,$args);
                else
*/
                    $event['who']($which,$args);
            }
        }
    }
}
