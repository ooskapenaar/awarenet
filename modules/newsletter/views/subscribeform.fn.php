<?php

//--------------------------------------------------------------------------------------------------
//*	display an email subscription form to public users
//--------------------------------------------------------------------------------------------------

function newsletter_subscribeform($args) {
	global $theme;
	global $kapenta;

	$html = '';								//%	return value [string]

	//----------------------------------------------------------------------------------------------
	//	check user role
	//----------------------------------------------------------------------------------------------
	//if ('public' != $kapenta->user->role) { return ''; }


	//----------------------------------------------------------------------------------------------
	//	make the block
	//----------------------------------------------------------------------------------------------

	$html = $theme->loadBlock('modules/newsletter/views/subscribeform.block.php');

	return $html;

}

?>