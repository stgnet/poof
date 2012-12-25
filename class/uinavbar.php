<?php

class uinavbar extends uiElement
{
    public function __construct()
    {
        parent::__construct();
        $this->ui_tag="div";
        $this->ui_class="container";
    }

    public function __toString()
    {
        try
        {
            return($this->Tag($this->GenerateTag(),
                $this->Tag("div class=\"navbar\"",
                    $this->Tag("div class=\"navbar-inner\"",
                        $this->Tag("div class=\"container\"",
                            $this->GenerateContent()
                        )
                    )
                )
            ));
        }
        catch (Exception ($e))
        {
            siError($e);
            return('');
        }
    }
}
