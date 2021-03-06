<?

	require_once($kapenta->installPath . 'modules/polls/models/question.mod.php');

//--------------------------------------------------------------------------------------------------
//*	create a new Polls_Question object
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	check permissions and any POST variables
	//----------------------------------------------------------------------------------------------
	if (false == $kapenta->user->authHas('polls', 'polls_question', 'new')) {
		$kapenta->page->do403('You are not authorized to create new Questions.');
	}

	if (false == array_key_exists('refModule', $_POST))
		{ $kapenta->page->do404('reference module not specified', true); }
	if (false == array_key_exists('refModel', $_POST))
		{ $kapenta->page->do404('reference model not specified', true); }
	if (false == array_key_exists('refUID', $_POST))
		{ $kapenta->page->do404('reference object UID not specified', true); }
	if (false == $kapenta->moduleExists($_POST['refModule']))
		{ $kapenta->page->do404('specified module does not exist', true); }
	if (false == $kapenta->db->objectExists($_POST['refModel'], $_POST['refUID']))
		{ $kapenta->page->do404('specified owner does not exist in database', true); }

	//----------------------------------------------------------------------------------------------
	//	create the object
	//----------------------------------------------------------------------------------------------
	$model = new Polls_Question();

	foreach($_POST as $key => $value) {
		switch($key) {
			case 'refModule':	$model->refModule = $value;		break;
			case 'refModel':	$model->refModel = $value;		break;
			case 'refUID':		$model->refUID = $value;		break;
			case 'content':		$model->content = $value;		break;
		}
	}

	$report = $model->save();

	//----------------------------------------------------------------------------------------------
	//	check that object was created and redirect
	//----------------------------------------------------------------------------------------------
	if ('' == $report) {
		$kapenta->session->msg('Created new Question<br/>', 'ok');
		$url = 'polls/editquestion'
			. '/refModule_' . $model->refModule
			. '/refModel_' . $model->refModel
			. '/refUID_' . $model->refUID;

		$kapenta->page->do302($url);
	} else {
		$kapenta->session->msg('Could not create new Question:<br/>' . $report);
		$kapenta->page->do302('/polls/');
	}

?>
