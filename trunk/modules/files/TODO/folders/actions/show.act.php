<?

	require_once($kapenta->installPath . 'modules/folders/models/folder.mod.php');

//--------------------------------------------------------------------------------------------------
//*	display a folder
//--------------------------------------------------------------------------------------------------
	
	//----------------------------------------------------------------------------------------------
	//	check permissions and reference
	//----------------------------------------------------------------------------------------------
	if ('' == $kapenta->request->ref) { $kapenta->page->do404(); }							// check ref
	$UID = $aliases->findRedirect('files_folder'); 						// check correct ref

	$model = new folder($kapenta->request->ref);	
	if (false == $model->loaded) { $kapenta->page->do404('no such folder'); }
	if (false == $kapenta->user->authHas('files', 'files_folder', 'show', $model->UID)) { $kapenta->page->do403(); }		

	//----------------------------------------------------------------------------------------------
	//	render the page
	//----------------------------------------------------------------------------------------------
	$kapenta->page->load('modules/folders/actions/show.page.php');
	$kapenta->page->blockArgs['UID'] = $UID;
	$kapenta->page->blockArgs['raUID'] = $model->alias;
	$kapenta->page->blockArgs['userUID'] = $model->createdBy;
	$kapenta->page->render();

?>