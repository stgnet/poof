<?php

// siDiscern() provides various instrumentation:
// 1) processing time
// 2) user actions or other events
// 3) version testing (compare two or more versions)

class siDiscern extends pfSingleton
{
    public function __construct()
    {
        register_shutdown_function(array($this,"Shutdown"));
    }

    public function Shutdown()
    {
        passthru("pwd ; ls");
        file_put_contents("/tmp/discern.csv","test\n");
        ob_end_flush();
        flush();
        sleep(10);
    }

    public function __toString()
    {
    }
}
