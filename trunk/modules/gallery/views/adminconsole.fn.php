<?

//--------------------------------------------------------------------------------------------------
//|	list of controls for this module as displayed on the admin console
//--------------------------------------------------------------------------------------------------

function gallery_adminconsole($args) {
	global $theme;
	global $kapenta;

	if ('admin' != $kapenta->user->role) { return ''; }
	$html = $theme->loadBlock('modules/gallery/views/adminconsole.block.php');
	return $html;
}

//--------------------------------------------------------------------------------------------------

?>
