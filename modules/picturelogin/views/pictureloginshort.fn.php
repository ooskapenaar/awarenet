<?

//--------------------------------------------------------------------------------------------------
//|	show picturelogin 
//--------------------------------------------------------------------------------------------------

function picturelogin_pictureloginshort($args) {
	global $kapenta;
	global $theme;

	$html = '';						//%	return value [string]
	//----------------------------------------------------------------------------------------------
	//	make and return the form
	//----------------------------------------------------------------------------------------------
	$block = $theme->loadBlock('modules/picturelogin/views/pictureloginshort.block.php');
	$html = $theme->replaceLabels($args, $block);
	return $html;
}

//--------------------------------------------------------------------------------------------------

?>
