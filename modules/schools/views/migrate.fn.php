<?

//--------------------------------------------------------------------------------------------------
//|	show a form for moving users from one school to another
//--------------------------------------------------------------------------------------------------

function schools_migrate($args) {
	global $db;

	$html = '';							//%	return value [string]

	//----------------------------------------------------------------------------------------------
	//	check arguments and user role
	//----------------------------------------------------------------------------------------------
	if ('admin' != $kapenta->user->role) { $kapenta->page->do403(); }	

	// ^ add any arguments here

	//----------------------------------------------------------------------------------------------
	//	make the block
	//----------------------------------------------------------------------------------------------
	$html = $kapenta->theme->loadBlock('modules/schools/views/migrate.block.php');
	return $html;
}

?>
