<?

//--------------------------------------------------------------------------------------------------
//*	turn on mobile browsing for this session
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	turn on mobile features
	//----------------------------------------------------------------------------------------------

	$kapenta->session->set('mobile', 'true');
	$kapenta->session->set('contentWidth', '320');

	$kapenta->session->msg("Session now in mobile compatability mode.", 'ok');

	//----------------------------------------------------------------------------------------------
	//	redirect to front page or user notifications
	//----------------------------------------------------------------------------------------------

	if (('public' == $kapenta->user->role) || ('banned' == $kapenta->user->role)) { $kapenta->page->do302('users/login/'); }
	$kapenta->page->do302('notifications/');

?>
