<?php

    // conver query string to array of name => value

    class arQuery extends arBase
    {
        public function __construct($query)
        {
            foreach (explode('&',$query) as $section)
            {
                $pair=explode('=',$section);
                if (empty($pair[1]))
                    $pair[1]=false;
                $this[urldecode($pair[0])]=urldecode($pair[1]);
            }
        }
    }
