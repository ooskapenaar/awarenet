<?

//--------------------------------------------------------------------------------------------------
//*	displays a list of users currently logged in to awareNet
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	admins only
	//----------------------------------------------------------------------------------------------
	if ('admin' != $kapenta->user->role) { $kapenta->page->do403(); }

	//----------------------------------------------------------------------------------------------
	//	render the page
	//----------------------------------------------------------------------------------------------
	$kapenta->page->load('modules/users/actions/activesessions.page.php');
	$kapenta->page->render();

?>
