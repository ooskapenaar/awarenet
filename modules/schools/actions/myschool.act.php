<?

//--------------------------------------------------------------------------------------------------
//	redirect to user's school
//--------------------------------------------------------------------------------------------------

	$schoolRa = raGetDefault('schools', $user->data['school']);
	if ($schoolRa == false) { do404(); }
	do302('schools/' . $schoolRa);

?>