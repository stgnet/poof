<?php
	// load base library
	require('poof.php');

	global $POOF_UI_DEBUG;
	if (!empty($_GET['debug']))
		$POOF_UI_DEBUG=true;

	echo uiPage("POOF")->Add(
		uiHeader("POOF Diagnostic Tool")->Add(
			uiDebug('POOF_DIR'),
			uiDebug('POOF_URL')
		),
		uiDebug('_SESSION'),
		uiDebug('_SERVER'),
		uiDebug('_GET'),
		uiDebug('_POST')
	);
