<?

//--------------------------------------------------------------------------------------------------
//|	make a list of 'hidden' schools
//--------------------------------------------------------------------------------------------------

function schools_listhidden($args) {
		global $kapenta;
		global $kapenta;
		global $theme;

	$html = '';		//%	return value [string]

	//TODO: permission check here

	//----------------------------------------------------------------------------------------------
	//	make the list
	//----------------------------------------------------------------------------------------------
	$conditions = array("hidden='yes'");
	$range = $kapenta->db->loadRange('schools_school', '*', $conditions, 'name');
	
	foreach($range as $row) { $html .= "[[:schools::summary::raUID=" . $row['UID'] . ":]]"; }

	return $html;
}

?>
