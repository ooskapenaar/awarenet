<?

	require_once($kapenta->installPath . 'modules/calendar/models/entry.mod.php');

//--------------------------------------------------------------------------------------------------
//*	delete a Calendar_Entry object
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	check POST vars and permissions
	//----------------------------------------------------------------------------------------------
	if (false == array_key_exists('action', $_POST)) { $kapenta->page->do404('Action not specified.'); }
	if ('deleteRecord' != $_POST['action']) { $kapenta->page->do404('Action not supported.'); }
	if (false == array_key_exists('UID', $_POST)) { $kapenta->page->do404('Entry not specified (UID)'); }
	  
	$model = new Calendar_Entry($_POST['UID']);
	if (false == $kapenta->user->authHas('calendar', 'calendar_entry', 'delete', $model->UID))
		{ $kapenta->page->do403(); }	  

	//----------------------------------------------------------------------------------------------
	//	delete the entry and redirect back to the calendar front page
	//----------------------------------------------------------------------------------------------	
	$model->delete();
	$kapenta->session->msg('Deleted calendar entry: ' . $model->title, 'ok');
	$kapenta->page->do302('calendar/');

?>
