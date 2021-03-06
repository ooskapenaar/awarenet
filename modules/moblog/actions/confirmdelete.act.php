<?

	require_once($kapenta->installPath . 'modules/moblog/models/post.mod.php');

//--------------------------------------------------------------------------------------------------
//*	confirm deletion of a moblog post
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	check permissions and reference
	//----------------------------------------------------------------------------------------------
	if (false == array_key_exists('UID', $kapenta->request->args)) { $kapenta->page->do404('UID not given'); }

	$model = new Moblog_Post($kapenta->request->args['UID']);

	if (false == $kapenta->user->authHas('moblog', 'moblog_post', 'edit', $model->UID))
		{ $kapenta->page->do403('You are not authorized to delete this blog post.'); }
	
	//----------------------------------------------------------------------------------------------
	//	make confirmation form
	//----------------------------------------------------------------------------------------------
	$labels = array('UID' => $model->UID, 'raUID' => $model->alias);
	$block = $theme->loadBlock('modules/moblog/views/confirmdelete.block.php');
	$html = $theme->replaceLabels($labels, $block);
	$kapenta->session->msg($html, 'warn');

	//----------------------------------------------------------------------------------------------
	//	redirect back to post to be deleted
	//----------------------------------------------------------------------------------------------	
	$kapenta->page->do302('moblog/' . $model->alias);

?>
