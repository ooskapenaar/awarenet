<?php

	require_once($kapenta->installPath . 'modules/images/inc/cron.inc.php');

//-------------------------------------------------------------------------------------------------
//*	test / development action to test daily image cron
//-------------------------------------------------------------------------------------------------

	if ('admin' != $kapenta->user->role) { $kapenta->page->do403(); }

	$report = images_cron_daily();
	echo $report;

?>
