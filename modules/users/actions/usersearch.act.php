<?

//-------------------------------------------------------------------------------------------------
//*	iframe to search users (for adding to mail, projects, friendships, etc)
//-------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------
	//	check that the user is logged in
	//---------------------------------------------------------------------------------------------
	if (($kapenta->user->role == 'public')||($kapenta->user->role == 'banned')) { $kapenta->page->do403(); }

	//---------------------------------------------------------------------------------------------
	//	display search form and any results
	//---------------------------------------------------------------------------------------------
	$query = '';
	if (true == array_key_exists('q', $_POST)) { $query = $_POST['q']; }

	$kapenta->page->load('modules/users/actions/usersearch.if.page.php');
	$kapenta->page->blockArgs['query'] = $query;
	$kapenta->page->render();

?>
