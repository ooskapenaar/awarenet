<?

	require_once($installPath . 'modules/groups/models/groups.mod.php');
	require_once($installPath . 'modules/groups/models/membership.mod.php');

//--------------------------------------------------------------------------------------------------
//	return number of members in group
//--------------------------------------------------------------------------------------------------
// * $args['groupUID'] = overrides raUID

function groups_membercount($args) {
	if (authHas('groups', 'show', '') == false) { return false; }
	if (array_key_exists('groupUID', $args)) { $args['raUID'] = $args['groupUID']; }

	$sql = "select count(UID) as memberCount from groupmembers "
		 . "where groupUID='" . $args['groupUID'] . "'";

	$result = dbQuery($sql);
	$row = dbFetchAssoc($result);
	return $row['memberCount'];
}

//--------------------------------------------------------------------------------------------------

?>