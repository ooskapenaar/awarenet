<?

//--------------------------------------------------------------------------------------------------
//*	stub installer, package manager does not have a database footprint
//--------------------------------------------------------------------------------------------------

function packages_install_module() {
	global $kapenta;

	if (false == $kapenta->fs->exists('data/packages/')) { 
		$kapenta->fs->put('data/packages/test.php', "<?php die('ok') ?>");
	}
}

//--------------------------------------------------------------------------------------------------
//|	discover if this module is installed
//--------------------------------------------------------------------------------------------------
//:	if installed correctly report will contain HTML comment <!-- installed correctly -->
//returns: HTML installation status report [string]

function packages_install_status_report() {
	global $kapenta;
	global $kapenta;

	if ('admin' != $kapenta->user->role) { return false; }

	$report = '';
	$installed = true;


	if (false == $kapenta->fs->exists('data/packages/')) {
		$report .= "Packages folder not present.<br/>";
		$installed = false;
	}

	$report .= "This module does not use the database at present.";

	//----------------------------------------------------------------------------------------------
	//	done
	//----------------------------------------------------------------------------------------------
	if (true == $installed) { $report .= '<!-- module installed correctly -->'; }
	return $report;
}

?>
