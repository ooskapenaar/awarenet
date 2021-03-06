<?php

//--------------------------------------------------------------------------------------------------
//|	display comments policy if not dismissed by user
//--------------------------------------------------------------------------------------------------

function comments_tip_policy($args) {
	global $kapenta;
	global $theme;

	$html = '';				//%	return value [string]

	//----------------------------------------------------------------------------------------------
	//	check if current user has dismissed this policy
	//----------------------------------------------------------------------------------------------
	if ('hide' == $kapenta->user->get('info.comments.policy')) { return ''; }

	//----------------------------------------------------------------------------------------------
	//	make the block
	//----------------------------------------------------------------------------------------------
	$html = $theme->loadBlock('modules/comments/views/tip_policy.block.php');

	return $html;
}

?>
