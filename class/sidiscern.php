<?php

// siDiscern() provides various instrumentation:
// 1) processing time
// 2) user actions or other events
// 3) version testing (compare two or more versions)

class siDiscern extends pfSingleton
{
    private $events;

    public function __construct()
    {
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
        $data['SESSION']=session_id();

        $this->events=array();
        $this->Event("init",$data);
        register_shutdown_function(array($this,"Shutdown"));
    }

    public function Event($name,$data=false)
    {
        if (is_array($data))
            $data=json_encode($data);
        $event=array(
            'pid'=>getmypid(),
            'time'=>microtime(true),
            'name'=>"$name",
            'data'=>"$data"
        );

        $this->events[]=$event;
    }

    public function Shutdown()
    {
        $discfile="/tmp/discern.csv";

        if (connection_aborted())
        {
            // user CANCELLED web page before completed
            $this->Event("abort");
        }
        else
        {
            // normal completion
            $this->Event("shutdown");

            // flush the output to make sure user sees page immediately
            if (php_sapi_name()!="cli")
                ob_end_flush();
            flush();
        }

        // log the events for later processing
        $fp=fopen($discfile,"a");
        if ($fp)
        {
            foreach ($this->events as $event)
                fputcsv($fp,$event);
            fclose($fp);
        }
    }
}
