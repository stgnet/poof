<?php

// new uiElement base class replaces ui and uibase classes

class uiElement extends pfBase
{
    private $ui_parent;
    private $ui_contents;
    protected $ui_tag;
    protected $ui_id;
    protected $ui_class;
    protected $ui_style;
    protected $ui_attr;
    protected $ui_html;
    protected $ui_text;
    protected $ui_postfunc;
    protected $ui_name;

    public function __construct()
    {
        $id=substr(strtolower(get_class($this)),2);
        $counter="POOF_UI_DIV_".$id;
        if (empty($GLOBALS[$counter]))
            $GLOBALS[$counter]=1;
        $this->ui_id=$id.$GLOBALS[$counter]++;
        $this->ui_tag="div";
        $this->ui_class=false;
        $this->ui_style=false;
        $this->ui_attr=array();
        $this->ui_html=false;
        $this->ui_text=false;
        $this->ui_postfunc=false;
        $this->ui_name=false;
        $this->ui_contents=array();
        //siDiscern()->Event("debug-uielement-construct",$this->ui_id);
    }
    /*
    public function SetTag($tag)
    {
        $this->ui_tag=$tag;

        return($this);
    }
    */
    public function SetName($name)
    {
        $this->ui_name=$name;
        return($this);
    }
    public function GetName()
    {
        return($this->ui_name);
    }
    public function AddAttr($name,$value)
    {
        $this->ui_attr[$name]=htmlentities($value);

        return($this);
    }
    public function AddClass($class)
    {
        if (empty($this->ui_class))
            $this->ui_class=$class;
        else
            $this->ui_class.=" ".$class;
        // chaining
        return($this);
    }

    // style & formatting
    public function Center()
    {
        return($this->AddClass("pagination-centered"));
    }
    public function Right()
    {
        return($this->AddClass("pull-right"));
    }
    public function Left()
    {
        return($this->AddClass("pull-left"));
    }
    public function Border($width=1,$color='#888',$style='solid')
    {
        return($this->AddStyle("border: {$width}px $style $color;"));
    }
    public function Margin($value)
    {
        return($this->AddStyle("margin: {$value};"));
    }
    public function MarginBottom($value)
    {
        return($this->AddStyle("margin-bottom: {$value};"));
    }
    public function MarginLeft($value)
    {
        return($this->AddStyle("margin-left: {$value};"));
    }
    public function MarginRight($value)
    {
        return($this->AddStyle("margin-right: {$value};"));
    }
    public function MarginTop($value)
    {
        return($this->AddStyle("margin-top: {$value};"));
    }
    public function Background($color)
    {
        return($this->AddStyle("background: $color;"));
    }
    public function AddStyle($style)
    {
        if (empty($this->ui_style))
            $this->ui_style=$style;
        else
            $this->ui_style.=" ".$style;

        return($this);
    }

    public function Add()
    {
        $args=func_get_args();
        foreach ($args as $obj) {
            if (is_string($obj))
                $obj=uiHtml($obj);

            if (!$obj instanceof uiElement)
                Fatal("uiElement::Add(".get_class($obj)."): incompatible object not based on uiElement! ");

            if ($obj->ui_parent)
                Fatal("uiElement::Add({$obj->ui_id}): already added to {$obj->ui_parent->ui_id}");

            //siDiscern()->Event('debug-ui-add',array('parent'=>$this->ui_id,'child'=>$obj->ui_id));
            $this->ui_contents[]=$obj;
            $obj->ui_parent=&$this;
        }

        // Add() returns this to allow convenient tree building
        return($this);
    }
    public function PreGenerateWalk($page)
    {
        if (method_exists($this,"PreGenerate"))
            $this->PreGenerate($page);

        if ($this->ui_contents) foreach ($this->ui_contents as $element)
            $element->PreGenerateWalk($page);
    }

    public function GetPath()
    {
        // the SCRIPT_NAME is pre-stripped URL matching actual php executed
        $path=$_SERVER['SCRIPT_NAME'];

        // when operating in a sugar mode, the module path needs to be artificially added
        if (!empty($GLOBALS['sugar_config']) && !empty($_REQUEST['module']))
            $path=dirname($path)."/modules/".$_REQUEST['module']."/index.php";

        return($path);
    }

    // this returns a full URL to the php script, plus an extended path
    // that matches the UI element tree names
    public function GetAction()
    {
        $action=$this->GetPath();
        if (substr($action,0,2)=='//')
            $action=substr($action,1);

        // construct element stack
        $path='';
        $walk=&$this;
        while ($walk) {
            $path="/".$walk->ui_id.$path;
            $walk=$walk->ui_parent;
        }
        $action.=$path;

        return($action);
    }

    // sanitize a fully descriptive field array
    // to be just 'name'=>"Descriptive Title"
    public function FieldsWithNames($fields)
    {
        $fieldnames=array();
        foreach ($fields as $name => $details)
        {
            if (is_array($details))
            {
                if (empty($details['desc']))
                    $fieldnames[$name]=str_replace(array('-','_')," ",$name);
                else
                    $fieldnames[$name]=$details['desc'];
            }
            else
                $fieldnames[$name]=$details;
        }
        return($fieldnames);
    }
    /* deprecated:
    public function DefaultFields($db)
    {
        // obtain list of fields for table/edit from database itself
        $fields=array();
        foreach ($db->fields() as $field) {
            $name=str_replace(array('-','_')," ",$field);
            $fields[$field]=ucwords($name);
        }

        return($fields);
    }
    */
    public function Post($func)
    {
        $this->ui_postfunc=$func;
        return($this);
    }
    public function PassToPostHandler()
    {
        global $_FILES;

        // iterate the tree and locate the element that matches the action
        $action=$this->GetAction();
        $self=$_SERVER['PHP_SELF'];

        $msg='';
        foreach (debug_backtrace() as $stack)
            $msg.=$stack['function']."() in ".$stack['file']." #".$stack['line']."\n";
        //siDiscern()->Event('debug-ptph-'.$this->ui_id,$msg);

        if ($action==$self) 
        {
            if ($this->ui_postfunc)
            {
        //siDiscern()->Event('debug-postfunc',array('id'=>$this->ui_id,'action'=>$action,'self'=>$self));
                $_SERVER['REQUEST_METHOD']="--handled--";
                $this->ui_postfunc($_POST);
                return(true);
            }
            if (!method_exists($this,"PostHandler"))
                Fatal("class element does not implement PostHandler - $self");

        //siDiscern()->Event('debug-posthand',array('id'=>$this->ui_id,'action'=>$action,'self'=>$self));
            if (empty($_FILES))
                return($this->PostHandler($_POST));
            else
                return($this->PostHandler($_POST,$_FILES));
        }


        //$path=substr($_SERVER['PHP_SELF'],strlen($action)+1);
        $path=str_replace($action."/","",$self);
        if (!$path) Fatal("error decoding path to $self");

        $subpath=explode('/',$path);
        $post_is_for=$subpath[0];

        //siDiscern()->event('debug',array('id'=>$this->ui_id,'action'=>$action,'self'=>$self,'path'=>$path,'for'=>$post_is_for));

        if (substr($self,0,strlen($action))!=$action)
            Fatal("object '$action' does not match request '$self'");

        if ($this->ui_contents) foreach ($this->ui_contents as $element) 
        {
            //siDiscern()->event('debug',array('id'=>$element->ui_id));
            if ($element->ui_id==$post_is_for) 
            {
                return($element->PassToPostHandler());
            }
        }
        Fatal("unable to locate post element '$post_is_for'");
    }

    public function Indent($adjust=0)
    {
        global $POOF_UI_LEVEL;

        $indention="    ";

        if ($adjust<=0)
            $POOF_UI_LEVEL+=$adjust;
        $output="\n".str_repeat($indention,$POOF_UI_LEVEL);
        if ($adjust>0)
            $POOF_UI_LEVEL+=$adjust;

        return($output);
    }
    public function Tag($tag,$cont=false)
    {
        $untag=explode(' ',$tag);
        $untag=$untag[0];
        $dontclose=array('script','i','iframe','div');

        // this prevents elements' __toString()
        // from being called more than once
        $contents=(string) $cont;

        if (empty($contents) && !in_array($untag,$dontclose))//$untag!="script" && $untag!="i")
            return($this->Indent()."<$tag />");

        if (!substr_count($contents,"\n") || $untag=="pre")
            return($this->Indent()."<$tag>$contents</$untag>");

        return($this->Indent(1)."<$tag>$contents".$this->Indent(-1)."</$untag>");
    }
    public function GenerateTag()
    {
        $tag="{$this->ui_tag} id=\"$this->ui_id\"";
        if ($this->ui_name)
            $tag.=" name=\"{$this->ui_name}\"";
        if ($this->ui_class)
            $tag.=" class=\"{$this->ui_class}\"";
        if ($this->ui_style)
            $tag.=" style=\"{$this->ui_style}\"";
        foreach ($this->ui_attr as $name => $value)
            $tag.=" $name=\"$value\"";

        return($tag);
    }
    public function ContentArray()
    {
        if ($this->ui_contents)
            return($this->ui_contents);
        else
            return(array());
    }
    public function GenerateContentArray()
    {
        // just like GenerateContent, except that it
        // returns an array for each subelement (first level only)
        // so that caller can wrap li tags or such
        $content=array();
        if ($this->ui_contents) foreach ($this->ui_contents as $element)
            $content[]=$element;

        return($content);
    }
    public function GenerateContent()
    {
        global $POOF_UI_DEBUG;

        if (empty($this->ui_parent))
        {
            if (!empty($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=='POST')
            {
                $_SERVER['REQUEST_METHOD']='post-handled';
                if ($this->PassToPostHandler())
                    return('');
            }
        }

        $output='';
        if ($this->ui_contents) foreach ($this->ui_contents as $element) {
            if (!$element->ui_id) Fatal("UI Element Name not set");

            // debugging aid:
            if (!empty($POOF_UI_DEBUG) || !empty($_GET['debug']))
                $output.="<div style=\"margin: 10px; border: 3px #aaa solid;box-shadow: 5px 5px 2px #444 ;\"><div style=\"background-color: #aaa;\">".htmlentities("<".$element->GenerateTag().">")."</div>\n";

            //$output.=$this->Tag($element->GenerateTag(),$element);
            $output.=$element;

            if (!empty($POOF_UI_DEBUG) || !empty($_GET['debug']))
                $output.="</div>\n";
        }

        return($output);
    }

    // generate output, but also content from child elements
    // child classes can either preset ui_vars in constructor, or
    // override this to generate specific output
    public function __toString()
    {
        if (!$this->ui_tag)
            return($this->ui_html.htmlentities($this->ui_text).$this->GenerateContent());
        return($this->Tag($this->GenerateTag(),
            $this->ui_html.htmlentities($this->ui_text).$this->GenerateContent()
        ));
    }
}
