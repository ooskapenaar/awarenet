<?

//--------------------------------------------------------------------------------------------------
//*	list of schools now shown in the main list
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	admins only TODO: make this a regular permission
	//----------------------------------------------------------------------------------------------
	if ('admin' != $user->role) { $page->do403(); }

	$kapenta->page->load('modules/schools/actions/listhidden.page.php');
	$kapenta->page->render();


?>
