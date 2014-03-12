<?

//--------------------------------------------------------------------------------------------------
//*	test of kapenta window manager
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	admins only
	//----------------------------------------------------------------------------------------------
	if ('admin' != $kapenta->user->role) { $kapenta->page->do403(); }


	//----------------------------------------------------------------------------------------------
	//	test
	//----------------------------------------------------------------------------------------------

	$url = $kapenta->serverPath . 'live/chat/admin';

	$testcontents = "
	<br/>
	<input type='button' value='Make Window' "
	 . "onClick=\"kwindowmanager.createWindow('Window Test', '" . $url . "');\" />\n";

	//----------------------------------------------------------------------------------------------
	//	render the page
	//----------------------------------------------------------------------------------------------

	$kapenta->page->load('modules/live/actions/testwindows.page.php');
	$kapenta->page->blockArgs['testcontents'] = $testcontents;
	$kapenta->page->render();


?>
