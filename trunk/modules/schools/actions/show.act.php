<?

//--------------------------------------------------------------------------------------------------
//	view a school record
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	authentication (public users no longer banned)
	//----------------------------------------------------------------------------------------------
	//if (($user->data['ofGroup'] == 'public') || ($user->data['ofGroup'] == 'banned')) { do403(); }
	
	//----------------------------------------------------------------------------------------------
	//	check reference
	//----------------------------------------------------------------------------------------------
	if ($request['ref'] == '') { do404(); }
	raFindRedirect('schools', 'show', 'schools', $request['ref']);
	require_once($installPath . 'modules/schools/models/school.mod.php');

	//----------------------------------------------------------------------------------------------
	//	load model
	//----------------------------------------------------------------------------------------------
	$model = new School($request['ref']);

	//----------------------------------------------------------------------------------------------
	//	render page
	//----------------------------------------------------------------------------------------------
	$page->load($installPath . 'modules/schools/actions/show.page.php');
	$page->blockArgs['raUID'] = $request['ref'];
	$page->blockArgs['UID'] = $model->data['UID'];
	$page->blockArgs['schoolName'] = $model->data['name'];
	$page->blockArgs['schoolRa'] = $model->data['recordAlias'];
	$page->render();

?>
