<?php
class pfGtalk extends pfBase
{
    private $to;
    public function __construct()
    {
        global $gtalk_daemon;
        $gtalk_daemon=pfDaemon("gtalk");
    }
    public function Login($user,$pass)
    {
        global $gtalk_daemon;
        $gtalk_daemon->Login($user,$pass);
        return($this);
    }
    public function To($to)
    {
        $this->to=$to;
        return($this);
    }
    public function Send($msg)
    {
        global $gtalk_daemon;
        $gtalk_daemon->Send($this->to,$msg);
        return($this);
    }
}
