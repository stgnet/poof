<?php
function uibutton($text,$href)
{
	return new uibutton($text,$href);
}

function uidaterange()
{
	return new uidaterange();
}

function test($one,$two=false)
{
	return new test($one,$two);
}

function uidivider()
{
	return new uidivider();
}

function uihtml($html)
{
	return new uihtml($html);
}

function uinavbar()
{
	return new uinavbar();
}

function uiicon($name)
{
	return new uiicon($name);
}

function uilink($href,$text=false)
{
	return new uilink($href,$text);
}

function uipre($text)
{
	return new uipre($text);
}

function uiimage($src)
{
	return new uiimage($src);
}

function uilist($list)
{
	return new uilist($list);
}

function uielement()
{
	return new uielement();
}

function uicontainer($class=false)
{
	return new uicontainer($class);
}

function uidiv($class)
{
	return new uidiv($class);
}

function uilead($text)
{
	return new uilead($text);
}

function uiheader($level,$text=false)
{
	return new uiheader($level,$text);
}

function uitable($db,$fields=NULL)
{
	return new uitable($db,$fields);
}

function event()
{
	return new event();
}

function ardir($path)
{
	return new ardir($path);
}

function arbase()
{
	return new arbase();
}

function uinavlist($list=false)
{
	return new uinavlist($list);
}

function uiparagraph($text)
{
	return new uiparagraph($text);
}

function uidebug($what=false)
{
	return new uidebug($what);
}

function dbbase()
{
	return new dbbase();
}

function uipage($meta)
{
	return new uipage($meta);
}

function uiscript($code)
{
	return new uiscript($code);
}

function uihero()
{
	return new uihero();
}

function dbcsv($file)
{
	return new dbcsv($file);
}
