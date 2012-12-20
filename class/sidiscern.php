<?php

// siDiscern() provides various instrumentation:
// 1) processing time
// 2) user actions or other events
// 3) version testing (compare two or more versions)

// requirements on poof.php for accuracy:
// 1) init_time is set from start of script
// 2) discern is initialized just prior to end of poof.php
// this allows discern to log the time for poof.php to run

class siDiscern extends pfSingleton
{
    private $events;
    public $error;

    public function __construct($name=false,$data=false,$time=false)
    {
        $this->error=false;
        $this->events=array();

        // make sure we note completion along with any exceptions
        register_shutdown_function(array($this,"Shutdown"));

        if ($name)
            return self::__invoke($name,$data,$time);
    }
    public function Init($time)
    {
        global $argv;

        $server=array(
            'HTTP_HOST',
            'HTTP_USER_AGENT',
            'HTTP_REFERER',
            'REMOTE_ADDR',
            'REMOTE_PORT',
            'SCRIPT_FILENAME',
            'REQUEST_METHOD',
            'REQUEST_URI',
            'REQUEST_TIME',
            'REQUEST_TIME_FLOAT',
        );

        // build data to report on init event
        $data=array();
        foreach ($server as $var)
            if (!empty($_SERVER[$var]))
                $data[$var]=$_SERVER[$var];
        $data['SAPI']=php_sapi_name();
        $data['ARGV']=(empty($argv)?'':$argv);
        $data['SESSION']=session_id();
        $data['PHPVERSION']=phpversion();

        // log the start time noted in poof.h
        self::__invoke("init",$data,$time);
    }
    public function Event($name=false,$data=false,$time=false)
    {
        return self::__invoke($name,$data,$time);
    }
    public function __invoke($name,$data=false,$time=false)
    {
        if (is_array($data))
            $data=json_encode($data);

        $event=array(
            'pid'=>getmypid(),
            'time'=>($time?$time:microtime(true)),
            'memi'=>memory_get_usage(false),
            'meme'=>memory_get_usage(true),
            'name'=>"$name",
            'data'=>"$data"
        );

        $this->events[]=$event;
        return($this);
    }

    public function Flush()
    {
        global $POOF_DIR;

        $path="$POOF_DIR/discern";
        if (!is_dir($path))
            mkdir($path,0777,true);

        $pid=getmypid();
        $script=basename($_SERVER['SCRIPT_FILENAME']);
        //$date=date('Hi');
        $date="xxxx";

        $discfile="$path/$date.csv";
        // log the events for later processing
        $fp=fopen($discfile,"a");
        if (!$fp)
            $fp=STDOUT; //Fatal("Unable to write $discfile");
        foreach ($this->events as $event)
            fputcsv($fp,$event);
        fclose($fp);
        $this->events=array();
    }

    public function Shutdown()
    {
        $error=error_get_last();

        // check for fatal error condition and log it
        if (!empty($error))
        {
            // in cli mode, the error has already been printed,
            // but best to call the handler for everything

            $this->Event("error",$error);
            $this->Flush();
            /*
            poof_error_handler(
                $error['type'],
                $error['message'],
                $error['file'],
                $error['line']
            ); 
            */
            siError(new ErrorException($error['message'],
                $error['type'],
                0,
                $error['file'],
                $error['line']));

            // handler will discern the error
            //$this->Event("error",$error);
        }

        if (connection_aborted())
        {
            // user CANCELLED web page before completed
            $this->Event("abort");
            $this->Flush();
            return;
        }
        // normal completion

        // mark the completion time
        $this->Event("shutdown");

        // flush the output to make sure user sees page immediately
        // (rather than after a small delay for discern flush to complete)
        if (php_sapi_name()!="cli")
            ob_end_flush();
        flush();

        $this->Flush();
    }
}
