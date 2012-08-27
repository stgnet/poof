<?php

	// send details to email rather than output
	function poof_error_email($email)
	{
		global $poof_error_config;

		$poof_error_config['email']=$email;
	}

	// manually triggered fatal - use in place of die()
	function Fatal($msg)
	{
		poof_error_send("FATAL: ".$msg);
		exit(1);
	}

	// manually triggered warning
	function Warning($msg)
	{
		poof_error_send("WARNING: ".$msg);
	}

	// php's internal errors are sent here
	function poof_error_handler($errno, $errstr, $errfile, $errline)
	{
		// ignore warnings for certain functions
		$exp=explode('(',$errstr);
		if (in_array($exp[0],array("socket_connect","socket_shutdown","date","strtotime","strftime"))) return(true);

		if (!empty($GLOBALS['sugar_config']))
		{
			// SugarCRM has some issues we don't need to barf on
			if (substr_count($errstr,'should not be called statically')) return(true);
			if (substr_count($errstr,'should be compatible with that of SugarBean')) return(true);
			if (substr_count($errstr,'Undefined index: defaultFirst')) return(true);
		}

		$errnostr="?{$errno}?";
		$fatal=true;
		switch ($errno)
		{
		case E_ERROR:
			$errnostr="FATAL ERROR";
			$fatal=true;
			break;

		case E_WARNING:
			$errnostr="WARNING";
			$fatal=false;
			break;

		case E_PARSE:
			$errnostr="PARSE";
			$fatal=true;
			break;

		case E_NOTICE:
			$errnostr="NOTICE";
			$fatal=false;
			break;

		case E_USER_NOTICE:
			$errnostr="USER_NOTICE";
			$fatal=false;
			break;

		case E_USER_WARNING:
			$errnostr="USER_WARNING";
			$fatal=false;
			break;

		case E_USER_ERROR:
			$errnostr="USER_ERROR";
			$fatal=true;
			break;

		case E_STRICT:
			$errnostr="STRICT";
			$fatal=false;
			break;

		case E_RECOVERABLE_ERROR:
			$errnostr="RECOVERABLE";
			$fatal=true;
			break;

		case E_DEPRECATED:
		case E_USER_DEPRECATED:
			$errnostr="DEPRECATED";
			$fatal=false;
			break;
		}

		$msg=basename($_SERVER['PHP_SELF']).": $errstr [$errnostr] in $errfile line $errline";

		poof_error_send($msg);
		if ($fatal) exit($errno);

		return(true); // don't fall through to PHP handler, it's already been 'handled'
	}


	function poof_error_send_log($msg)
	{
		$script=basename($_SERVER['PHP_SELF']);
		syslog(LOG_ERR,"$script: $msg");
	}
	function poof_error_trace()
	{
		$eliminate=array("\t","\n","\r");

		$output='';

		//foreach (array_reverse(debug_backtrace()) as $stack)
		foreach (debug_backtrace() as $stack)
		{
/*
			if (!empty($stack['file']) && basename($stack['file'])=="err.class.php")
				continue;
*/

			//if (substr($stack['function'],0,10)=="poof_error") continue;
			if ($stack['function']=="poof_error_trace") continue;
			if ($stack['function']=="poof_error_send_html") continue;
			if ($stack['function']=="poof_error_send") continue;

			$output.="===> FUNCTION {$stack['function']} (";

				$sep='';
				foreach ($stack['args'] as $arg)
				{
					$output.=$sep.str_replace($eliminate," ",print_r($arg,true));
					$sep=', ';
				}
				$output.=")\n";

				/*
				if ($stack['class'])
					$output.="  OBJECT ".$stack['class'];
	
				if ($stack['type'])
					$output.=" ".$stack['type'];
	
				$output.="\n";
				*/

				if (!empty($stack['object']))
				{
					$output.=print_r($stack['object'],true);
					/*
					foreach ($stack['object'] as $key => $value)
					{
						$output.="    '".$key."' => ";
						$output.=print_r($value,true);
						$output.="\n";
					}
					*/
				}

			if (!empty($stack['file']) && $stack['file'])
				$output.="IN FILE '{$stack['file']}' LINE {$stack['line']}:\n";
			if (!empty($stack['file']) && is_file($stack['file']))
			{
				$line=file($stack['file']);
				$range=2;
				$i=$stack['line']-$range;

				while ($i<=$stack['line']+$range)
				{
					$ln=sprintf("%05d: ",$i);
					if ($i==$stack['line'])
						$ln=sprintf("%05d> ",$i);
					if ($i>0 && !empty($line[$i-1]))
						$output.=$ln.trim($line[$i-1])."\n";
					++$i;
				}
			}
			$output.="\n";
		}
		return($output);
	} 
	function poof_error_send_email($msg)
	{
		$host="unknown";
		if (array_key_exists('SERVER_NAME',$GLOBALS))
			$host=$GLOBALS['SERVER_NAME'];
		if (array_key_exists('HOSTNAME',$GLOBALS))
			$host=$GLOBALS['HOSTNAME'];

		$script="unknown";
		if (!empty($_SERVER['SCRIPT_FILENAME']))
			$script=$_SERVER['SCRIPT_FILENAME'];
		else
		if (!empty($_SERVER['PHP_SELF']))
			$script=$_SERVER['PHP_SELF'];
		else
		if (!empty($GLOBALS['argv'][0]))
			$script=$GLOBALS['argv'][0];

		$script_base=basename($script);

		$subject="$host: $script: ERROR: $msg";

		$body="ERROR: $msg\n\n";
		$body.="STACK TRACE:\n";
		$body.=poof_error_trace()."\n";
		$body.="GLOBALS:\n";
		$body.=print_r($GLOBALS,true);

		mail($email,$subject,$body,"From: $script_base@$host\r\n");
	}
	function poof_error_send_html($msg)
	{
		print("\n\n<br/><hr/><p><font size=\"+2\" color=\"red\">\n");
		print("ERROR: $msg\n");
		print("</font></p>");
		print("<pre>".htmlentities(poof_error_trace())."</pre>");
		print("<hr/><br/>\n");
	}

	function poof_error_send($msg)
	{
		poof_error_send_html($msg);
	}
