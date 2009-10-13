<?

	require_once($installPath . 'modules/moblog/models/moblog.mod.php');
	require_once($installPath . 'modules/moblog/models/precache.mod.php');

//--------------------------------------------------------------------------------------------------
//	summarise for the nav (300 wide)
//--------------------------------------------------------------------------------------------------
// * $args['raUID'] = recordAlias or UID or groups entry
// * $args['postUID'] = overrides raUID

function moblog_summarynav($args) {
	if (array_key_exists('postUID', $args) == true) { $args['raUID'] = $args['postUID']; }
	if (array_key_exists('raUID', $args) == false) { return false; }
	$model = new Moblog($args['raUID']);	
	return replaceLabels($model->extArray(), loadBlock('modules/moblog/views/summarynav.block.php'));
}

//--------------------------------------------------------------------------------------------------

?>