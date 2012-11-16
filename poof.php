<?php
/* ==========================================================
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
 * ========================================================== */

// for siDiscern(), make note of actual start time
$poof_init_time=microtime(true);

// register our autoloader
spl_autoload_register('poof_autoload');

// load functions mapped to class constructors
require_once(dirname(__FILE__)."/class_constructors.php");

// load error handling
require_once(dirname(__FILE__)."/error_handler.php");

// security considerations
if (function_exists("libxml_disable_entity_loader"))
    libxml_disable_entity_loader(true);

// always start session handling (produces no error if SAPI=cli)
session_set_cookie_params(0); // persistant user tracking for discern
session_start();

// load instrumentation class and initialize it
// (yes, I know this adds overhead, trust me you'll like it)
siDiscern($poof_init_time);

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

// locate a file from the library
function poof_locate($path)
{
    global $POOF_FILE;  // path to this file
    global $POOF_DIR;   // base directory for poof library
    global $POOF_ROOT;  // directory path to poof library
    global $POOF_URL;   // URL path to poof library
    global $POOF_CWD;   // the current directory
    global $POOF_THEMES;    // array of directories to check first

    if (empty($POOF_DIR))
    {
        // locate the poof library itself, set globals
        $POOF_FILE=__FILE__;
        $POOF_CWD=getcwd();
        $POOF_DIR=dirname($POOF_FILE);
        if (!file_exists("$POOF_DIR/poof.php"))
            Fatal("unable to locate file path to poof library");

        $POOF_ROOT=str_replace($_SERVER['SCRIPT_NAME'],"",$_SERVER['SCRIPT_FILENAME']);
        if (!$POOF_ROOT && !empty($_SERVER['HOME']))
            $POOF_ROOT=$_SERVER['HOME'];
        if (!$POOF_ROOT)
            $POOF_ROOT=$POOF_CWD;

        $POOF_URL=str_replace($POOF_ROOT,"",$POOF_DIR);
        $POOF_THEMES=array();
    }
    if ($path)
    {
        $path=strtolower($path);
        // default must be last theme checked
        foreach (array_merge($POOF_THEMES,array('default')) as $theme)
        {
            $test="$POOF_DIR/theme/$theme/$path";
            if (file_exists($test))
                return($test);
        }
        $test="$POOF_DIR/$path";
        if (file_exists($test))
            return($test);
        Fatal("poof_locate(): did not find '$test'");
    }
    return(false);
}

function poof_url($path)
{
    global $POOF_URL,$POOF_ROOT;
    $filepath=poof_locate($path);
    $relpath=str_replace($POOF_ROOT,$POOF_URL,$filepath);
    return($relpath);
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

    Fatal("unable to locate file '$path'\n");
}
