<?php

// Daemon provides background server with IPC via TCP localhost

define("PFD_REQ_NAME",1);
define("PFD_ANS_NAME",2);
define("PFD_REQ_FUNC",3);
define("PFD_ANS_FUNC",4);

class pfDaemon extends pfBase
{
    protected $name;
    protected $port;
    protected $path;
    protected $sock;

    // used by _read() only
    private $head;
    private $data;

    function __construct($name,$path=false)
    {
        $this->name=$name;
        $this->port=50000+hexdec(substr(md5($name),-3));

        $file="class/{$name}_daemon.php";
        if (!file_exists($file))
            $file=poof_locate($file);
        $this->path=$file;
        $this->sock=false;

        $this->head='';
        $this->data='';
    }
    public function _SockErr()
    {
        $errno=socket_last_error($this->sock);
        return(socket_strerror($errno)." [$errno]");
    }
    public function _Write($code,$data)
    {
        //siDiscern()->Event("pfd_write",array('code'=>$code,'data'=>$data))->Flush();

        $packet=pack("N2",$code,strlen($data));
        if (strlen($packet)!=8)
            Fatal("pfDaemon::_Request() incorrect packet length ".strlen($packet));
        $packet.=$data;
        if (socket_write($this->sock,$packet)===false)
            Warning("pfDaemon::_Request() socket_write ".
                $this->_SockErr());
    }
    public function _Read(&$code)
    {
        if (strlen($this->head)<8)
        {
            $this->data='';
            $want=8-strlen($this->head);
            //siDiscern()->Event("pfd_read",array('want'=>$want))->Flush();
            $data=socket_read($this->sock,$want);
            $got=strlen($data);
            //siDiscern()->Event("pfd_read",array('got'=>$got))->Flush();
            if ($data===false)
            {
                Warning("pfDaemon::_Read() socket_read ".$this->SockErr());
                return(false);
            }
            $this->head.=$data;
            if (strlen($this->head)<8)
                return(false);
        }
        $unpacked=unpack("N2",$this->head);
        $code=$unpacked[1];
        $len=$unpacked[2];

        if (strlen($this->data)<$len)
        {
            $want=$len-strlen($this->data);
            //siDiscern()->Event("pfd_read",array('want'=>$want))->Flush();
            $data=socket_read($this->sock,$len-strlen($this->data));
            $got=strlen($data);
            //siDiscern()->Event("pfd_read",array('got'=>$got))->Flush();
            if ($data===false)
            {
                Warning("pfDaemon::_Read() socket_read ".$this->SockErr());
                return(false);
            }
            $this->data.=$data;
            if (strlen($this->data)<$len)
                return(false);
        }
        $this->head='';
        return($this->data);
    }
    public function _Request($data)
    {
        $timeout=10;
        $started=time();
        while (time()-$started<$timeout)
        {
            if (!$this->sock)
            {
                $this->sock=socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
                if (!$this->sock)
                {
                    Warning("pfDaemon::_Request() socket_create: ".
                        $this->_SockErr());
                    sleep(1);
                    continue;
                }
                if (socket_connect($this->sock,'localhost',$this->port)===false)
                {
                    if (socket_last_error($this->sock)!=111)
                    {
                        Warning("pfDaemon::_Request() socket_connect localhost:$this->port ".$this->_SockErr());
                        socket_close($this->sock);
                        $this->sock=false;
                        sleep(1);
                        continue;
                    }

                    // connection refused indicates daemon is not running
                    $error=shell_exec("php {$this->path} -daemon");
                    if ($error)
                        Warning("pfDaemon::_Request() fork of {$this->path} had result: $error");

                    // retry several times quickly to avoid delay
                    $retry=100;
                    while (1)
                    {
                        if (!$retry)
                        {
                            socket_close($this->sock);
                            $this->sock=false;
                            break;
                        }
                        $retry--;
                        usleep(10000); // 100th of a second
                        if (socket_connect($this->sock,'localhost',$this->port)===false)
                            continue;
                        // got a connection, drop through
                        break;
                    }
                    if (!$this->sock)
                        continue;
                }

                // after connect, always confirm identity
                $this->_Write(PFD_REQ_NAME,"");
                $response=$this->_Read($code);
                if (!$response || $code!=PFD_ANS_NAME)
                {
                    socket_close($this->sock);
                    $this->sock=false;
                    sleep(1);
                    continue;
                }
                if ($response!=$this->name)
                {
                    Warning("pfDaemon::_Request() connected to '$response' wanted '{$this->name}'");
                    socket_close($this->sock);
                    $this->sock=false;
                    sleep(1);
                    continue;
                }
            }
            $this->_Write(PFD_REQ_FUNC,$data);
            $response=$this->_Read($code);
            if (!$response || $code!=PFD_ANS_FUNC)
            {
                socket_close($this->sock);
                $this->sock=false;
                sleep(1);
                continue;
            }
            return($response);
        }
        Fatal("pfDaemon:_Request() timed out");
    }

    public function __call($name,$args)
    {
        $data=json_encode(array('name'=>$name,'args'=>$args));
        $response=json_decode($this->_Request($data),true);
        if (!empty($response['return']))
            return($response['return']);
        Fatal("pfDaemon:_call($name) returned ".print_r($response,true));
    }
}
