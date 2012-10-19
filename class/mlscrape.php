<?php

    class mlScrape extends mlBase
    {
        public function __construct($text=false)
        {
            if (!$text) return;

            $ctx=stream_context_create(array(
                'http'=>array('timeout'=>10)
            ));
            if (substr($text,0,7)=="http://" || substr($text,0,8)=="https://")
            {
                $url=$text;
                $text=file_get_contents($text,0,$ctx);
                    //$text="Unable to get contents of $url\n".print_r($e,true);
            }

            parent::__construct($text);
        }
        public function ScrapeText()
        {
            $text=$this->ml_pretext;
            foreach ($this as $element)
                $text.=$element->ScrapeText();
            $text.=$this->ml_postext;
            return($text);
        }
        public function ScrapeTable()
        {
            $fields=array();
            $records=array();
            $thead=$this->FirstTag("thead");
            if ($thead)
            {
                $tr=$thead->FirstTag("tr");
                if ($tr)
                {
                    foreach ($tr->ArrayOfTags("th") as $th)
                    {
                        $fields[]=$th->ScrapeText();
                    }
                }
            }
            $tbody=$this->FirstTag("tbody");
            if (!$tbody)
                $tbody=$this;
            foreach ($tbody->ArrayOfTags("tr") as $tr)
            {
                $record=array();
                $idx=0;
                foreach ($tr->ArrayOfTags("td") as $td)
                {
                    if (empty($fields[$idx]))
                        $fields[$idx]="$idx";

                    $record[$fields[$idx]]=$td->ScrapeText();

                    $idx++;
                }
                $records[]=$record;
            }
            return($records);
        }
    }
