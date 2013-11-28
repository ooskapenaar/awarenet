<?

//-------------------------------------------------------------------------------------------------
//*	describe a module
//-------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------
	//	check that the module exists
	//---------------------------------------------------------------------------------------------
	if ('' == $kapenta->request->ref) { $page->do404(); }
	if (false == in_array($kapenta->request->ref, $kapenta->listModules())) { $page->do404(); }

	//---------------------------------------------------------------------------------------------
	//	render the page
	//---------------------------------------------------------------------------------------------
	$kapenta->page->load('modules/docgen/actions/describemodule.page.php');
	$kapenta->page->blockArgs['modname'] = $kapenta->request->ref;
	$kapenta->page->render();

?>