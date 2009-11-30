<?

//--------------------------------------------------------------------------------------------------
//		 _                          _                                _    
//		| | ____ _ _ __   ___ _ __ | |_ __ _   ___  _ __ __ _  _   _| | __
//		| |/ / _` | '_ \ / _ \ '_ \| __/ _` | / _ \| '__/ _` || | | | |/ /
//		|   < (_| | |_) |  __/ | | | || (_| || (_) | | | (_| || |_| |   < 
//		|_|\_\__,_| .__/ \___|_| |_|\__\__,_(_)___/|_|  \__, (_)__,_|_|\_\
//		          |_|                                   |___/     
//                                                                           	Version 2.0 Beta
//--------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------
//	include the kapenta core functions (database access, templating system, etc)
//--------------------------------------------------------------------------------------------------

	include 'setup.inc.php'; // system settings
	include $installPath . 'modules/users/models/users.mod.php';
	include $installPath . 'modules/pages/models/pages.mod.php';
	include $installPath . 'core/core.inc.php';

//--------------------------------------------------------------------------------------------------
//	important variables
//--------------------------------------------------------------------------------------------------

	$page = new Page();
	$request = getRequestParams();	// details of request made by browser
	$ref = $request['ref'];			// record or other item being referred to

//--------------------------------------------------------------------------------------------------
//	load the current user (public if not logged in)
//--------------------------------------------------------------------------------------------------

	$user = new Users($_SESSION['sUserUID']);
	$userlogin = new UserLogin();
	if ($user->data['ofGroup'] != 'public') {
		$exists = $userlogin->load($_SESSION['sUserUID']);
		if (false == $exists) {
			$userlogin->data['userUID'] = $_SESSION['sUserUID'];
			$userlogin->save();
		} else {
			$userlogin->updateLastSeen();
		}
	}

//--------------------------------------------------------------------------------------------------
//	load the action requested by the user
//--------------------------------------------------------------------------------------------------
	include $installPath.'modules/'.$request['module'].'/actions/'.$request['action'].'.act.php';

?>
