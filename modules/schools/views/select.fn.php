<?

	require_once($installPath . 'modules/schools/models/schools.mod.php');

//--------------------------------------------------------------------------------------------------
//	return a list of schools
//--------------------------------------------------------------------------------------------------
// * $args['varname'] = name of variable
// * $args['default'] = current value

function schools_select($args) {
	$varname = 'school';
	$default = '';
	if (array_key_exists('varname', $args)) { $varname = $args['varname']; }
	if (array_key_exists('default', $args)) { $default = $args['default']; }
	$html = '';

	$sql = "select * from schools order by name";
	$result = dbQuery($sql);
	$html .= "<select name='" . $varname . "'>\n";
	while ($row = dbFetchAssoc($result)) {
		$row = sqlRMArray($row);
		if ($row['UID'] == $default) {
			$html .= "<option value='" . $row['UID'] . "' selected='YES'>" 
			      . $row['name'] . "</option>\n";
		} else {
			$html .= "<option value='" . $row['UID'] . "'>" . $row['name'] . "</option>\n";
		}
	}
	$html .= "</select>\n";
	return $html;
}

//--------------------------------------------------------------------------------------------------

?>