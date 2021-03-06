<?
	require_once($kapenta->installPath . 'modules/abuse/models/report.mod.php');

//--------------------------------------------------------------------------------------------------
//*	create a new Report object
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//*	check permissions and any POST variables
	//----------------------------------------------------------------------------------------------
	if ('public' == $kapenta->user->role) { $kapenta->page->do403(); }

	if (false == array_key_exists('refModule', $_POST))
		{ $kapenta->page->do404('reference module not specified'); }
	if (false == array_key_exists('refModel', $_POST))
		{ $kapenta->page->do404('reference model not specified'); }
	if (false == array_key_exists('refUID', $_POST))
		{ $kapenta->page->do404('reference object UID not specified'); }
	if (false == $kapenta->moduleExists($_POST['refModule']))
		{ $kapenta->page->do404('specified module does not exist'); }
	if (false == $kapenta->db->objectExists($_POST['refModel'], $_POST['refUID']))
		{ $kapenta->page->do404('specified owner does not exist in database: ' . $_POST['refModel'] . ' - '. $_POST['refUID']); }

	//----------------------------------------------------------------------------------------------
	//*	create the object
	//----------------------------------------------------------------------------------------------
	$model = new Abuse_Report();
	foreach($_POST as $field => $value) {
		switch(strtolower($field)) {
			case 'refmodule':	$model->refModule = $value; 					break;
			case 'refmodel':	$model->refModel = $value; 						break;
			case 'refuid':		$model->refUID = $value; 						break;
			case 'comment':		$model->comment = $utils->cleanHtml($value); 	break;
			case 'title':		$model->title = $utils->cleanTitle($value); 	break;

			case 'fromurl':		
				$model->fromurl = str_replace($kapenta->serverPath, '', $value); 
				break;

			//case 'notes':		$model->notes = $utils->cleanString($value); break;
			//case 'status':	$model->status = $utils->cleanString($value); break;
		}
	}

	$model->status = 'open';
	$report = $model->save();

	//----------------------------------------------------------------------------------------------
	//*	check that object was created and redirect
	//----------------------------------------------------------------------------------------------
	if ('' == $report) {
		$msg = "Report Submitted.<br/>Thank you for letting us know. fromurl:" . $model->fromurl . "\n";
		$kapenta->session->msg($msg, 'ok');
		$kapenta->page->do302($model->fromurl);
	} else {
		$kapenta->session->msg('Could not create new Report:<br/>' . $report, 'bad');
		$kapenta->page->do302($model->fromurl);
	}

?>
