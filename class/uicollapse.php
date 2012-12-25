<?php

class uicollapse extends uiElement
{
    private $list;

    public function __construct($list)
    {
        parent::__construct();
        $this->ui_tag="div";
        $this->ui_class="accordian";
        $this->list=$list;
    }
    public function PreGenerate($page)
    {
        $page->ReadyScript('collapse',"\$('.collapse').collapse();");
    }

    public function __toString()
    {
        try
        {
            $output='';
            $group='';
            $active=true;
            if ($this->list) foreach ($this->list as $name => $element) {

                $div=uiDiv("accordian-body collapse");
                if (!$active)
                    $div->AddClass("in");

                $group=$this->Tag("div class=\"accordian-heading\"",
                    $this->Tag("a class=\"accordian-toggle\"
data-toggle=\"collapse\" data-parent=\"#{$this->ui_id}\"
href=\"#{$div->ui_id}\" ",$name)
                );

                $group.=$this->Tag($div->GenerateTag(),
                    $this->Tag($element->GenerateTag(),$element)
                );

                $active=false;

                $output.=$this->Tag("div class=\"accordian-group\"",
                    $group);
            }
    
            return($this->Tag($this->GenerateTag(),$output.
                $this->GenerateContent()
            ));
        }
        catch (Exception $e)
        {
            siError($e);
            return('');
        }
    }
}
