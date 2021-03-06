<?

//--------------------------------------------------------------------------------------------------
//|	display n most active projects (by number of revisions)
//--------------------------------------------------------------------------------------------------
//opt: num - number of projects to display, default is 10 (int) [string]

function projects_mostactivenav($args) {
		global $kapenta;
		global $theme;
		global $kapenta;
		global $session;
		global $kapenta;

	$html = '';		//%	return value [string]
	$num = 10;

	//----------------------------------------------------------------------------------------------
	//	check arguments and permissions
	//----------------------------------------------------------------------------------------------
	if (true == array_key_exists('num', $args)) { $num = (int)$args['num']; }
	if ($num < 1) { $num = 1; }

	//----------------------------------------------------------------------------------------------
	//	count revisions
	//----------------------------------------------------------------------------------------------

	$monthAgo = $kapenta->datetime($kapenta->time() - 5184000);
	//$kapenta->session->msgAdmin('monthago: ' . $monthAgo);

	$sql = "SELECT projectUID, count(UID) as numRevisions "
		 . "FROM projects_change "
		 . "WHERE projectUID != '' "
		 . "AND (DATE(`createdOn`) > DATE('" . $monthAgo . "')) "
		 . "AND (createdBy <> 'public') "
		 . "GROUP BY projectUID "
		 . "ORDER BY numRevisions DESC LIMIT $num";

	$result = $kapenta->db->query($sql);

	//----------------------------------------------------------------------------------------------
	//	make the block
	//----------------------------------------------------------------------------------------------
	while ($row = $kapenta->db->fetchAssoc($result)) {
		$row = $kapenta->db->rmArray($row);
		$html .= "[[:projects::summarynav::projectUID=" . $row['projectUID'] . ":]]";
	}
	
	return $html;
}

?>
