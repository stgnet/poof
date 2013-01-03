<?php

class uipage extends uiElement
{
    private $ui_meta;
    private $ui_styles;
    private $ui_prescripts;
    private $ui_postscripts;
    private $ui_headscripts;
    private $ui_tailscripts;
    private $ui_readyscripts;

    public function __construct($meta='')
    {
        parent::__construct();
        $this->ui_tag="body";
        $this->ui_meta=$meta;

        $this->ui_styles=array();
        $this->ui_prescripts=array();
        // jquery always goes first!
        $this->ui_postscripts=array();
        $this->ui_headscripts=array();
        $this->ui_tailscripts=array();
        $this->ui_readyscripts=array();

        if (!is_array($meta))
            $this->ui_meta=array('title'=>$meta);


        // activate the theme (it calls back to add elements)
        uiTheme($this);
    }
    public function Stylesheet($name,$file)
    {
        $this->ui_styles[$name]=$file;
    }
    public function PreScript($name,$file)
    {
        $this->ui_prescripts[$name]=$file;
    }
    public function PostScript($name,$file)
    {
        $this->ui_postscripts[$name]=$file;
    }
    public function HeadScript($name,$code)
    {
        $this->ui_headscripts[$name]=$code;
    }
    public function TailScript($name,$code)
    {
        $this->ui_tailscripts[$name]=$code;
    }
    public function ReadyScript($name,$code)
    {
        $this->ui_readyscripts[$name]=$code;
    }
    public function GenerateStyles()
    {
        global $POOF_URL;

        $output='';

        foreach ($this->ui_styles as $style)
            $output.=$this->Tag("link href=\"".
                poof_url("css/$style").
                "\" rel=\"stylesheet\"");

        return($output);
    }
    public function GeneratePreScripts()
    {
        global $POOF_URL;
        $output='';
        $head='';

        foreach ($this->ui_prescripts as $script)
            $output.=$this->Tag("script src=\"".
                poof_url("js/$script").
                "\"");

        $head='';
        foreach ($this->ui_headscripts as $code)
            $head.=" ".$code."\n";

        if (!empty($head))
            $output.=$this->Tag("script type=\"text/javascript\"",$head);

        return($output);
    }
    public function GeneratePostScripts()
    {
        global $POOF_URL;
        $output='';

        foreach ($this->ui_postscripts as $script)
            $output.=$this->Tag("script src=\"".
                poof_url("js/$script").
                "\"");

        // active the js components
        $ready='';
        foreach ($this->ui_readyscripts as $code)
            $ready.=" ".$code."\n";

        $urlscript=urlencode($_SERVER['SCRIPT_NAME']);

        $ready.="
\$('#{$this->ui_id}').bind('contextmenu',function(e)
{
    if (e.ctrlKey) {
        //alert('right click '+e.toElement.id);
        console.log(\"rightclick=%o\",e);
        window.open('$POOF_URL/index.php?edit=$urlscript&id='+e.toElement.id);
        return false;
    }
});";

        if (!empty($ready))
        $output.=$this->Tag("script type=\"text/javascript\"",
            "\$(document).ready(function(){\n".
            $ready.
            "});\n"
        );

        $tail='';
        foreach ($this->ui_tailscripts as $code)
            $tail.=" ".$code."\n";

        if (!empty($tail))
            $output.=$this->Tag("script type=\"text/javascript\"",$tail);

        return($output);
    }
    public function GenerateMeta()
    {
        $output='';

        foreach ($this->ui_meta as $name => $content)
            if (strtolower($name)!='title')
                $output.=$this->Tag("meta name=\"$name\" content=\"$content\"");
                //$output.=$this->Indent()."<meta name=\"$name\" content=\"$content\">";
        return($output);
    }

    public function __toString()
    {
        try
        {
            global $POOF_DIR;
            global $POOF_URL;
            global $POOF_INIT;

            if (!empty($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=='POST')
            {
                $_SERVER['REQUEST_METHOD']='post-handled';
                siDiscern('post');
                if ($this->PassToPostHandler())
                    return('');
            }

            // allow tree elements to pass scripts/css up to page generator
            siDiscern('pregen');
            $this->PreGenerateWalk($this);

            siDiscern('generate');

            $title=$_SERVER['PHP_SELF'];
            foreach ($this->ui_meta as $name => $content)
                if (strtolower($name)=='title')
                    $title=$content;

            $urlscript=urlencode($_SERVER['SCRIPT_NAME']);
            $toolhref="$POOF_URL/index.php?config=$urlscript";

            $footer_right="";
            $admin_login=empty($_SESSION['POOFSITE']['login'])?false:true;
            $tool_icon='wrench';
            if ($admin_login)
                $tool_icon='cog';
            //$footer_right.="<a href=\"$toolhref\">&nbsp;<i class=\"icon-$tool_icon\"></i>&nbsp;</a>";

            $footer="";
            if ($footer_right)
                $footer.="<p class=\"pull-right\">$footer_right&nbsp;</p>";

            $head=(file_exists("$POOF_DIR/brand.txt")?
                    "\n".file_get_contents("$POOF_DIR/brand.txt"):
                    "\n<!-- Generated by POOF (http://poof.stg.net) -->").
                $this->Tag("title",htmlentities($title)).
                $this->Tag("meta charset=\"utf-8\"").
                $this->GenerateMeta().
                $this->GenerateStyles().
                $this->GeneratePreScripts();

            $body= $this->GenerateContent().
                $this->ui_html.
                $this->GeneratePostScripts();

            return("<!DOCTYPE html>".
                $this->Tag("html lang=\"en\"",
                    $this->Tag("head",$head).
                    $this->Tag($this->GenerateTag(),
                        $body.
                        $footer.
//                        "<p class=\"pull-left\">&nbsp;".number_format(microtime(true)-$POOF_INIT,3)."</p>".

                        "<p class=\"pull-right\" title=\"".
                            (integer)(1000*(microtime(true)-$POOF_INIT))." ms".
                            "\"><a href=\"$toolhref\">
                            <i class=\"icon-$tool_icon\"></i></a>&nbsp;</p>".
                        (siDiscern('output')?'':'')
                    )
                )
            );
        }
        catch(Exception $e)
        {
            siError($e);
            return('');
        }
    }
}
