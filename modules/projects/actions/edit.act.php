<?

//--------------------------------------------------------------------------------------------------
//*	edit a project abstract (since moved to editabstract.act.php) 	//TODO: remove this if possible
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	check reference and permissions
	//----------------------------------------------------------------------------------------------
	if ('' == $kapenta->request->ref) { $kapenta->page->do404(); }
	$UID = $aliases->findRedirect('projects_project');
	if (false == $kapenta->user->authHas('projects', 'projects_project', 'edit', $UID)) 
		{ $kapenta->page->do403('You are not authorized to edit this project.'); }

	//----------------------------------------------------------------------------------------------
	//	check reference and permissions
	//----------------------------------------------------------------------------------------------
	$kapenta->page->do302('projects/editabstract/' . $kapenta->request->ref);

?>
