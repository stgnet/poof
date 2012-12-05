<?php

// Daemon provides background server with IPC via TCP localhost

class pfDaemonError
{
    public $error;
    function __construct($error)
    {
        $this->error=$error;
    }
}

class pfDaemonConnection extends pfDaemon
{
    private $server;
    public $name;
    public $peer;

    function __construct($sock,$server)
    {
        $this->sock=$sock;
        $this->server=$server;
        $this->_Write($server->name);
    }
    public function __destruct()
    {
        socket_close($this->sock);
    }
    function _Process()
    {
        $test=socket_recv($this->sock,$data,1,MSG_PEEK);
        if ($test===0)
        {
            //siDiscern()->Event("disconnected",array('name'=>$this->name,'peer'=>$this->peer))->Flush();
            return(false); // disconnected
        }

        $data=$this->_Read();
        if ($data===false) return(true);

        $request=json_decode($data,true);
        if (empty($request['name']))
        {
            Warning("received request with no name");
            return(false);
        }

        if (method_exists($this->server,$request['name']))
        {
            $return=call_user_func_array(array($this->server,$request['name']),$request['args']);
            if (is_a($return,"pfDaemonError"))
                $response=array('error'=>$return->error);
            else
                $response=array('return'=>$return);
        }
        else
            $response=array('error'=>"method '{$request['name']}' not found");

        $this->_Write(json_encode($response));
        return(true);
    }
}

class pfDaemonServer extends pfDaemon
{
    private $timeout;

    function __construct($name)
    {
        global $argv;

        // shut down (by default) after being idle for 30 minutes
        $this->timeout=30*60;

        if (empty($argv[2]) || $argv[2]!="debug")
        {
            //siDiscern()->Event("daemonizing");
            if (!function_exists("posix_setsid"))
                Fatal("posix_setsid not found - install php-process please");

            $pid=pcntl_fork();
            if ($pid<0) Fatal("pcntl_fork failed $pid");
            if ($pid)
            {
                // i am the parent
                //siDiscern()->Event("parent-exit");
                exit(0);
            }

            // disconnect from session
            posix_setsid();

            // must close std paths to complete disconnect
            fclose(STDIN);
            fclose(STDOUT);
            fclose(STDERR);

            // open up something to prevent errors being written
            // to a socket and fouling the communications
            $STDIN=fopen("/dev/null","r");
            $STDOUT=fopen("/dev/null","wb");
            $STDERR=fopen("/dev/null","wb");
        }
        siDiscern()->Event("daemon")->Flush();
        
        // listen for connections
        pfDaemon::__construct($name);
        if (empty($this->port))
            Fatal("pfDaemonServer: port not set");

        $this->sock=socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
        if (!$this->sock)
            Fatal("pfDaemonServer: socket_create: ".$this->_SockErr());

        socket_bind($this->sock,"localhost",$this->port)
            or Fatal("pfDaemonServer: socket_bind: ".$this->_SockErr());

        socket_listen($this->sock)
            or Fatal("pfDaemonServer: socket_list: ".$this->_SockErr());

        socket_set_nonblock($this->sock)
            or Fatal("pfDaemonServer: socket_set_nonblock: ".$this->_SockErr());

        $connections=array();

        $lastactive=time();

        while (!$this->timeout || time()-$lastactive<$this->timeout)
        {
            $cc=count($connections);
            $age=time()-$lastactive;
            //siDiscern()->Event("active",array('connections'=>$cc,'age'=>$age))->Flush();
            if (count($connections))
                $lastactive=time();

            $r=array($this->sock);
            foreach ($connections as $connection)
                $r[]=$connection->sock;
            $w=NULL;
            $e=NULL;

            $select=socket_select($r,$w,$e,1);
            if ($select===false)
            {
                Warning("pfDaemonServer: socket_select: ".$this->_SockErr());
                sleep(1);
                continue;
            }

            // allow daemon to perform background processes via poll every 1sec
            if (method_exists($this,"_Process"))
                $this->_Process();

            if (!$select) continue; // don't bother checking, stay idle

            // process active connections, delete inactive
            $remove=array();
            foreach ($connections as $index => $connection)
            {
                $connection->_Process()
                    or $remove[]=$index;
            }
            foreach ($remove as $index)
                unset($connections[$index]);

            // repeat select just for accept socket (otherwise it throws an error)
            $r=array($this->sock);
            $w=NULL;
            $e=NULL;
            $select=socket_select($r,$w,$e,0);
            if ($select===false || !$select)
                continue;

            $new_sock=socket_accept($this->sock);
            if ($new_sock===false)
            {
                if (socket_last_error($this->sock)===0)
                    continue;
                Warning("pfDaemonServer: socket_accept: ".$this->_SockErr());
                sleep(1);
                continue;
            }
            $addr='unknown';
            $port='invalid';
            socket_getsockname($new_sock,$addr,$port);
            $name="$addr:$port";

            $addr='unknown';
            $port='invalid';
            socket_getpeername($new_sock,$addr,$port);
            $peer="$addr:$port";

            //siDiscern()->Event("accept",array('name'=>$name,'peer'=>$peer))->Flush();

            $accept=new pfDaemonConnection($new_sock,$this);
            $accept->name=$name;
            $accept->peer=$peer;

            $connections[]=$accept;
        }
    }
    public function SetTimeout($seconds)
    {
        $this->timeout=$seconds;
    }
    public function __call($name,$args)
    {
        Fatal("pfDaemonServer does not have method $name");
    }
}
