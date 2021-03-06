<?

//--------------------------------------------------------------------------------------------------
//|	makes a list of all groups active at a school, formatted for nav
//--------------------------------------------------------------------------------------------------
//opt: schoolUID - UID of a Schools_School object [string]
//opt: school - alias of above [string]

function groups_atschoolnav($args) {
	global $kapenta;
	global $kapenta;

	$schoolUID = $kapenta->user->school;		//%	school to display groups for [string]
	$html = '';						//%	return value [string]

	//----------------------------------------------------------------------------------------------
	//	check arguments and permissions
	//----------------------------------------------------------------------------------------------
	if ('public' == $kapenta->user->role) { return '[[:users::pleaselogin:]]'; }
	if (true == array_key_exists('schoolUID', $args)) { $schoolUID = $args['schoolUID']; }	
	if (true == array_key_exists('school', $args)) { $schoolUID = $args['school']; }	

	//----------------------------------------------------------------------------------------------
	//	query database
	//----------------------------------------------------------------------------------------------
	$conditions = array();
	$conditions[] = "schoolUID='" . $kapenta->db->addMarkup($schoolUID) . "'";

	$range = $kapenta->db->loadRange('groups_schoolindex', '*', $conditions);

	//----------------------------------------------------------------------------------------------
	//	make the block
	//----------------------------------------------------------------------------------------------

	if (0 == count($range)) { 
		$html .= "<div class='inlinequote'>No active groups at present.</div>\n"; 
	}

	foreach($range as $item) {
		$html .= "[[:groups::summarynav::groupUID=" . $item['groupUID'] . ":]]\n";
	}

	return $html;
}


?>
