<?

//--------------------------------------------------------------------------------------------------
//*	users module API (DEPRECATED)
//--------------------------------------------------------------------------------------------------
//+	Used by firefox plugin. These APIs should be systematized or removed.

//--------------------------------------------------------------------------------------------------
//	display current user
//--------------------------------------------------------------------------------------------------

if ($req->ref == 'current') {
	$ary = array(	'uid' => $user->UID,
					'username' => $user->username,
					'ofgroup' => $user->role,  
					'firstname' => $user->firstname,  
					'surname' => $user->surname );

	echo "<?xml version=\"1.0\"?>\n";
	echo arrayToXml2d($ary, 'user');
}

?>