<?

//--------------------------------------------------------------------------------------------------
//*	development/testing action for slideshow
//--------------------------------------------------------------------------------------------------

	if ('admin' != $kapenta->user->role) { $kapenta->page->do403(); }

	$kapenta->page->load('modules/images/actions/test.slideshow.page.php');
	$kapenta->page->render();

?>
