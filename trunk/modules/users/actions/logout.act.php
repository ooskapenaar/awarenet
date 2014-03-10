<?

//--------------------------------------------------------------------------------------------------
//*	log the user out and redirect to the homepage
//--------------------------------------------------------------------------------------------------

	$kapenta->session->set('recover', 'no');

	if ('public' == $session->user) {
		//------------------------------------------------------------------------------------------
		//	user was not logged in
		//------------------------------------------------------------------------------------------
		$session->msg("You are already logged out.<br/>\n");
		$kapenta->page->do302(''); // homepage		

	} else {
		//------------------------------------------------------------------------------------------
		//	log them out
		//------------------------------------------------------------------------------------------
		$args = array('userUID' => $session->user);

		if ('yes' == $session->get('recover')) { $session->set('recover', 'no'); }
		$check = $session->logout();

		if (true == $check) { $session->msg("You are now logged out.<br/>\n", 'ok'); }
		else { $session->msg('Logout incomplete.', 'bad'); }

		$kapenta->raiseEvent('*', 'users_logout', $args);
		$kapenta->page->do302('');

	}

?>
