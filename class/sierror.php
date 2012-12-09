<?php

class siError extends pfSingleton
{
    private $ignore_functions;

    public function __construct($error=false)
    {
        $this->ignore_functions=array();

        // send all php errors to this class
        set_error_handler(array($this,'php_error_handler'));
        set_exception_handler(array($this,'__invoke'));
        error_reporting(-1);

        if ($error!==false)
            self::__invoke($error);
    }
    public function __invoke($error=false)
    {
        if (!$error)
            return($this);

        $header="H2";
        $color="red";

        siDiscern('error',$error);

        if (php_sapi_name()=='cli')
            echo "\n\n------------------------------------------------------------\n";
        else
            echo "\n\n<br/><hr/><$header><font color=\"$color\">";

        $message=(string)$error;
        if (php_sapi_name()!='cli')
            $message=str_replace("\n","<br />",htmlentities($message));

        echo "ERROR: $message";
        if (php_sapi_name()=='cli')
            echo "\n";
        else
            echo "\n</font></$header>\n<pre>";
        //print_r($error);
        if (php_sapi_name()=='cli')
            echo "\n\n------------------------------------------------------------\n";
        else
            echo "</pre><hr /><br />\n";

        return($this);
    }
    public function IgnoreFunction($name)
    {
        if (!in_array($name,$this->ignore_functions))
            $this->ignore_functions[]=$name;
    }
    public function php_error_handler($type,$message,$file,$line)
    {
        //self::__invoke(new ErrorException($message,$type,0,$file,$line));

        $exp=explode('(',$message,1);
        $function_name=$exp[0];
        siDiscern('php_error_function',$function_name);
        if (in_array($function_name,$this->ignore_functions))
        {
            siDiscern('php_error_ignore',$function_name);
            return true;
        }

        throw new ErrorException($message,$type,0,$file,$line);
    }

}

class WarningException extends Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}

// for convenience, global function Fatal() and Warning() are defined
function Fatal($message)
{
    siDiscern('fatal',$message);
    throw new Exception($message);
}
function Warning($message)
{
    siDiscern('warning',$message);
    siError(new WarningException($message));
}

