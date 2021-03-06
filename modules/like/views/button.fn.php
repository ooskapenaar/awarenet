<?

//--------------------------------------------------------------------------------------------------
//*	make a like button for some object
//--------------------------------------------------------------------------------------------------
//arg: refModule - name of a kapenta module [string]
//arg: refModel - type of object being endorsed [string]
//arg: refUID - UID of object being endorsed [string]

function like_button($args) {
	global $theme;
	global $kapenta;
	global $kapenta;
	global $kapenta;

	$html = '';						//%	return value [string]

	//----------------------------------------------------------------------------------------------
	//	check arguments and user role
	//----------------------------------------------------------------------------------------------
	if (('public' == $kapenta->user->role) || ('banned' == $kapenta->user->role)) { return ''; }

	if (false == array_key_exists('refModule', $args)) { return '(refModule not given)'; }
	if (false == array_key_exists('refModel', $args)) { return '(refModel not given)'; }
	if (false == array_key_exists('refUID', $args)) { return '(refUID not given)'; }

	if (false == $kapenta->moduleExists($args['refModule'])) { return 'Unknown module.'; }
	if (false == $kapenta->db->objectExists($args['refModel'], $args['refUID'])) { return '(unk UID)'; }

	//----------------------------------------------------------------------------------------------
	//	make the block
	//----------------------------------------------------------------------------------------------
	$block = $theme->loadBlock('modules/like/views/button.block.php');
	$html = $theme->replaceLabels($args, $block);

	return $html;
}

?>
