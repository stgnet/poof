<?php

require_once(dirname(dirname(__FILE__))."/poof.php");
require_once(dirname(dirname(__FILE__))."/XMPPHP/XMPP.php");

siError()->IgnoreFunction("stream_select");

class gtalk_daemon extends pfDaemonServer
{
    private $conn;

    public function __construct()
    {
        $this->conn=false;
        parent::__construct('gtalk');
    }
    public function __destruct()
    {
        if ($this->conn)
            $this->conn->disconnect();
    }
    public function _HandleMessage($from,$msg)
    {
        $cmd=explode(" ",trim($msg),2);
        if (empty($cmd[0]))
            return $this->conn->message($from,"decode error: ".print_r($cmd));

        $php=$cmd[0].".php";

        if (file_exists($php))
        {
            $exec="php ".$php." ".empty($cmd[1])?'':$cmd[1];
            $output=shell_exec($exec);
            return $this->conn->message($from,$output);
        }

        $this->conn->message($from,"Unable to locate command ".$cmd[0]);
    }
    public function _Process()
    {

        global $POOF_DIR;
        if (!$this->conn)
            return;

        /*
        if ($this->conn->disconnected)
        {
            $this->conn=false;
            return;
        }
        */

        $types=array('message','presence','end_stream','session_start');
        $payloads=$this->conn->processUntil($types,1);
        foreach ($payloads as $event)
        {
            $pl=$event[1];
            switch ($event[0])
            {
                case 'message':
                    //HandleReceivedMessage($pl['from'],$pl['body']); // $pl['type']=="chat"
                    //$this->conn->message($pl['from'],"body=".$pl['body']);
                    $this->_HandleMessage($pl['from'],$pl['body']);
                    break;
                case 'presence':
                    //HandlePresence($pl['from'],$pl['show'],$pl['status']);
                    //echo "Presence: ".$pl['from']." show=".$pl['show']." status=".$pl['status']."\n";
                    break;
                case 'session_start':
                    $this->conn->presence($status=($_SERVER['HOSTNAME'].":".$POOF_DIR));
                    break;
                default:
                    Fatal("unknown event: '{$event[0]}' ".print_r($pl,true));
            }
        }
    }
    public function Send($to,$msg)
    {
        if (!$this->conn)
            Fatal("not connected - login first");
        $this->conn->message($to,$msg);
    }
    public function Login($user,$pass)
    {
        if ($this->conn)
            return;

            $this->conn=new XMPPHP_XMPP(
                'talk.google.com',
                5222,
                $user,
                $pass,
                'xmpphp',
                'gmail.com',
                $printLog=false,
                $loglevel=0
            );
        $this->SetTimeout(300);
    }
}


if (!empty($argv[1]) && $argv[1]=="-daemon") new gtalk_daemon();
