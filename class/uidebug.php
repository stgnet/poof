<?php

class uidebug extends uiElement
{
    private $what;

    public function __construct($what=false)
    {
        $this->ui_tag="pre";

        parent::__construct();
        if ($what)
            $this->what=$what;
        else
            $this->what="GLOBALS";
    }

    private function DumpConstants()
    {
        $constants=array(
            "PHP_VERSION",
            "PHP_MAJOR_VERSION",
            "PHP_MINOR_VERSION",
            "PHP_RELEASE_VERSION",
            "PHP_EXTRA_VERSION",
            "PHP_ZTS",
            "PHP_DEBUG",
            "PHP_MAXPATHLEN",
            "PHP_OS",
            "PHP_SAPI",
            "PHP_EOL",
            "PHP_INT_MAX",
            "PHP_INT_SIZE",
            "DEFAULT_INCLUDE_PATH",
            "PEAR_INSTALL_DIR",
            "PEAR_EXTENSION_DIR",
            "PHP_EXTENSION_DIR",
            "PHP_PREFIX",
            "PHP_BINDIR",
            "PHP_BINARY",
            "PHP_MANDIR",
            "PHP_LIBDIR",
            "PHP_DATADIR",
            "PHP_SYSCONFDIR",
            "PHP_LOCALSTATEDIR",
            "PHP_CONFIG_FILE_PATH",
            "PHP_SHLIB_SUFFIX",
        );

        $output='';
        foreach ($constants as $constant)
        {
            $value="--error--";
            eval("\$value=$constant;");
            $output.="$constant = '$value'\n";
        }

        return($output);
    }

    public function __toString()
    {
        try
        {
            $text=$this->what." = ";

            if ($this->what=="CONSTANTS")
                $text=print_r($this->DumpConstants(),true);
            else
            if (isset($GLOBALS[$this->what]))
                $text.=print_r($GLOBALS[$this->what],true);
            else
                $text.="** ERROR: {$this->what} NOT SET **";

            return($this->Tag("pre",htmlentities($text)));
        }
        catch (Exception $e)
        {
            siError($e);
            return('');
        }
    }
}
