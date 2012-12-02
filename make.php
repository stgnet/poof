<?php
    // run this after library changes to regenerate support files
    //require 'error_handler.php';

    function make_constructors()
    {
        // update the convenience function list from classes
        $output='<'.'?php'."\n// generated by make.php - do not edit\n";
        $output.='
/**
 * convenience constructor functions
 * @package poof
 */
';


        // scan files in the class directory
        $d=dir("class");
        $files=array();
        while ($file=$d->read())
            $files[]=$file;
        $d->close();

        // scan files in the theme/default/class directory
        $d=dir("theme/default/class");
        while ($file=$d->read())
            $files[]=$file;
        $d->close();

        sort($files);

        foreach ($files as $file) {
            // skip anything that isn't an autoload-able class
            if (!preg_match('/^(.*)\.php$/',$file,$match)) {
                if ($file[0]!='.') print("WARNING: skipping $file\n");
                continue;
            }

            $class=$match[1];
//print($class."\n");

            if (file_exists("theme/default/class/$file"))
                $contents=file_get_contents("theme/default/class/$file");
            else
                $contents=file_get_contents("class/$file");
            $singleton=false;
            if (strstr($contents,"extends pfSingleton"))
                $singleton=true;

            // locate the construct function to get args - and warn if not found
            if (!preg_match('/function\s+__construct\((.*)\)/',$contents,$match)) {
                print("$class: __construct() not found!\n");
                continue;
            }
            $args=$match[1];

            // break apart the argument list and remove default assignments
            $pairs=explode(',',$args);
            foreach ($pairs as &$pair) {
                $exp=explode('=',$pair);
                $pair=$exp[0];
            }
            $justargs=implode(',',$pairs);

            if ($singleton)
                $output.="
function $class($args)
{
    if (empty(\$GLOBALS['$class']))
        \$GLOBALS['$class']=new $class($justargs);
    return \$GLOBALS['$class'];
}
";

            else
                $output.="
function $class($args)
{
    return new $class($justargs);
}
";


            preg_match_all('/POOF_CONSTRUCT:\s+(\S+)/',$contents,$matches,PREG_SET_ORDER);
            foreach ($matches as $match) {
                $alternate=strtolower($match[1]);
                //print("Adding alternate $alternate for $class\n");

                $output.="
function $alternate($args)
{
    return new $class($justargs);
}
";

            }
        }

        file_put_contents("class_constructors.php",$output);
    }

    // make all the components needed
    make_constructors();
    system("phpdoc -d . -i 'demo/*' -i 'tests/*' -t docs");

