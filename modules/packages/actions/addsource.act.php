<?

	require_once($kapenta->installPath . 'modules/packages/inc/kupdatemanager.class.php');

//--------------------------------------------------------------------------------------------------
//*	add a software source (kapenta repository)
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	check POST vars and user role
	//----------------------------------------------------------------------------------------------
	if ('admin' != $kapenta->user->role) { $kapenta->page->do403(); }

	if (false == array_key_exists('action', $_POST)) { $kapenta->page->do404('Action not specified.'); }
	if ('addSource' != $_POST['action']) { $kapenta->page->do404('Action not recognized.'); }
	if (false == array_key_exists('source', $_POST)) { $kapenta->page->do404('Source not given.'); }

	$source = trim($_POST['source']);

	//----------------------------------------------------------------------------------------------
	//	try add the source
	//----------------------------------------------------------------------------------------------
	if ('' != $source) {
		$updateManager = new KUpdateManager();
		$check = $updateManager->addSource($source);

		if (true == $check) {
			$kapenta->session->msg('Added source: ' . $source);
		} else {
			$kapenta->session->msg('Could not add source: ' . $source . ' (unkown error)');
		}

	} else {
		$kapenta->session->msg('No source given.');
	}	

	//----------------------------------------------------------------------------------------------
	//	redirect back to package manager
	//----------------------------------------------------------------------------------------------
	$kapenta->page->do302('packages/');

?>
