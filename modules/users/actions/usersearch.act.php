<?

//-------------------------------------------------------------------------------------------------
//	iframe to search users (for adding to mail, projects, friendships, etc)
//-------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------
	//	check that the user is logged in
	//---------------------------------------------------------------------------------------------
	if (($user->data['ofGroup'] == 'public')||($user->data['ofGroup'] == 'banned')) { do403(); }

	//---------------------------------------------------------------------------------------------
	//	display search form and any results
	//---------------------------------------------------------------------------------------------
	$query = '';
	if (true == array_key_exists('q', $_POST)) { $query = $_POST['q']; }

	$page->load($installPath . 'modules/users/actions/usersearch.if.page.php');
	$page->blockArgs['query'] = $query;
	$page->render();

?>