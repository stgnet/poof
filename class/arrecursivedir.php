<?php
    /**
     * provides list of files in a directory
     * @package poof
    */
    class arRecursiveDir extends arDir
    {
        /**
         * @param string $path optional directory to scan
        */
        public function __construct($path=false)
        {
            if (!$path)
                $path=".";
            $dirs=array($path);

            while (count($dirs))
            {
                $next=array();
                foreach ($dirs as $dirpath)
                {
                    $d=dir($dirpath);

                    while ($file=$d->read())
                    {
                        if ($file=="." || $file=="..")
                            continue;
                        $fullpath=$dirpath."/".$file;
                        if (substr($fullpath,0,2)=="./")
                            $fullpath=substr($fullpath,2);
                        if (is_dir($fullpath))
                            $next[]=$fullpath;
                        else
                            $this[]=$fullpath;
                    }
                    $d->close();
                }
                $dirs=$next;
            }
        }
    }
