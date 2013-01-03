<?php

class siError extends pfSingleton
{
    private $ignore_functions;
    private $force_text;

    public function __construct($error=false)
    {
        $this->ignore_functions=array();
        $this->force_text=false;

        // send all php errors and exceptions to this class
        set_error_handler(array($this,'php_error_handler'));
        set_exception_handler(array($this,'__invoke'));

        // enable all errors to be reported
        error_reporting(-1);

        // turn off display errors due to https://bugs.php.net/bug.php?id=47494
        ini_set('display_errors','0');

        if ($error!==false)
            self::__invoke($error);
    }
    public function log($error)
    {
        global $POOF_DIR;
        global $POOF_URL;
        global $POOF_HOST;

        // cannot use /error as apache may have redirected that
        $dir="errlog";
        $path="$POOF_DIR/$dir";
        if (!is_dir($path))
            mkdir($path,0777,true);

        $msg=$error->getMessage().
            " in ".$error->getFile().
            " line ".$error->getLine();

        $file=md5($msg).".txt";

        $pathfile="$path/$file";
        if (file_exists($pathfile))
            return;

        $discern_url=siDiscern()->GetUrl();
        file_put_contents($pathfile,(string)$error.
            "\n\n$discern_url\n\n".print_r($error,true));

        $url="http://$POOF_HOST$POOF_URL/$dir/$file";

        $msg.="\n".$url;

        $ch=curl_init("http://poof.stg.net/error_notify.php?msg=".urlencode($msg));
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch,CURLOPT_POSTFIELDS,"");
        curl_setopt($ch,CURLOPT_POST,1);
        curl_exec($ch);
        curl_close($ch);
    }
    public function __invoke($error=false)
    {
        if (!$error)
            return($this);

        $additional=false;
        try
        {
            $this->log($error);
        }
        catch (Exception $e)
        {
            $additional="\nAdditional error: ".(string)$e;
        }

        $header="H2";
        $color="red";

        siDiscern('error',$error);
        $discern_url=siDiscern()->GetUrl();

        $text=$this->force_text;
        if (php_sapi_name()=='cli')
            $text=true;

        if ($text)
            echo "\n\n------------------------------------------------------------\n";
        else
            echo "\n\n<br/><hr/><$header><font color=\"$color\">";

        $message=(string)$error.$additional;
        if (!$text)
            $message=str_replace("\n","<br />",htmlentities($message));

        echo "ERROR: $message";
        if ($text)
            echo "\n$discern_url\n";
        else
            echo "\n<br /><a href=\"$discern_url\">$discern_url</a></font></$header>\n<pre>";
        //print_r($error);
        if ($text)
            echo "------------------------------------------------------------\n";
        else
            echo "</pre><hr /><br />\n";

        return($this);
    }
    public function SetText()
    {
        $this->force_text=true;
    }
    public function IgnoreFunction($name)
    {
        if (!in_array($name,$this->ignore_functions))
            $this->ignore_functions[]=$name;
            sidiscern('ignore-now',$this->ignore_functions);
    }
    public function php_error_handler($type,$message,$file,$line)
    {
        //self::__invoke(new ErrorException($message,$type,0,$file,$line));
        global $POOF_DIR;
        global $POOF_SITE;
        global $POOF_TIMEZONE;

        $exp=explode('(',$message);
        $function_name=$exp[0];


        if (in_array($function_name,$this->ignore_functions))
        {
            //siDiscern('php_error_ignore',$function_name);
            return true;
        }
/*        elseif ($function_name=="fopen")
        {
           siDiscern('error',"$message in $file line $line");
           return true;
        }
        */
        elseif ($function_name=="date" || $function_name=="date_default_timezone_get")
        {
            // fix issue with lack of timezone
            if (strstr($message,"date_default_timezone_set"))
            {
                /*
                $tzfile="$POOF_DIR/timezone"; // edit this file to change it
                if (file_exists($tzfile))
                {
                    $tz=file_get_contents($tzfile);
                }
                else
                {
                    $tz=date_default_timezone_get();
                    file_put_contents($tzfile,$tz);
                }
                date_default_timezone_set($tz);
                */
                if (!$POOF_TIMEZONE)
                {
                    $POOF_TIMEZONE=date_default_timezone_get();
                    $POOF_SITE->Set('timezone',$POOF_TIMEZONE);
                }
                else
                    date_default_timezone_set($POOF_TIMEZONE);
                return true;
            }
        }
//print("ERROR: $message in $file $line\n");
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
    if ($message instanceof Exception)
    {
        siDiscern('error',$message->getMessage());
        throw $message;
    }
    siDiscern('error',$message);
    throw new Exception($message);
}
function Warning($message)
{
    if ($message instanceof Exception)
    {
        siDiscern('error-warning',$message->getMessage());
        siError($message);
        return false;
    }

    siDiscern('error-warning',$message);
    siError(new WarningException($message));
    return false;
}

