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
    public $count;

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
            $r=array($this->sock);
            $w=NULL;
            $e=NULL;
            if (pfDaemonDebug) siDiscern("debug","connection  select")->Flush();
            $select=socket_select($r,$w,$e,0);
            if (pfDaemonDebug) siDiscern("debug","connection select returned $select")->Flush();
            if ($select===false || !$select)
                return(false);

        try
        {
            $test=socket_recv($this->sock,$data,1,MSG_PEEK);
            if ($test===0)
            {
                siDiscern('daemon-connection-close',array(
                    'name'=>$this->name,
                    'peer'=>$this->peer,
                    'requests'=>$this->count,
                ))->Flush();
                return(false); // disconnected
            }
        }
        catch (Exception $e)
        {
            return(false);
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
            try
            {
                $return=call_user_func_array(array($this->server,$request['name']),$request['args']);
                if (is_a($return,"pfDaemonError"))
                    $response=array('error'=>$return->error);
                else
                    $response=array('return'=>$return);
            }
            catch (Exception $e)
            {
                $response=array('error'=>(string)$e);
            }
        }
        else
            $response=array('error'=>"method '{$request['name']}' not found");

        $this->_Write(json_encode($response));

        /*
        siDiscern('request',array(
            'request'=>$request,
            'response'=>$response
        ))->Flush();
        */

        $this->count++;

        return(true);
    }
}

class pfDaemonServer extends pfDaemon
{
    private $timeout;
    private $lastactive;

    function __construct($name)
    {
        global $argv;

        siError()->SetText();

        // shut down (by default) after being idle for 30 minutes
        $this->timeout=1*60;
        $this->lastactive=time();

        // listen for connections
        pfDaemon::__construct($name);
        if (empty($this->port))
            Fatal("pfDaemonServer: port not set");

        $this->sock=socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
        if (!$this->sock)
            Fatal("pfDaemonServer: socket_create: ".$this->_SockErr());

        try
        {
            if (socket_bind($this->sock,"127.0.0.1",$this->port)===false)
                $this->ThrowSocketError("socket_connect");
            if (pfDaemonDebug) siDiscern('debug',"bound on port {$this->port}")->Flush();
        }
        catch (Exception $e)
        {
            //try
            //{
                if (socket_bind($this->sock,"127.0.0.1",$this->altp)===false)
                    $this->ThrowSocketError("socket_connect");
                if (pfDaemonDebug) siDiscern('debug',"bound on port {$this->altp}")->Flush();
            //}
            //catch (Exception $e)
            //{
            //    Fatal("Unable to bind primary {$this->port} and alt {$this->altp} ports");
            //}
        }


        socket_listen($this->sock)
            or Fatal("pfDaemonServer: socket_list: ".$this->_SockErr());

        socket_set_nonblock($this->sock)
            or Fatal("pfDaemonServer: socket_set_nonblock: ".$this->_SockErr());

        if (!empty($argv[2]) && $argv[2]=="debug")
        {
            // debug mode: don't fork, don't timeout
            $this->timeout=0;
        }
        else
        {
            if (get_current_user()=="root" ||
                getmyuid()===0)
                Fatal("daemon $name should not be run as ".get_current_user());

            if (!function_exists("posix_setsid"))
                Fatal("posix_setsid not found - install php-process please");

            siDiscern("daemonizing")->Flush();
            $pid=pcntl_fork();
            if ($pid<0) Fatal("pcntl_fork failed $pid");
            if ($pid)
            {
                // i am the parent
                if (pfDaemonDebug) siDiscern("parent-exit")->Flush();
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
        siDiscern("daemon")->Flush();


        $connections=array();

        $this->lastactive=time();

        while (!$this->timeout || time()-$this->lastactive<$this->timeout)
        {
            $cc=count($connections);
            $age=time()-$this->lastactive;

            if ($cc)
            {
                siDiscern("active",array(
                    'name'=>$this->name,
                    'connections'=>$cc,
                    'age'=>$age
                ))->Flush();

                $this->lastactive=time();
            }

            $r=array($this->sock);
            foreach ($connections as $connection)
                $r[]=$connection->sock;
            $w=NULL;
            $e=NULL;

            if (pfDaemonDebug) siDiscern("debug","enter select of ".count($r))->Flush();
            $select=socket_select($r,$w,$e,1);
            if ($select===false)
            {
                Warning("pfDaemonServer: socket_select: ".$this->_SockErr());
                sleep(1);
                continue;
            }
            if (pfDaemonDebug) siDiscern("debug","select returned $select")->Flush();

            // allow daemon to perform background processes via poll every 1sec
            if (method_exists($this,"_Process"))
            {
                try
                {
                    if (pfDaemonDebug) siDiscern("debug","enter process")->Flush();
                    $this->_Process();
                    if (pfDaemonDebug) siDiscern("debug","exit process")->Flush();
                }
                catch (Exception $e)
                {
                    Warning($e);
                }
            }

            //if (!$select) continue; // don't bother checking, stay idle

            // process active connections, delete inactive
            $remove=array();
            foreach ($connections as $index => $connection)
            {
                $name=$connection->name;
                if (pfDaemonDebug) siDiscern("debug","Enter connection process $name")->Flush();
                $connection->_Process()
                    or $remove[]=$index;
                if (pfDaemonDebug) siDiscern("debug","Exit connection process")->Flush();
            }
            foreach ($remove as $index)
                unset($connections[$index]);

            // repeat select just for accept socket (otherwise it throws an error)
            $r=array($this->sock);
            $w=NULL;
            $e=NULL;
            if (pfDaemonDebug) siDiscern("debug","accept select")->Flush();
            $select=socket_select($r,$w,$e,0);
            if (pfDaemonDebug) siDiscern("debug","select returned $select")->Flush();
            if ($select===false || !$select)
                continue;

            if (pfDaemonDebug) siDiscern("debug","entering accept")->Flush();
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

            siDiscern("accept",array('name'=>$name,'peer'=>$peer))->Flush();

            $accept=new pfDaemonConnection($new_sock,$this);
            $accept->name=$name;
            $accept->peer=$peer;

            $connections[]=$accept;
        }
    }
    public function ResetActivity()
    {
        $this->lastactive=time();
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
