<?

//--------------------------------------------------------------------------------------------------
//|	lessons submenu
//--------------------------------------------------------------------------------------------------

function lessons_menu($args) { 
	global $theme;
	global $user;

	$html = '';							//%	return value [string]

	//----------------------------------------------------------------------------------------------
	//	make the block
	//----------------------------------------------------------------------------------------------
	$block = $theme->loadBlock('modules/lessons/views/menu.block.php');
	//$labels = array();
	//$html = $theme->replaceLabels($labels, $block);
	$html = $block;
	return $html;
}

//--------------------------------------------------------------------------------------------------

?>
