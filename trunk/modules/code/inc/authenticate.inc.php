<?

//--------------------------------------------------------------------------------------------------
//*	utility file for authenticating repository users
//--------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------
//|	check user credentials
//--------------------------------------------------------------------------------------------------
//arg: username - name of a kapenta user [string]
//arg: password - name of a kapenta user [string]
//arg: packageUID - UID of a Code_Package object [string]
//opt: mode - placeholder for planned functionlity [string]
//returns: true on success, false on failure [bool]

function code_authenticate($username, $password, $packageUID, $privilege, $mode = 'basic') {
	global $db;
	$auth = false;				//%	return value [bool]

	//----------------------------------------------------------------------------------------------
	//	query database
	//----------------------------------------------------------------------------------------------
	$conditions = array("username='" . $db->addMarkup($username) . "'");
	$range = $db->loadRange('users_user', '*', $conditions);
	// ^ "select * from Users_User where username='" . $username . "'";

	if (count($range) > 0) {
		$row = array_shift($range);									//%	first row [array]
		if ($row['password'] == sha1($password . $row['UID'])) {
			// username and password match
			$conditions = array();
			$conditions[] = "userUID='" . $db->addMarkup($row['UID']) . "'";
			$conditions[] = "packageUID='" . $db->addMarkup($packageUID) . "'";
			$conditions[] = "privilege='" . $db->addMarkup($privilege) . "'";

			$range = $db->loadRange('code_userindex', '*', $conditions);

			foreach($range as $item) { $auth = true; }
		}
	} 

	return $auth;
}



?>
