<?

//-------------------------------------------------------------------------------------------------
//*	delete everything in the sync queue (all outgoing messages)
//-------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------
	//	auth
	//---------------------------------------------------------------------------------------------
	if ('admin' != $user->role) { $page->do403('Only admins may dop this.'); }

	//---------------------------------------------------------------------------------------------
	//	confirmation?
	//---------------------------------------------------------------------------------------------

	if (true == array_key_exists('confirm', $req->args)) {
		//-----------------------------------------------------------------------------------------
		// delete all notices
		//-----------------------------------------------------------------------------------------
		$sql = "delete from Sync_Notice";
		$db->query($sql);

		$msg = "Sync Queue cleared.  Please perform a manual sync with upstream "
			 . "and downstream hosts to bring all records up to date.<br/>\n";

		$session->msg($msg, 'ok');
		$page->do302('sync/');

	} else {
		//-----------------------------------------------------------------------------------------
		// redirect to sync queue
		//-----------------------------------------------------------------------------------------
		$block = $theme->loadBlock('modules/sync/views/clearqueueconfirmform.block.php');
		$session->msg($block, 'warn');
		$page->do302('sync/showqueue/');

	}

?>
