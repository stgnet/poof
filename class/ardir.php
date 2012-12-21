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
        public function SortNewest()
        {
            $this->uasort(function($a,$b){
                return (filemtime($a)<filemtime($b));
            });
            return($this);
        }
        public function SortOldest()
        {
            $this->uasort(function($a,$b){
                return (filemtime($a)>filemtime($b));
            });
            return($this);
        }
    }
