<?php
	// load extra configuation if running standalone
	//if (empty($GLOBALS['sugar_config']))
	//	require('../stgnet_poof/poof-sugar.php');

	// load base library
	require('poof.php');

	global $POOF_UI_DEBUG;

	$POOF_UI_DEBUG=true;

	$meta=array(
		'title'=>"POOF Diagnostics",
		'keywords'=>"POOF, PHP, Framework, Object-oriented, Orthogonal",
		'description'=>"Diagnostics tool for framework code",
		'viewport'=>"width=device-width, initial-scale=1.0",
	);

	$page=new uiPage($meta);
	$page->Add(new uiHeader("POOF Diagnostic Tool"));
	//$page->Add(new uiSession());
	$page->Add(new uiDebug('POOF_DIR'));
	$page->Add(new uiDebug('POOF_URL'));
	$page->Add(new uiDebug('_SESSION'));
	$page->Add(new uiDebug('_SERVER'));
	$page->Generate();
