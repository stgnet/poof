<?php
// generated by make.php - do not edit

/**
 * convenience constructor functions
 * @package poof
 */

function arbase()
{
    return new arbase();
}

function ardir($path=false)
{
    return new ardir($path);
}

function arfile($file)
{
    return new arfile($file);
}

function arquery($query)
{
    return new arquery($query);
}

function audigest($realm,$users,$fail=false)
{
    return new audigest($realm,$users,$fail);
}

function dbbase()
{
    return new dbbase();
}

function dbcsv($file)
{
    return new dbcsv($file);
}

function dbcsv_daemon($path)
{
    return new dbcsv_daemon($path);
}

function dbflat($file,$fields)
{
    return new dbflat($file,$fields);
}

function event()
{
    return new event();
}

function mlbase($text=false)
{
    return new mlbase($text);
}

function mlscrape($text=false)
{
    return new mlscrape($text);
}

function pfbase()
{
    return new pfbase();
}

function pfdaemon($name,$path=false)
{
    return new pfdaemon($name,$path);
}

function pfdaemonserver($error)
{
    return new pfdaemonserver($error);
}

function pfsingleton()
{
    return new pfsingleton();
}

function sidiscern($init_time=false)
{
    if (empty($GLOBALS['sidiscern']))
        $GLOBALS['sidiscern']=new sidiscern($init_time);
    return $GLOBALS['sidiscern'];
}

function uiarticle($class=false)
{
    return new uiarticle($class);
}

function uiaside($class=false)
{
    return new uiaside($class);
}

function uiblockquote()
{
    return new uiblockquote();
}

function uibutton($text=false,$href=false)
{
    return new uibutton($text,$href);
}

function uicarousel($list=false)
{
    return new uicarousel($list);
}

function uicodemirror($text)
{
    return new uicodemirror($text);
}

function uicollapse($list)
{
    return new uicollapse($list);
}

function uicontainer($class=false)
{
    return new uicontainer($class);
}

function uicontainerfluid($class=false)
{
    return new uicontainerfluid($class);
}

function uidaterange()
{
    return new uidaterange();
}

function uidebug($what=false)
{
    return new uidebug($what);
}

function uidiv($class=false)
{
    return new uidiv($class);
}

function uidivider()
{
    return new uidivider();
}

function uidropdown($text,$list=false)
{
    return new uidropdown($text,$list);
}

function uieditable($db,$fields=NULL)
{
    return new uieditable($db,$fields);
}

function uielement()
{
    return new uielement();
}

function uifooter($class=false)
{
    return new uifooter($class);
}

function uiform($fields=false,$record=false,$style=false)
{
    return new uiform($fields,$record,$style);
}

function uigittip($username)
{
    return new uigittip($username);
}

function uigoogleanalytics($account,$domain=false)
{
    return new uigoogleanalytics($account,$domain);
}

function uiheader($class=false)
{
    return new uiheader($class);
}

function uiheading($level,$text=false)
{
    return new uiheading($level,$text);
}

function uihero()
{
    return new uihero();
}

function uihgroup($class=false)
{
    return new uihgroup($class);
}

function uihtml($html)
{
    return new uihtml($html);
}

function uiicon($name)
{
    return new uiicon($name);
}

function uiiconbutton($icon,$name=false)
{
    return new uiiconbutton($icon,$name);
}

function uiimage($src)
{
    return new uiimage($src);
}

function uiinput_base($attr=false,$valid)
{
    return new uiinput_base($attr,$valid);
}

function uiinput_button($attr=false)
{
    return new uiinput_button($attr);
}

function uiinput_cancel($attr=false)
{
    return new uiinput_button($attr);
}

function uiinput_checkbox($attr=false)
{
    return new uiinput_checkbox($attr);
}

function uiinput_radio($attr=false)
{
    return new uiinput_radio($attr);
}

function uiinput_select($attr=false)
{
    return new uiinput_select($attr);
}

function uiinput_text($attr=false)
{
    return new uiinput_text($attr);
}

function uiinput_password($attr=false)
{
    return new uiinput_text($attr);
}

function uiinput_hidden($attr=false)
{
    return new uiinput_text($attr);
}

function uiinput_image($attr=false)
{
    return new uiinput_text($attr);
}

function uiinput_reset($attr=false)
{
    return new uiinput_text($attr);
}

function uiinput_submit($attr=false)
{
    return new uiinput_text($attr);
}

function uiinput_color($attr=false)
{
    return new uiinput_text($attr);
}

function uiinput_date($attr=false)
{
    return new uiinput_text($attr);
}

function uiinput_datetime($attr=false)
{
    return new uiinput_text($attr);
}

function uiinput_datetime_local($attr=false)
{
    return new uiinput_text($attr);
}

function uiinput_email($attr=false)
{
    return new uiinput_text($attr);
}

function uiinput_month($attr=false)
{
    return new uiinput_text($attr);
}

function uiinput_number($attr=false)
{
    return new uiinput_text($attr);
}

function uiinput_range($attr=false)
{
    return new uiinput_text($attr);
}

function uiinput_search($attr=false)
{
    return new uiinput_text($attr);
}

function uiinput_tel($attr=false)
{
    return new uiinput_text($attr);
}

function uiinput_time($attr=false)
{
    return new uiinput_text($attr);
}

function uiinput_url($attr=false)
{
    return new uiinput_text($attr);
}

function uiinput_week($attr=false)
{
    return new uiinput_text($attr);
}

function uiinput_key($attr=false)
{
    return new uiinput_text($attr);
}

function uiinput_textarea($attr=false)
{
    return new uiinput_textarea($attr);
}

function uilabel($text=false)
{
    return new uilabel($text);
}

function uilead($text)
{
    return new uilead($text);
}

function uilegend($text=false)
{
    return new uilegend($text);
}

function uilink($href,$text=false)
{
    return new uilink($href,$text);
}

function uilist($list=false)
{
    return new uilist($list);
}

function uinav($class=false)
{
    return new uinav($class);
}

function uinavbar()
{
    return new uinavbar();
}

function uinavlist($list=false)
{
    return new uinavlist($list);
}

function uipage($meta)
{
    return new uipage($meta);
}

function uiparagraph($text=false)
{
    return new uiparagraph($text);
}

function uipopover($text)
{
    return new uipopover($text);
}

function uipre($text)
{
    return new uipre($text);
}

function uirow($class=false)
{
    return new uirow($class);
}

function uirowfluid($class=false)
{
    return new uirowfluid($class);
}

function uiscript($code)
{
    return new uiscript($code);
}

function uisection($class=false)
{
    return new uisection($class);
}

function uispan($number,$offset=false)
{
    return new uispan($number,$offset);
}

function uitabbable($list)
{
    return new uitabbable($list);
}

function uitable($db,$fields=NULL)
{
    return new uitable($db,$fields);
}

function uitheme($page)
{
    return new uitheme($page);
}

function uitooltip($text)
{
    return new uitooltip($text);
}

function uiwell($class=false)
{
    return new uiwell($class);
}

function uiyoutube($url,$width=640,$height=false)
{
    return new uiyoutube($url,$width,$height);
}
