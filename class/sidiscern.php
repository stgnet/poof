<?php

// siDiscern() provides various instrumentation:
// 1) processing time
// 2) user actions or other events
// 3) version testing (compare two or more versions)

class siDiscern extends pfSingleton
{
    private $events;

    public function __construct($init_time=false)
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

        $this->events=array();
        $this->Event("init",$data,$init_time);
        $this->Event("main");
        register_shutdown_function(array($this,"Shutdown"));
    }

    public function Event($name,$data=false,$time=false)
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
        $discfile="/tmp/discern.csv";
        // log the events for later processing
        $fp=fopen($discfile,"a");
        if ($fp)
        {
            foreach ($this->events as $event)
                fputcsv($fp,$event);
            fclose($fp);
        }
        $this->events=array();
    }

    public function Shutdown()
    {
        $error=error_get_last();

        if (connection_aborted())
        {
            // user CANCELLED web page before completed
            $this->Event("abort",$error);
        }
        else
        {
            // normal completion
            $this->Event("shutdown",$error);

            // flush the output to make sure user sees page immediately
            if (php_sapi_name()!="cli")
                ob_end_flush();
            flush();
        }
        $this->Flush();
    }
}
