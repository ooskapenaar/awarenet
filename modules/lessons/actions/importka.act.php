<?php

	require_once($kapenta->installPath . 'modules/lessons/inc/khan.inc.php');

	if ('admin' !== $kapenta->user->role) { $kapenta->page->do404(); }
	
	$kapenta->fileMakeSubDirs('data/lessons/scraper/x.x');

	$listing = "";
	$courses = lessons_listKhan();
	
	$listing .= $courses;	
	//----------------------------------------------------------------------------------------------
	//	render the page
	//----------------------------------------------------------------------------------------------

	$kapenta->page->load('modules/lessons/actions/importka.page.php');
	$kapenta->page->blockArgs['kalisting'] = $listing;
	$kapenta->page->render();	

?>