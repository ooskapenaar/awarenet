<?

//--------------------------------------------------------------------------------------------------
//*	list all Kapenta modules
//--------------------------------------------------------------------------------------------------
//role: admin - only administrators may do this

	//----------------------------------------------------------------------------------------------
	//	authorization (admins only)
	//----------------------------------------------------------------------------------------------
	if ('admin' != $user->role) { $page->do403(); }

	//----------------------------------------------------------------------------------------------
	//	show the page
	//----------------------------------------------------------------------------------------------
	$page->load('modules/admin/actions/listmodules.page.php');
	$page->render();

?>
