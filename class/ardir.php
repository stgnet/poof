<?php

    /**
     * provides list of files in a directory
     * @package poof
    */
    class arDir extends arBase
    {
        /**
         * @param string $path optional directory to scan
        */
        public function __construct($path=false)
        {
            if (!$path)
                $path=".";
            $d=dir($path);
            if ($path=="." || $path=="./") $path="";
            if ($path && substr($path,-1,1)!="/") $path.="/";
            while ($file=$d->read())
                $this[]=$path.$file;
        }
    }
