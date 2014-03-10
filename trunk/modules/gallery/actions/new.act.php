<?

	require_once($kapenta->installPath . 'modules/gallery/models/gallery.mod.php');

//--------------------------------------------------------------------------------------------------
//*	add a new (root) gallery
//--------------------------------------------------------------------------------------------------
//TODO: replace with standard generated code

	if (false == $user->authHas('gallery', 'gallery_gallery', 'new')) { $kapenta->page->do403(); }

	if (false == array_key_exists('action', $_POST)) { $kapenta->page->do404('Action not specified.'); }
	if ('createGallery' != $_POST['action']) { $kapenta->page->do404('Action not supported.'); }

	//----------------------------------------------------------------------------------------------
	//	create a gallery given a title
	//----------------------------------------------------------------------------------------------
	if ((false == array_key_exists('title', $_POST)) || ('' == trim($_POST['title']))) {
		//------------------------------------------------------------------------------------------
		//	invalid title
		//------------------------------------------------------------------------------------------
		$session->msg("Please choose a title for your new gallery.");
		$kapenta->page->do302('gallery/list/' . $user->alias);
	} 

	//----------------------------------------------------------------------------------------------
	//	create gallery
	//----------------------------------------------------------------------------------------------
	$model = new Gallery_Gallery();
	$model->title = $utils->cleanString($_POST['title']);;
	$report = $model->save();

	if ('' != $report) {
		$session->msg('Could not create gallery: ' . $report, 'bad');
		$kapenta->page->do302('gallery/list/' . $user->alias);

	}

	$session->msg('Gallery created: ' . $model->title, 'ok'	);
	$kapenta->page->do302('gallery/edit/' . $model->alias);

?>
