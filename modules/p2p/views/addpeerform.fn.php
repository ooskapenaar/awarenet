<?

//--------------------------------------------------------------------------------------------------
//|	form to add a new peer server (formatted for nav)
//--------------------------------------------------------------------------------------------------

function p2p_addpeerform($args) {
		global $theme;
		global $kapenta;

	if ('admin' != $kapenta->user->role) { return ''; }
	$block = $theme->loadBlock('modules/p2p/views/addpeerform.block.php');
	$block = $theme->ntb($block, 'New Peer Server', 'divNewServerForm', 'hide');
	return $block;
}



?>
