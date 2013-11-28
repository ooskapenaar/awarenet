<?

//--------------------------------------------------------------------------------------------------
//|	form to manually trigger the package update process
//--------------------------------------------------------------------------------------------------

function packages_updatesourcesform($args) {
	global $user;
	global $theme;

	$html = '';				//%	return value [string]

	//----------------------------------------------------------------------------------------------
	//	check user role 
	//----------------------------------------------------------------------------------------------
	if ('admin' != $user->role) { return ''; }

	//----------------------------------------------------------------------------------------------
	//	make the block
	//----------------------------------------------------------------------------------------------
	$html = $theme->loadBlock('modules/packages/views/updatesourcesform.block.php');

	return $html;
}

?>