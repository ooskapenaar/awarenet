<?

//--------------------------------------------------------------------------------------------------
//	default action for projects module (ie, no action specified in URL)
//--------------------------------------------------------------------------------------------------

	if ($request['ref'] == '') {
		// no recordAlias/UID given in URL, list all schools
		include $installPath . 'modules/projects/actions/list.act.php';
	} else {
		// recordAlias/UID given in URL, show single school
		include $installPath . 'modules/projects/actions/show.act.php';
	}

?>
