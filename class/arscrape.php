<?php

    class arScrape extends arBase
    {
        public function __construct($text)
        {
            // remove comments
            $text=preg_replace('/<!--.*-->/',"",$text);

            foreach (explode('<',$text) as $section)
            {
                if (!$section) continue;
                $exp=explode('>',$section,2);
                $html=false;
                $tag=$exp[0];
                if (!empty($exp[1]))
                    $html=trim($exp[1]);
                    print("< $tag >");
                    if ($html)
                    print("=| $html |");
                print("\n");
            }
        }
    }

