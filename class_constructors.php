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

function uinavbar($menu)
{
	return new uinavbar($menu);
}

function uipre($text)
{
	return new uipre($text);
}

function uiimage($src,$href)
{
	return new uiimage($src,$href);
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

function uinavlist($list)
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
