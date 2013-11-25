<?

	require_once($kapenta->installPath . 'modules/newsletter/models/edition.mod.php');

//--------------------------------------------------------------------------------------------------
//*	create a new Edition object
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	check permissions and any POST variables
	//----------------------------------------------------------------------------------------------
	if (false == $user->authHas('newsletter', 'newsletter_edition', 'new')) {
		$page->do403('You are not authorized to create new Editions.');
	}


	//----------------------------------------------------------------------------------------------
	//	create the object
	//----------------------------------------------------------------------------------------------
	$model = new Newsletter_Edition();

	foreach($_POST as $key => $value) {
		switch($key) {
			case 'subject':		$model->subject = $value;		break;
			case 'status':		$model->status = $value;		break;
			case 'publishdate':		$model->publishdate = $value;		break;
			case 'sentto':		$model->sentto = $value;		break;
			case 'abstract':		$model->abstract = $value;		break;
			case 'shared':		$model->shared = $value;		break;
			case 'alias':		$model->alias = $value;		break;
		}
	}

	$report = $model->save();

	//----------------------------------------------------------------------------------------------
	//	check that object was created and redirect
	//----------------------------------------------------------------------------------------------
	if ('' == $report) {
		$session->msg('Created new Edition<br/>', 'ok');
		$page->do302('/newsletter/editedition/' . $model->alias);
	} else {
		$session->msg('Could not create new Edition:<br/>' . $report);
		$page->do302('/newsletter/');
	}

?>
