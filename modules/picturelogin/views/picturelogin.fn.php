<?

//--------------------------------------------------------------------------------------------------
//|	show picturelogin 
//--------------------------------------------------------------------------------------------------

function picturelogin_picturelogin($args) {
	global $kapenta;
	global $theme;

	$html = '';						//%	return value [string]
	//----------------------------------------------------------------------------------------------
	//	make and return the form
	//----------------------------------------------------------------------------------------------
	$block = $theme->loadBlock('modules/picturelogin/views/picturelogin.block.php');
	$html = $theme->replaceLabels($args, $block);
	return $html;
}

//--------------------------------------------------------------------------------------------------

?>
