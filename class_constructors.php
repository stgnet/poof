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

function uiheader($text)
{
	return new uiheader($text);
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
