<?

	require_once($kapenta->installPath . 'modules/moblog/models/post.mod.php');

//--------------------------------------------------------------------------------------------------
//*	save a blog post
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	check POST vars and permissions
	//----------------------------------------------------------------------------------------------

	if (false == array_key_exists('action', $_POST)) { $page->do404('Action not specified.'); }
	//TODO: pick an action and implement it
	if ('saveRecord' != $_POST['action']) { $page->do404('Action not supported.'); }
	if (false == array_key_exists('UID', $_POST)) { $page->do404('UID not given.'); }

	$model = new Moblog_Post($_POST['UID']);
	if (false == $model->loaded) { $page->do404(); }
	if (false == $user->authHas('moblog', 'moblog_post', 'edit', $model->UID))
		{ $page->do403('You cannot edit this blog post.'); }

	//----------------------------------------------------------------------------------------------
	//	update the record	//TODO: use a switch here
	//----------------------------------------------------------------------------------------------
	
	foreach($_POST as $key => $value) {
		switch($key) {
			case 'title':		$model->title = $utils->cleanTitle($value);			break;
			case 'content':		$model->content = $utils->cleanHtml($value);		break;
			case 'published':	$model->published = $utils->cleanYesNo($value);		break;
		}
	}

	$report = $model->save();
	if ('' != $report) { $session->msg('Could not have blog post: ' . $report, 'bad'); }
	$session->msg('Blog post updated: ' . $model->title, 'ok');

	//----------------------------------------------------------------------------------------------
	//	notify friends, classmates and admins
	//----------------------------------------------------------------------------------------------
	//DEPRECATED: there is a separate notification on the object_updated event for ths module.
	/*
	if ('yes' == $model->published) {

		$recent = $notifications->existsRecent(
			'moblog', 'moblog_post', $model->UID,
			$user->UID, 'moblog_save', (60*60)
		);

		if ('' != $recent) {
			//--------------------------------------------------------------------------------------
			//	this blog post was recently updated, just update the existing notice
			//--------------------------------------------------------------------------------------
			$annotation = '<small>Revised ' . $kapenta->datetime() . '<br/></small>';
			$notifications->annotate($recent, $annotation);
			$session->msgAdmin('Revisin existing notification: ' . $recent, 'ok');

		} else {
			//--------------------------------------------------------------------------------------
			//	no recent notice about this being saved, create a new notificaion and add people
			//--------------------------------------------------------------------------------------
			$ext = $model->extArray();
			$title = $user->getName() . ' has updated their blog post: ' . $model->title;
			if ($user->UID != $model->createdBy) { $title = 'Blog post updted: ' . $model->title; }

			$content = ''
			 . $theme->makeSummary($model->content, 1000) . ' '
			 . "<a href='" . $ext['viewUrl'] . "'>[ show post &gt;&gt; ]</a><br/>";

			$nUID = $notifications->create(
				'moblog', 'moblog_post', $model->UID, 'moblog_save', 
				$title, $content, $ext['viewUrl'],
				false
			);

			$notifications->addUser($nUID, $user->UID);
			$notifications->addUser($nUID, $model->createdBy);
			$notifications->addFriends($nUID, $model->createdBy);
			$notifications->addAdmins($nUID);
			$notifications->addSchool($nUID, $user->school);
			//$notifications->addSchoolGrade($nUID, $user->school, $user->grade);
		}
	}
	*/	

	//----------------------------------------------------------------------------------------------
	//	redirect to post
	//----------------------------------------------------------------------------------------------
	$page->do302('moblog/' . $model->alias);
	

?>
