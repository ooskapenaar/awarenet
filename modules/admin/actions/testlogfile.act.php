<?

	require_once($kapenta->installPath . 'modules/admin/inc/logfile.class.php');

//--------------------------------------------------------------------------------------------------
//*	test of logfile class
//--------------------------------------------------------------------------------------------------

	if ('admin' != $kapenta->user->role) { $kapenta->page->do403(); }

	$parser = new Admin_LogFile('data/log/11-05-05-pageview.log.php');

	

?>
