<?php
	// load extra configuation if running standalone
	//if (empty($GLOBALS['sugar_config']))
	//	require('../axia_axqlib/axqlib-sugar.php');

	// load base library
	require('poof.php');

	global $POOF_UI_DEBUG;

	$POOF_UI_DEBUG=true;


	$page=new uiPage();
	$page->Add(new uiHeader("POOF Diagnostic Tool"));
	//$page->Add(new uiSession());
	$page->Add(new uiDebug('_SESSION'));
	$page->Add(new uiDebug('_SERVER'));
	$page->Generate();
