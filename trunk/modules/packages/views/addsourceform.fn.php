<?

//--------------------------------------------------------------------------------------------------
//|	form for adding software sources to kapenta
//--------------------------------------------------------------------------------------------------

function packages_addsourceform($args) {
	global $user;
	global $theme;

	$html = '';				//%	return value [string]

	//----------------------------------------------------------------------------------------------
	//	check arguments and user role
	//----------------------------------------------------------------------------------------------
	if ('admin' != $user->role) { return ''; }

	//----------------------------------------------------------------------------------------------
	//	make the block
	//----------------------------------------------------------------------------------------------
	$html = $theme->loadBlock('modules/packages/views/addsourceform.block.php');

	return $html;
}

?>