<?php

class uibutton extends uiElement
{
    private $click;
    private $target;

    public function __construct($text=false,$href=false)
    {
        $this->click=false;

        parent::__construct();
        if ($href) {
            $this->ui_tag="a";
            $this->ui_class="btn";
            //$this->ui_attr="href=\"$href\"";
            $this->AddAttr('href',$href);
            $this->ui_text=$text;
        } else {
            $this->ui_tag="button";
            $this->ui_class="btn";
            $this->ui_text=$text;
        }
    }

    public function Post($func)
    {
        $this->click=true;
        return(parent::Post($func));
    }
    public function Target($that)
    {
        $this->target=$that;
        return($this);
    }
    public function PreGenerate($page)
    {
        if (!$this->click) return;

        $button='#'.$this->ui_id;
        $url=$this->GetAction();
        $id=$this->ui_id;
        $name=$this->ui_text;

        if ($this->target)
        {
            $target='#'.$this->target->ui_id;

            $page->ReadyScript($id,"
                \$('$button').click(function(){
                            \$('$button').addClass('disabled').removeClass('btn-danger');
                    \$('$target').empty().append('Loading...');
                    \$.ajax({
                        type: 'POST',
                        url: '$url',
                        data: {'button':'$id','value':'$name'},
                        success: function(data) {
                            \$('$target').empty().append(data);
                            \$('$button').removeClass('disabled');
                        },
                        error: function(xhr) {
                            \$('$target').empty().append(xhr.status+' '+xhr.statusText+': '+xhr.responseText);
                            \$('$button').addClass('btn-danger').removeClass('disabled');
                        }
                    });
                });
            ");
            return;
        }

        $page->ReadyScript($id,"
            \$('$button').click(function(){
                            \$('$button').addClass('disabled').removeClass('btn-danger');
                \$.ajax({
                    type: 'POST',
                    url: '$url',
                    data: {'button':'$id','value':'$name'},
                    success: function(data) {
                            \$('$button').removeClass('disabled');
                    },
                    error: function(xhr) {
                            \$('$button').addClass('btn-danger').removeClass('disabled');
                    }
                });
            });
        ");
    }
}
