<?

//-------------------------------------------------------------------------------------------------
//	displays the page log live
//-------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------
	//	auth - only admins can do this
	//---------------------------------------------------------------------------------------------
	if ('admin' != $user->role) { $page->do403(); }

	//---------------------------------------------------------------------------------------------
	//	render page
	//---------------------------------------------------------------------------------------------
	$kapenta->page->load('modules/admin/actions/livelog.page.php');
	$page->jsinit .= "\n\t\tmsgSubscribe('admin-syspagelogsimple', msgh_sysPageLog);\n";
	$kapenta->page->render();

?>