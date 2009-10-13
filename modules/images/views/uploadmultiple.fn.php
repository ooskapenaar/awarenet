<?

	require_once($installPath . 'modules/images/models/image.mod.php');

//--------------------------------------------------------------------------------------------------
//	iframe to upload multiple images
//--------------------------------------------------------------------------------------------------
// * $args['refModule'] = module to list on
// * $args['refUID'] = number of images per page
// * $args['categories'] = comma delimited list of categories these pictures can belong to

function images_uploadmultiple($args) {
	global $serverPath; 
	
	//----------------------------------------------------------------------------------------------
	//	input validation
	//----------------------------------------------------------------------------------------------
	if (array_key_exists('refModule', $args) == false) { return '(no module)'; }
	if (array_key_exists('refUID', $args) == false) { return '(no UID)'; }
	$categories = '';
	
	//----------------------------------------------------------------------------------------------
	//	check user is authorised
	//----------------------------------------------------------------------------------------------
	if (authHas($args['refModule'], 'imageupload', '') == false) { return ''; }
	
	//----------------------------------------------------------------------------------------------
	//	make the iframe
	//----------------------------------------------------------------------------------------------
	$srcUrl = $serverPath . 'images/uploadmultiple/refModule_' . $args['refModule'] 
		. '/refUID_' . $args['refUID'] . '/';
		
	if (array_key_exists('categories', $args)) { $srcUrl .= '/cats_' . $args['categories']; }
	$html = "<iframe src='" . $srcUrl . "' name='imgMul" . $args['refModule'] . "'" 
		. " width='570' height='200' frameborder='0'></iframe>";
		
	return $html;
}

//--------------------------------------------------------------------------------------------------

?>