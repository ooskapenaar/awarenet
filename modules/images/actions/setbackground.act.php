<?

	require_once($kapenta->installPath . 'modules/images/models/image.mod.php');

//--------------------------------------------------------------------------------------------------
//*	set an image to be the current user's theme background image
//--------------------------------------------------------------------------------------------------
//ref: UID or alias of an Images_Image object [string]

	//----------------------------------------------------------------------------------------------
	//	check user role and reference
	//----------------------------------------------------------------------------------------------
	if (('public' == $kapenta->user->role) || ('banned' == $kapenta->user->role)) { $kapenta->page->do403('Please log in.'); }

	if ('' == $kapenta->request->ref) { $kapenta->page->do404('Image not specified.'); }
	
	$model = new Images_Image($kapenta->request->ref);
	if (false == $model->loaded) { $kapenta->page->do404('Image not found.'); }
	
	$kapenta->user->set('ut.i.background', 'images/full/' . $model->alias);

	//----------------------------------------------------------------------------------------------
	//	redirect to theme settings
	//----------------------------------------------------------------------------------------------
	$kapenta->page->do302('users/myaccount/');

?>
