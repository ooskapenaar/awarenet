<?

//--------------------------------------------------------------------------------------------------
//	action to search for friends
//--------------------------------------------------------------------------------------------------

	if (authHas('users', 'view', '') == false) { 
		echo "Please log in.<br/>\n";
		die();
	}

	//----------------------------------------------------------------------------------------------
	//	POST var for search?
	//----------------------------------------------------------------------------------------------

	$search = '';
	if (array_key_exists('q', $_POST) == true) {
		$search = clean_string($_POST['q']);
	}

	//----------------------------------------------------------------------------------------------
	//	add var for search?
	//----------------------------------------------------------------------------------------------

	$add = '';
	if (array_key_exists('add', $request['args']) == true) {
		$add = "<br/>[[:users::friendrequestprofilenav::userUID=" 
			 . $request['args']['add'] . "::notitle=yes:]]";
	}

	//----------------------------------------------------------------------------------------------
	//	render the page
	//----------------------------------------------------------------------------------------------

	$page->load($installPath . 'modules/users/actions/find.page.php');
	$page->blockArgs['fsearch'] = $search;
	$page->blockArgs['fadd'] = $add;
	$page->render();

?>