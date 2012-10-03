<?php

    // 'markup language' class aka tree structure with attr & text storage aka xml

    class mlBase extends ArrayObject
    {
        protected $ml_tag;        // tag for this element
        protected $ml_attr;       // array of name="value" pairs
        protected $ml_pretext;    // text prior to contents
        protected $ml_postext;    // text after contents

        public function __construct($text=false)
        {
            $dontnest=array("a");

            if (!$text) return;
            $stack=array();
            foreach (explode('<',$text) as $section)
            {
                if (!$section) continue;
                $exp=explode('>',$section,2);
                $ml=false;
                $tag=trim($exp[0]);
                if (!empty($exp[1]))
                    $ml=trim($exp[1]);

                if (empty($tag) && empty($ml)) continue;

                // ignore comments
                if ($tag[0]=='!')
                    continue;

                // close tag
                if ($tag[0]=='/')
                {
                    $tag=substr($tag,1);
                    $last=$this;
                    if ($this->tag_in($tag,$stack))
                        while (count($stack)>1)
                        {
                            $last=array_pop($stack);
                            if ($last->ml_tag==$tag)
                                break;
                        }
                    end($stack)->ml_postext.=$ml;
                    continue;
                }


                $exp=explode(' ',$tag,2);
                $attr=false;
                if (!empty($exp[1]))
                    $attr=$exp[1];
                $tag=$exp[0];

                if (in_array($tag,$dontnest) && $this->tag_in($tag,$stack))
                {
                    while (count($stack)>1)
                    {
                        $last=array_pop($stack);
                        print("Checking {$last->ml_tag} against $tag\n");
                        if ($last->ml_tag==$tag)
                            break;
                    }
                }

                if (empty($stack))
                {
                    $last=false;
                    $element=$this;
                }
                else
                {
                    $last=end($stack);
                    $class=get_class($this);
                    $element=new $class();
                }

                if ($last)
                    $last[]=$element;

                if (substr($attr,-1)=="/")
                    $attr=trim(substr($attr,0,-1));
                else
                    $stack[]=$element;

                $element->ml_tag=$tag;
                $element->ml_attr=$attr;
                $element->ml_pretext=$ml;
            }
        }
        public function FirstTag($tag)
        {
            $found=false;
            if ($this->ml_tag==$tag)
                return($this);
            if (count($this))
                foreach ($this as $element)
                    if ($found=$element->FirstTag($tag)) break;
            return($found);
        }
        public function ArrayOfTags($tag)
        {
            $list=array();
            if ($this->ml_tag==$tag)
                $list[]=$this;
             if (count($this))
                 foreach ($this as $element)
                     $list=array_merge($list,$element->ArrayOfTags($tag));
             return($list);
        }
        public function tag_in($tag,$list=false)
        {
            if (is_array($list))
            {
                foreach ($list as $element)
                    if ($element->ml_tag==$tag) return(true);
                return(false);
            }
            Fatal("unimplemented");
        }
        public function GetAttr($name)
        {
            if (preg_match("_$name=\"([^\"]*)\"_",$this->ml_attr,$match) && count($match)>1)
                return($match[1]);
            return('');
        }
        public function Add()
        {
            $args=func_get_args();
            foreach ($args as $element)
                $this[]=$element;
            return($this);
        }
        public function asHtml($level=false)
        {
            $output='';
            $indention=" ";
            $level=0+$level;

            $output.=str_repeat($indention,$level);
            $output.="<".$this->ml_tag;
            if ($this->ml_attr) $output.=" ".$this->ml_attr;
            $output.=">";
            $output.=$this->ml_pretext;
            if (count($this))
            {
                $output.="\n";

                foreach ($this as $element)
                    $output.=$element->asHtml($level+1);

                $output.=str_repeat($indention,$level);
            }

            if ($this->ml_postext)
            {
                $output.=str_repeat($indention,$level+1);
                $output.=$this->ml_postext;
                $output.="\n";
                $output.=str_repeat($indention,$level);
            }
            $output.="</".$this->ml_tag.">";
            $output.="\n";

            return($output);
        }
    }

