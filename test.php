<?php
	// load extra configuation if running standalone
	//if (empty($GLOBALS['sugar_config']))
	//	require('../axia_axqlib/axqlib-sugar.php');

	// load base library
	require('poof.php');

	global $POOF_UI_DEBUG;

	$POOF_UI_DEBUG=true;


	//if ($_SERVER['HTTP_HOST']!='localhost')
	{
		$page=new uiPage();
		$page->Add(new uiHeader("POOF Diagnostic Tool"));
		//$page->Add(new uiSession());
		$page->Add(new uiDebug('_SESSION'));
		$page->Add(new uiDebug('_SERVER'));
		$page->Generate();
		return;
		exit(0);
	}
/*

//require("modules/Accounts/vardefs.php");

	$GLOBALS['AXQLIB_UI_DEBUG']=true;

	// initialize database connection
	$sugar=new dbsugar();
	$users=new dbsugar($sugar,'users');

	$users_cstm=$users->custom('users_cstm');
*/
/*
	function SelectTable($option)
	{
		$selected_table=$option

		$table_db=new dbsugar($sugar,$selected_table);

		$table_element=new uiTable($table_db);

		$target->Add($table_element);
		$target->Generate();
	}

*/
/*
	// ### build UI tree backwards ###

	// target area for selector
	$target=new uiElement(); // empty element

	// selector for which database
//	$selector=new uiSelect($sugar,$target);
//	$selector->SetPost('SelectTable');

	$record=array();

*/
/*

	$userlist=array();
	foreach ($users->records() as $record)
		$userlist[$record['id']]=$record['first_name']." ".$record['last_name'];

	$fields=array();
	$fields['user']=array('prompt'=>"Select User",'type'=>"select",'options'=>$userlist);
	$fields['userfile']=array('prompt'=>"Select File",'type'=>"file");
	$fields['submit']=array('type'=>"submit",'value'=>"Upload and Import");

	$formtest=new uiForm($record,$fields,$target);
*/

/*
	$fields=array(
		'first_name'		=> "First Name",
		'last_name'		=> "Last Name",
		'asterisk_ext_c'	=> "Extension Number",
		'asterisk_outbound_c'	=> "Enable Click to Dial",
	);

	$test=new uiEditable($users_cstm,$fields);

	// top of page
	$page=new uiElement();
	$page->Add(new uiHeader("AXIA PHP Library - Diagnostic Tool"));

	$page->Add($test);


	$page->Add($target);
//	$page->Add(new uiSession());
//	$page->Add(new uiDebug('_SESSION'));
//	$page->Add(new uiDebug('_SERVER'));

	// generate page
	$page->Generate();

*/
