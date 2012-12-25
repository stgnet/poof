<?php

class uitabbable extends uiElement
{
    //private $list;

    public function __construct($list)
    {
        parent::__construct();
        $this->ui_tag="div";
        $this->ui_class="tabbable";
        //$this->list=$list;

        foreach($list as $name => $element)
            $this->Add($element->SetName($name));
    }

    public function PreGenerate($page)
    {
        $page->ReadyScript('button',"\$('.nav-tabs').button();");
    }

    public function __toString()
    {
        try
        {
            $tabs='';
            $content='';
            $active=true;
            /*
            if ($this->list) foreach ($this->list as $name => $element) {
                $content.="\n<!-- TAB: $name -->";
    
                $div=uiDiv("tab-pane$active");
    
                $tabs.=$this->Tag($active?"li class=\"active\"":"li",
                    $this->Tag("a href=\"#{$div->ui_name}\" data-toggle=\"tab\"",$name)
                );
    
                $content.=$this->Tag($div->GenerateTag(),$element);
    
                $active='';
            }
                $content.="\n<!-- END TABS -->";
            */
            foreach ($this->ContentArray() as $element)
            {
                $content.="\n<!-- TAB: {$element->ui_name} -->";
                $div=uiDiv("tab-pane".($active?" active":""));
    
                $tabs.=$this->Tag("li".($active?" class=\"active\"":""),
                    $this->Tag("a href=\"#{$div->ui_id}\" data-toggle=\"tab\"",$element->ui_name)
                );
    
                $content.=$this->Tag($div->GenerateTag(),$element);
    
                $active=false;
            }
            $content.="\n<!-- END TABS -->";
    
            return($this->Tag($this->GenerateTag(),
                $this->Tag("ul class=\"nav nav-tabs\"",$tabs).
                $this->Tag("div class=\"tab-content\"",$content)
            ));
        }
        catch (Exception $e)
        {
            siError($e);
            return('');
        }

        //return($this->Tag("ul class=\"nav\"",$list).$this->GenerateContent());
    }
}
