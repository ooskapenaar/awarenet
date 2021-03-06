<?
	//require_once($kapenta->installPath . 'modules/notifications/models/notification.mod.php');
	// ^ sometimes needed for breadcrumbs, etc

//--------------------------------------------------------------------------------------------------
//*	show form to edit a Notification object
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	check permissions
	//----------------------------------------------------------------------------------------------
	if ('' == $kapenta->request->ref) { $kapenta->page->do404('Notification not specified'); }
	if ('admin' != $kapenta->user->role) { $kapenta->page->do403('Only admins can use this interface.'); }

	$UID = $kapenta->request->ref;
	if (false == $kapenta->db->objectExists('notifications_notification', $UID)) { $kapenta->page->do404(); }
	if (false == $kapenta->user->authHas('notifications', 'notifications_notification', 'edit', $UID))
		{ $kapenta->page->do403('You are not authorized to edit this Notifications.'); }


	//----------------------------------------------------------------------------------------------
	//	render the page
	//----------------------------------------------------------------------------------------------
	$kapenta->page->load('modules/notifications/actions/edit.page.php');
	$kapenta->page->blockArgs['UID'] = $UID;
	$kapenta->page->blockArgs['notificationUID'] = $UID;
	$kapenta->page->render();

?>
