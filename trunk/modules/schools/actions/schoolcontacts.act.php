<?

//--------------------------------------------------------------------------------------------------
//*	tables of school and teacher contact details
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	admins and teachers only //TODO: make a contacdetails permission
	//----------------------------------------------------------------------------------------------
	if (('admin' != $user->role) && ('teacher' != $user->role)) { $page->do403(); }

	//----------------------------------------------------------------------------------------------
	//	load the page
	//----------------------------------------------------------------------------------------------

	$kapenta->page->load('modules/schools/actions/schoolcontacts.page.php');
	$kapenta->page->render();

?>
