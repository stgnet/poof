<?php

// Daemon provides background server with IPC via TCP localhost

class pfDaemonConnection extends pfDaemon
{
    private $server;

    function __construct($sock,$server)
    {
        $this->sock=$sock;
        $this->server=$server;
    }
    function _Process()
    {
        $test=socket_recv($this->sock,$data,1,MSG_PEEK);
        if ($test===0) return(false); // disconnected

        $code=false;
        $data=$this->_Read($code);
        if ($data===false) return(true);
        if ($code==PFD_REQ_NAME)
        {
            $this->_Write(PFD_ANS_NAME,$this->server->name);
            return(true);
        }
        if ($code!=PFD_REQ_FUNC)
            return(false);

        $request=json_decode($data,true);
        if (empty($request['name']))
        {
            Warning("received request with no name");
            return(false);
        }

        if (method_exists($this->server,$request['name']))
        {

            $return=call_user_func_array(array($this->server,$request['name']),$request['args']);
            $response=array('return'=>$return);
        }
        else
            $response=array('error'=>"method not found");
        $this->_Write(PFD_ANS_FUNC,json_encode($response));
        return(true);
    }
}

class pfDaemonServer extends pfDaemon
{
    function __construct($name)
    {
        global $argv;

        if (empty($argv[2]) || $argv[2]!="debug")
        {
            if (!function_exists("posix_setsid"))
                Fatal("posix_setsid not found - install php-process please");
            $pid=pcntl_fork();
            if ($pid<0) Fatal("pcntl_fork failed $pid");
            if ($pid)
                // i am the parent
                exit(0);

            // disconnect from session
            posix_setsid();
            fclose(STDIN);
            fclose(STDOUT);
            fclose(STDERR);
        }
        
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

        $timeout=300;

        while (time()-$lastactive<$timeout)
        {
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
            if (!$select) continue; // don't bother checking, stay idle

            $remove=array();
            foreach ($connections as $index => $connection)
            {
                $connection->_Process()
                    or $remove[]=$index;
            }
            foreach ($remove as $index)
                unset($connections[$index]);

            // repeat select just for accept socket
            $r=array($this->sock);
            $w=NULL;
            $e=NULL;
            $select=socket_select($r,$w,$e,0);
            if ($select===false || !$select)
                continue;

            $new_sock=socket_accept($this->sock);
            if ($new_sock===false)
            {
                Warning("pfDaemonServer: socket_accept: ".$this->_SockErr());
                sleep(1);
                continue;
            }

            $connections[]=new pfDaemonConnection($new_sock,$this);
        }
    }
    public function __call($name,$args)
    {
        Fatal("pfDaemonServer does not have method $name");
    }
}
