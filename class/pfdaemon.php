<?php

// Daemon provides background server with IPC via TCP localhost
//declare(ticks=1);

class pfDaemon extends pfBase
{
    protected $name;
    protected $port;
    protected $altp;
    protected $path;
    protected $sock;

    // used by _read() only
    private $head;
    private $data;

    public function __construct($name,$path=false)
    {
        $this->name=$name;

        $unique=hexdec(substr(md5($name),-3));
        $this->port=50000+$unique;
        $this->altp=49999-$unique;

        $file="class/{$name}_daemon.php";
        $path=poof_locate($file);
        if (!$path)
            Fatal("Unable to locate $file");
        $this->path=$path;
        $this->sock=false;

        $this->head='';
        $this->data='';
    }
    public function __destruct()
    {
        if ($this->sock)
            socket_close($this->sock);
    }
    public function _SockErr()
    {
        $errno=socket_last_error($this->sock);
        return(socket_strerror($errno)." [$errno]");
    }
    public function _Write($data)
    {
        //siDiscern()->Event("pfd_write",array('data'=>$data))->Flush();

        $packet=pack("N",strlen($data));
        if (strlen($packet)!=4)
            Fatal("pfDaemon::_Request() incorrect packet length ".strlen($packet));
        $packet.=$data;
        if (socket_write($this->sock,$packet)===false)
            Warning("pfDaemon::_Request() socket_write ".
                $this->_SockErr());
    }
    public function _Read()
    {
        if (strlen($this->head)<4)
        {
            // first, fail if there isn't data to read 
            $r=array($this->sock);
            $w=NULL;
            $e=NULL;
            $select=socket_select($r,$w,$e,5);
            if ($select===false)
            {
                Warning("pfDaemon::_Read() socket_select: ".$this->_SockErr());
                return(false);
            }
            if (!$select)
            {
                Warning("pfDaemon::_Read() no data to read after 5 secs");
                return(false);
            }

            $this->data='';
            $want=4-strlen($this->head);
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
            if (strlen($this->head)<4)
                return(false);
        }
        $unpacked=unpack("N",$this->head);
        $len=$unpacked[1];
            //siDiscern()->Event("pfd_read",array('len'=>$len))->Flush();
        if ($len>32767)
            Fatal("Invalid size ".bin2hex($this->head));

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
        $timeout=15;
        $started=time();
        while (time()-$started<$timeout)
        {
            if (!$this->sock)
            {
                    //siDiscern()->Event("create")->Flush();
                $this->sock=socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
                if (!$this->sock)
                {
                    Warning("pfDaemon::_Request() socket_create: ".
                        $this->_SockErr());
                    sleep(1);
                    continue;
                }
                    //siDiscern()->Event("connect",array('to'=>"127.0.0.1:{$this->port}"))->Flush();
                if (socket_connect($this->sock,'127.0.0.1',$this->port)===false &&
                    socket_connect($this->sock,'127.0.0.1',$this->altp)===false)
                {
                    if (socket_last_error($this->sock)!=111)
                    {
                        Warning("pfDaemon::_Request() socket_connect: ".$this->_SockErr());
                        socket_close($this->sock);
                        $this->sock=false;
                        sleep(1);
                        continue;
                    }

                    // connection refused indicates daemon is not running
                    $cmd="php {$this->path} -daemon";
                    //siDiscern()->Event("exec",array('cmd'=>$cmd))->Flush();
                    $error=shell_exec($cmd);
                    if ($error)
                        Warning("pfDaemon::_Request() fork of {$this->path} had result: $error");
                    //siDiscern()->Event("exec-complete",array('cmd'=>$cmd))->Flush();

                    socket_close($this->sock);
                    $this->sock=false;
                    sleep(1);
                    continue;


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
                $response=$this->_Read();
                if (!$response)
                {
                    Warning("no response after connect");
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
            $this->_Write($data);
            $response=$this->_Read();
            if (!$response)
            {
                Warning("No response to request");
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
        if (is_array($response) && array_key_exists('return',$response))
            return($response['return']);

        if (is_array($response) && array_key_exists('error',$response))
            Fatal("{$this->name}::$name(): ".$response['error']);

        Fatal("pfDaemon:_call($name) returned ".print_r($response,true));
    }
}
