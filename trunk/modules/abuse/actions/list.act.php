<?

//--------------------------------------------------------------------------------------------------
//*	list abuse reports
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	authentication (only admins can do this for now)
	//----------------------------------------------------------------------------------------------
	if ('admin' != $user->role) { $page->do403(); }

	$pageNo = 1;
	if (true == array_key_exists('page', $request['args'])) { $pageNo = (int)$kapenta->request->args['page']; }

	//----------------------------------------------------------------------------------------------
	//	show the page
	//----------------------------------------------------------------------------------------------
	$kapenta->page->load('modules/abuse/actions/list.page.php');
	$kapenta->page->blockArgs['pageNo'] = $pageNo . '';
	$kapenta->page->render();

?>