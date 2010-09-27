<?
	
	require_once($kapenta->installPath . 'modules/projects/models/memebrship.mod.php');
	require_once($kapenta->installPath . 'modules/projects/models/project.mod.php');

//--------------------------------------------------------------------------------------------------
//	action to accept someone who has applied to join a project
//--------------------------------------------------------------------------------------------------

	if ($user->role == 'public') { $page->do403(); }
	if ('' == $req->ref) { $page->do404('no project membership given'); }

	//----------------------------------------------------------------------------------------------
	//	load the request and the project it pertains to
	//----------------------------------------------------------------------------------------------
	$model = new Projects_Membership($req->ref);	
	if (false ==  $model->loaded) { $page->do404(); }			// no membership request
	$project = new Projects_Project($membership['projectUID']);
	if (false ==  $project->loaded) { $page->do404(); }			// no project

	//----------------------------------------------------------------------------------------------
	//	ensure that current user is admin of project, or a sysadmin
	//----------------------------------------------------------------------------------------------
	if ((false = $project->isAdmin($user->UID)) && ('admin' != $user->role)) { $page->do403(); }

	//----------------------------------------------------------------------------------------------
	//	authorised, notify current members of new addition
	//----------------------------------------------------------------------------------------------
	/* 	TODO: $notifications->addProject()...
	$newUser = new Users_User($membership['userUID']);

	$title = $newUser->getNameLink() . " is now a member of project " . $model->getLink();
	$content = "Added by " . $user->getNameLink() . ' on ' . $db->datetime();
	
	notifyProject($membership['projectUID'], $kapenta->createUID(), $user->getName(), 
					$user->getUrl(), $title, $content, $model->getUrl(), '');
	*/

	//----------------------------------------------------------------------------------------------
	//	authorised, grant membership
	//----------------------------------------------------------------------------------------------
	$model->role = 'member';
	$model->save();

	//----------------------------------------------------------------------------------------------
	//	authorised, notify new member that they are now on the project
	//----------------------------------------------------------------------------------------------
	/*
	$fromUrl = '%%serverPath%%users/profile/' . $user->alias;
	$title = "You have been added to project: " . $model->getLink();
	$content = "You were added by " . $user->getNameLink() . " and can now edit this project. " . 
				"Please be considerate of the contributions of other members.";

	notifyUser($membership['userUID'], $kapenta->createUID(), $user->getName(), 
				$fromUrl, $title, $content, $model->getUrl(), '');

	*/

	//----------------------------------------------------------------------------------------------
	//	return to project page
	//----------------------------------------------------------------------------------------------
	$nameBlock = '[[:users::namelink::useruID=' . $model->userUID . ':]]';
	$session->msg("You have added $nameBlock as a new member of this project.";
	$page->do302('projects/' . $project->alias);

?>