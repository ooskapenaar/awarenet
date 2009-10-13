<?

	require_once($installPath . 'modules/schools/models/schools.mod.php');

//--------------------------------------------------------------------------------------------------
//	returns whether current user is a teacher
//--------------------------------------------------------------------------------------------------
// * $args['raUID'] = group UID or recordAlias

function schools_haseditauth($args) {
	global $user;
	if ($user->data['ofGroup'] == 'admin') { return 'yes'; }
	if ($user->data['ofGroup'] == 'teacher') { return 'yes'; }
	if (array_key_exists('raUID', $args) == false) { return false; }
	return 'no';
}


?>