<?

	require_once($installPath . 'modules/comments/models/comments.mod.php');

//--------------------------------------------------------------------------------------------------
//	summary
//--------------------------------------------------------------------------------------------------

function comments_summary($args) {
	if (authHas('comments', 'view', '') == false) { return ''; }
	if (array_key_exists('UID', $args)) {
		$model = new Comment($args['UID']);
		$html = replaceLabels($model->extArray(), loadBlock('modules/comments/views/summary.block.php'));
		return $html;
	}
}

//--------------------------------------------------------------------------------------------------

?>