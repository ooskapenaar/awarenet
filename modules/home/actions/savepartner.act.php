<?

	require_once($kapenta->installPath . 'modules/home/models/partner.mod.php');

//--------------------------------------------------------------------------------------------------
//*	save changes to a Partner object
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	check permissions and POST variables
	//----------------------------------------------------------------------------------------------
	if (false == array_key_exists('action', $_POST)) { $kapenta->page->do404('Action not specified.'); }
	if ('savePartner' != $_POST['action']) { $kapenta->page->do404('Action not supported.'); } 
	if (false == array_key_exists('UID', $_POST)) { $kapenta->page->do404('UID not POSTed.'); }

	$model = new Home_Partner($_POST['UID']);
	if (false == $model->loaded) { $kapenta->page->do404("could not load Partner $UID");}

	if (false == $kapenta->user->authHas('home', 'Home_Partner', 'edit', $model->UID))
		{ $kapenta->page->do403('You are not authorized to edit this Partner.'); }

	//----------------------------------------------------------------------------------------------
	//	load and update the object
	//----------------------------------------------------------------------------------------------
	foreach($_POST as $field => $value) {
		switch(strtolower($field)) {
			case 'title':		$model->title = $utils->cleanString($value); 		break;
			case 'description':	$model->description = $utils->cleanString($value);	break;
			case 'url':			$model->url = $utils->cleanString($value);			break;
			case 'weight':		$model->weight = $utils->cleanString($value);		break;
		}
	}

	$report = $model->save();

	//----------------------------------------------------------------------------------------------
	//	check that object was saved and redirect
	//----------------------------------------------------------------------------------------------
	if ('' == $report) { $kapenta->session->msg('Saved changes to Partner', 'ok'); }
	else { $kapenta->session->msg('Could not save Partner:<br/>' . $report, 'bad'); }

	if (true == array_key_exists('return', $_POST)) { $kapenta->page->do302($_POST['return']); }
	else { $kapenta->page->do302('home/showpartner/' . $model->alias); }

?>
