<?

//--------------------------------------------------------------------------------------------------
//|	block to test ajax load 
//--------------------------------------------------------------------------------------------------

function live_testblock($args) {
		global $kapenta;
		global $theme;
		global $kapenta;

	$html = '';

	$block = $theme->loadBlock('modules/live/views/testblock.block.php');

	$labels = array(
		'userLink' => $kapenta->user->getNameLink(),
		'datetime' => $kapenta->db->datetime()
	);

	$html = $theme->replaceLabels($labels, $block);

	return $html;
}

?>
