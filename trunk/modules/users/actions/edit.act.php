<?

//--------------------------------------------------------------------------------------------------
//*	edit a user record
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	authentication
	//----------------------------------------------------------------------------------------------
	if ('admin' != $user->role) { $page->do403(); }

	//----------------------------------------------------------------------------------------------
	//	render the page
	//----------------------------------------------------------------------------------------------
	$kapenta->page->load('modules/users/actions/edit.page.php');
	$kapenta->page->blockArgs['raUID'] = $kapenta->request->ref;
	$kapenta->page->blockArgs['userUID'] = $aliases->getOwner('users', 'users_user', $kapenta->request->ref);
	$kapenta->page->render();

?>