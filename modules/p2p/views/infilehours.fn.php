<?

//--------------------------------------------------------------------------------------------------
//*	displays whether this peer is currently set to transfer files
//--------------------------------------------------------------------------------------------------

function p2p_infilehours($args) {
	global $kapenta;
	global $kapenta;
	$html = '';						//%	return value [string]

	//----------------------------------------------------------------------------------------------
	//	check permissions
	//----------------------------------------------------------------------------------------------
	// no restrictions on this information at present

	//----------------------------------------------------------------------------------------------
	//	look up file hours and check if we're in them
	//----------------------------------------------------------------------------------------------
	$inHours = false;			//%	return value [bool]
	$fileHours = $kapenta->registry->get('p2p.filehours');
	$hours = explode(",", $fileHours);
	$current = (int)date('G', $kapenta->time());

	foreach($hours as $hour) {
		if ($current == (int)$hour) { $inHours = true; }
	}

	//----------------------------------------------------------------------------------------------
	//	make the block
	//----------------------------------------------------------------------------------------------
	if (true == $inHours) {
		$html .= "<span class='ajaxmsg'>Yes</span>";
	} else {
		$html .= "<span class='ajaxwarn'>No</span>";
	}

	//----------------------------------------------------------------------------------------------
	//	done
	//----------------------------------------------------------------------------------------------
	return $html;
}

?>
