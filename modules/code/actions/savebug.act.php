<?

	require_once($kapenta->installPath . 'modules/code/models/bug.mod.php');

//--------------------------------------------------------------------------------------------------
//*	save changes to a Bug object
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	check permissions and POST variables
	//----------------------------------------------------------------------------------------------
	if (false == array_key_exists('action', $_POST)) { $kapenta->page->do404('Action not specified.'); }
	if ('saveBug' != $_POST['action']) { $kapenta->page->do404('Action not supported.'); } 
	if (false == array_key_exists('UID', $_POST)) { $kapenta->page->do404('UID not POSTed.'); }

	$model = new Code_Bug($_POST['UID']);
	if (false == $model->loaded) { $kapenta->page->do404("could not load Bug $UID");}

	if (false == $kapenta->user->authHas('code', 'Code_Bug', 'edit', $model->UID))
		{ $kapenta->page->do403('You are not authorized to edit this Bug.'); }

	//----------------------------------------------------------------------------------------------
	//	load and update the object
	//----------------------------------------------------------------------------------------------
	foreach($_POST as $field => $value) {
		switch(strtolower($field)) {
			case 'package':		$model->package = $utils->cleanString($value);		break;
			case 'membertype':	$model->memberType = $utils->cleanString($value);	break;
			case 'guestname':	$model->guestName = $utils->cleanString($value);	break;
			case 'guestemail':	$model->guestEmail = $utils->cleanString($value);	break;
			case 'title':		$model->title = $utils->cleanString($value);		break;
			case 'description':	$model->description = $utils->cleanString($value);	break;
			case 'status':		$model->status = $utils->cleanString($value);		break;
		}
	}
	$report = $model->save();

	//----------------------------------------------------------------------------------------------
	//	check that object was saved and redirect
	//----------------------------------------------------------------------------------------------
	if ('' == $report) { $kapenta->session->msg('Saved changes to Bug', 'ok'); }
	else { $kapenta->session->msg('Could not save Bug:<br/>' . $report, 'bad'); }

	if (true == array_key_exists('return', $_POST)) { $kapenta->page->do302($_POST['return']); }
	else { $kapenta->page->do302('code/showbug/' . $model->alias); }

?>
