<?php
/**
 * ==========================================================
 * poof
 * http://poof.stg.net
 * ==========================================================
 * Copyright 2012 StG Net
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ==========================================================
 * 
 * @package poof
 * @author Scott Griepentrog
 * @copyright Apache 2.0
 */

// necessary for PHPUNIT
global $POOF_INIT;
global $POOF_SITE;

$POOF_INIT=microtime(true);

// register our autoloader
poof_init_locate();
spl_autoload_register('poof_autoload');

// load functions mapped to class constructors
require_once(dirname(__FILE__)."/class_constructors.php");

// fix missing hostname
if (empty($_SERVER['HOSTNAME']))
    $_SERVER['HOSTNAME']=trim(`hostname`);

// load error handling
siError();

$POOF_SITE=dbPoofSite();

$tz=$POOF_SITE->Get('timezone');
if ($tz)
    date_default_timezone_set($tz);

// load the instrumentation library
siDiscern()->init($POOF_INIT);


poof_init_url();

// security considerations
if (function_exists("libxml_disable_entity_loader"))
    libxml_disable_entity_loader(true);

// new compatible password hashing
if (!function_exists("password_hash"))
    require_once(dirname(__FILE__)."/misc/password.php");

// always start session handling 
//if (php_sapi_name()!="cli")
if (empty($argv[1]) || $argv[1]!="-daemon")
{
    session_set_cookie_params(0); // persistant user tracking for discern
    session_start();

    if (empty($_SESSION['POOFSITE']) ||
        empty($_SESSION['POOFSITE']['loaded']) ||
        (time()-$_SESSION['POOFSITE']['loaded'])>300)
    {
        $_SESSION['POOFSITE']=dbPoofSite()->lookup();
        $_SESSION['POOFSITE']['loaded']=time();
    }
}

// fix missing hex2bin
if (!function_exists("hex2bin"))
{
    function hex2bin($h)
    {
        if (!is_string($h)) return null;
        $r = '';
        for ($a = 0; $a < strlen($h); $a += 2)
        {
            $r .= chr(hexdec($h{$a}.$h{($a + 1)}));
        }
        return $r;
    }
}

function safe(&$var)
{
    if (isset($var))
        return $var;

    return false;
}
function safearray(&$var)
{
    if (empty($var))
        return(array());
    return($var);
}

// allow selection of a theme (multiple possible)
function poof_theme($name)
{
    global $POOF_DIR;
    global $POOF_THEMES;
    if (empty($POOF_DIR)) poof_locate();
    if (!is_dir("$POOF_DIR/theme/$name"))
        Fatal("poof_theme(): unable to locate theme '$name'");
    $POOF_THEMES[]=$name;
}

function poof_init_locate()
{
    global $POOF_FILE;  // path to this file
    global $POOF_DIR;   // base directory for poof library
    global $POOF_ROOT;  // directory path to poof library
    global $POOF_CWD;   // the current directory
    global $POOF_PRJ;   // the 'project' directory poof was invoked from
    global $POOF_THEMES;    // array of directories to check first

        // locate the poof library itself, set globals
        $POOF_FILE=realpath(__FILE__);
        $POOF_CWD=getcwd();
        $POOF_DIR=dirname($POOF_FILE);
        if (!file_exists("$POOF_DIR/poof.php"))
            Fatal("unable to locate file path to poof library");

        $POOF_ROOT=str_replace($_SERVER['SCRIPT_NAME'],"",$_SERVER['SCRIPT_FILENAME']);
        $rootfile="$POOF_DIR/root";
        if ($POOF_ROOT)
        {
            if (!file_exists($rootfile))
                @file_put_contents($rootfile,$POOF_ROOT);
        }
        else
        {
            if (file_exists($rootfile))
                $POOF_ROOT=trim(file_get_contents($rootfile));
            else
                $POOF_ROOT="/invalid";
        }

        // figure out the path to the directory of script that 
        // included poof.php - as we want to locate() files there also
        if (empty($_SERVER['PWD']) || $_SERVER['SCRIPT_FILENAME'][0]=="/")
            $POOF_PRJ=realpath(dirname($_SERVER['SCRIPT_FILENAME']));
        else
            $POOF_PRJ=realpath(dirname($_SERVER['PWD']."/".$_SERVER['SCRIPT_FILENAME']));
        $POOF_THEMES=array();
}
function poof_init_url()
{
    global $POOF_URL;   // URL path to poof library
    global $POOF_HOST;  // hostname used (possibly virtual)
    global $POOF_SITE;  // site configuration database
    global $POOF_ROOT;
    global $POOF_DIR;

    if (!safe($POOF_SITE))
        $POOF_SITE=dbPoofSite();
    if (!safe($POOF_SITE))
        Fatal("Unable to initialize dbPoofSite");

        $POOF_URL=str_replace($POOF_ROOT,"",$POOF_DIR);

        $POOF_HOST=safe($_SERVER['HTTP_HOST']);
        $site_host=$POOF_SITE->Get('host');

        if (!$POOF_HOST)
        {
            if ($site_host)
                $POOF_HOST=$site_host;
            else
                $POOF_HOST='unknown-host';
        }
        else
        {
            if ($POOF_HOST!=$site_host)
                $POOF_SITE->Set('host',$POOF_HOST);
        }

}
// locate a file from the library
function poof_locate($path)
{
    global $POOF_FILE;  // path to this file
    global $POOF_DIR;   // base directory for poof library
    global $POOF_ROOT;  // directory path to poof library
    global $POOF_CWD;   // the current directory
    global $POOF_PRJ;   // the 'project' directory poof was invoked from
    global $POOF_THEMES;    // array of directories to check first

    if ($path)
    {
        $orig=$path;
        $path=strtolower($path);
        if ($path[0]=='/')
        {
            if (file_exists($path))
                return(realpath($path));
        }

        // default must be last theme checked
        foreach (array_merge($POOF_THEMES,array('default')) as $theme)
        {
            $test="$POOF_DIR/theme/$theme/$path";
            if (file_exists($test))
            {
                //siDiscern()->Event("locate",array('request'=>$orig,'path'=>$test));
                return($test);
            }
        }

        // first option: find the file in the POOF framework
        $test="$POOF_DIR/$path";
        if (file_exists($test))
        {
            //if (class_exists("siDiscern"))
            //    siDiscern()->Event("locate",array('request'=>$orig,'path'=>$test));
            return(realpath($test));
        }

        // second option: find the file in the customer's project
        $test="$POOF_PRJ/$path";
        if (file_exists($test))
            return(realpath($test));

        // third option: find the file in the customer project
        // but without the usual class/ or css/ etc prefix dir
        $test="$POOF_PRJ/".basename($path);
        if (file_exists($test))
            return(realpath($test));

        //Warning("poof_locate(): did not find '$test'");
        if (class_exists("siDiscern"))
        siDiscern()->Event("locate-failed",array('request'=>$orig));
    }
    return(false);
}

function poof_url($path)
{
    global $POOF_URL,$POOF_ROOT;
    $filepath=poof_locate($path);
    $relpath=str_replace($POOF_ROOT,"",$filepath);
    return($relpath);
}
function poof_fullurl($path)
{
    global $POOF_HOST;
    return("http://$POOF_HOST".poof_url($path));
}

// automatically load class files from the library when instantiated
function poof_autoload($class)
{
    global $POOF_DIR;

    $path=poof_locate("class/{$class}.php");

    if (is_file($path))
    {
        require_once($path);
        return(true);
    }

    // 
    $test="$POOF_DIR/vendors/".str_replace(array('\\', '_'), '/',$class).'.php';
    if (is_file($test))
    {
        require_once($test);
        return(true);
    }

    //Fatal("unable to locate file '$path'\n");
    return(false);
}

siDiscern('main');

