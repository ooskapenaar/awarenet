<?

//--------------------------------------------------------------------------------------------------
//*	list of temporary tables
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	check post vars and user role
	//----------------------------------------------------------------------------------------------
	if ('admin' != $kapenta->user->role) { $kapenta->page->do403('Admins only.'); }

	//----------------------------------------------------------------------------------------------
	//	handle POSTs
	//----------------------------------------------------------------------------------------------

	if ((true == array_key_exists('action', $_POST)) && ('delTmp' == $_POST['action'])) {
		$tableName = '';
		if (true == array_key_exists('table', $_POST)) { $tableName = $_POST['table']; }
		if ('tmp_' != substr($tableName, 0, 4)) { $tableName = ''; }	// ONLY tmp tables

		if (('' != $tableName) && (true == $kapenta->db->tableExists($tableName))) {
			$sql = "drop table $tableName";
			$check = $kapenta->db->query($sql);
			if (false == $check) { $kapenta->session->msg("Could not delete $tableName", 'bad'); }
			else { $kapenta->session->msg("Removed: $tableName", 'ok'); }
		}
	}

	//----------------------------------------------------------------------------------------------
	//	show the page
	//----------------------------------------------------------------------------------------------
	$kapenta->page->load('modules/admin/actions/temptables.page.php');
	$kapenta->page->render();

?>
