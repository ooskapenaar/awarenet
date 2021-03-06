<?

	require_once($kapenta->installPath . 'modules/groups/models/group.mod.php');

//--------------------------------------------------------------------------------------------------
//*	save changes to a Groups_Group object
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	check permissions and POST variables
	//----------------------------------------------------------------------------------------------
	if (false == array_key_exists('action', $_POST)) { $kapenta->page->do404('action not specified'); }
	if ('saveRecord' != $_POST['action']) { $kapenta->page->do404('action not supported'); } 
	if (false == array_key_exists('UID', $_POST)) { $kapenta->page->do404('UID not POSTed'); }

	$UID = $_POST['UID'];

	if (false == $kapenta->user->authHas('groups', 'groups_group', 'edit', $UID))
		{ $kapenta->page->do403('You are not authorized to edit this Group.'); }

	//----------------------------------------------------------------------------------------------
	//	load and update the object
	//----------------------------------------------------------------------------------------------
	$model = new Groups_Group($UID);
	if (false == $model->loaded) { $kapenta->page->do404("Group not found.");}
	//TODO: sanitize description
	foreach($_POST as $field => $value) {
		switch(strtolower($field)) {
			case 'school':			$model->school = $utils->cleanString($value); 		break;
			case 'name':			$model->name = $utils->cleanTitle($value); 			break;
			case 'type':			$model->type = $utils->cleanTitle($value);	 		break;
			case 'description':		$model->description = $utils->cleanHtml($value); 	break;
		}
	}

	$model->members->deleteDuplicates();			//	remove any duplicate memberships

	$report = $model->save();

	//----------------------------------------------------------------------------------------------
	//	check that object was saved and redirect
	//----------------------------------------------------------------------------------------------
	if ('' == $report) { $kapenta->session->msg('Group updated.'); }
	else { $kapenta->session->msg('Could not save Group:<br/>' . $report); }

	if (true == array_key_exists('return', $_POST)) { $kapenta->page->do302($_POST['return']); }
	else { $kapenta->page->do302('/groups/show/' . $model->alias); }

?>
