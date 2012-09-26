<?php

class uidebug extends uiElement
{
    private $what;

    public function __construct($what=false)
    {
        parent::__construct();
        if ($what)
            $this->what=$what;
        else
            $this->what="GLOBALS";
    }

    public function __toString()
    {
        $text=$this->what." = ";

        if (isset($GLOBALS[$this->what]))
            $text.=print_r($GLOBALS[$this->what],true);
        else
            $text.="** ERROR: {$this->what} NOT SET **";

        return($this->Tag("pre",htmlentities($text)));

    }
}
