<?

	require_once($kapenta->installPath . 'modules/notifications/models/userindex.mod.php');

//--------------------------------------------------------------------------------------------------
//*	had a notification in a user's stream
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	check reference and ownership
	//----------------------------------------------------------------------------------------------
	if ('' == $kapenta->request->ref) { $kapenta->page->do404('Notification to hide not specified'); }

	$model = new Notifications_UserIndex($kapenta->request->ref);
	if (false == $model->loaded) { $kapenta->page->do404('Notification index not found'); }
	if ('admin' != $kapenta->user->role) {
		if ($model->userUID != $kapenta->user->UID) { $page->do043('Not your notification to hide.'); }
	}

	//----------------------------------------------------------------------------------------------
	//	hide notification and redirect back to user's feed
	//----------------------------------------------------------------------------------------------
	$model->status = 'hide';
	$report = $model->save();
	if ('' == $report) { $kapenta->session->msg('Notification hidden.'); }
	$kapenta->page->do302('notifications/');

?>
