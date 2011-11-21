<?

	require_once($kapenta->installPath . 'modules/videos/models/gallery.mod.php');

//--------------------------------------------------------------------------------------------------
//*	add a new gallery
//--------------------------------------------------------------------------------------------------
//TODO: replace with standard generated code

	if (false == $user->authHas('videos', 'videos_gallery', 'new')) { $page->do403(); }

	if (false == array_key_exists('action', $_POST)) { $page->do404('Action not specified.'); }
	if ('createGallery' != $_POST['action']) { $page->do404('Action not supported.'); }

	//----------------------------------------------------------------------------------------------
	//	create a gallery given a title
	//----------------------------------------------------------------------------------------------
	if ((false == array_key_exists('title', $_POST)) || ('' == trim($_POST['title']))) {
		//------------------------------------------------------------------------------------------
		//	invalid title
		//------------------------------------------------------------------------------------------
		$session->msg("Please choose a title for your new gallery.");
		$page->do302('videos/listgalleries/' . $user->alias);
	} 

	//----------------------------------------------------------------------------------------------
	//	create gallery
	//----------------------------------------------------------------------------------------------
	$model = new Videos_Gallery();
	$model->title = $utils->cleanString($_POST['title']);;
	$report = $model->save();

	if ('' != $report) {
		$session->msg('Could not create gallery: ' . $report, 'bad');
		$page->do302('videos/listgalleries/' . $user->alias);
	}

	$session->msg('Gallery created: ' . $model->title, 'ok'	);
	$page->do302('videos/editgallery/' . $model->alias);

?>