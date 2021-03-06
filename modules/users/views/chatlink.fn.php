<?

//-------------------------------------------------------------------------------------------------
//|	makes link for chatting with this user		//TODO: fix this up
//-------------------------------------------------------------------------------------------------
//arg: userUID - UID of the user whose login status we're cheking [string]

function users_chatlink($args) {
	global $kapenta;
	global $kapenta;

	$html = '';									//%	return value [string]

	//----------------------------------------------------------------------------------------------
	//	check arguments and permissions
	//----------------------------------------------------------------------------------------------
	if (false == array_key_exists('userUID', $args)) { return ''; }
	if ('public' == $kapenta->user->role) { return ''; }		// do not disclose online status to public

	//----------------------------------------------------------------------------------------------
	//	query database
	//----------------------------------------------------------------------------------------------
	$conditions = array();
	$conditions[] = "status='active'";
	$conditions[] = "createdBy='" . $kapenta->db->addMarkup($args['userUID']) . "'";
	$range = $kapenta->db->loadRange('users_session', '*', $conditions);

	//$sql = "select * from users_login where userUID='" . $kapenta->db->addMarkup($args['userUID']) . "'";

	//----------------------------------------------------------------------------------------------
	//	make the block
	//----------------------------------------------------------------------------------------------
	if (0 == count($range)) {
		$html .= '[offline]';		
	} else {
		$html .= ''
		 . "<a href='#' "
		 . "onClick=\"kchatclient.createDiscussion('" . $args['userUID'] . "');\""
		 . ">[chat]</a>";
	}

	return $html;
}

?>
