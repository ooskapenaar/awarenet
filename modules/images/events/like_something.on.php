<?

	require_once($kapenta->installPath . 'modules/images/models/image.mod.php');

//--------------------------------------------------------------------------------------------------
//|	fired when a user confirms a 'like' button or link
//--------------------------------------------------------------------------------------------------
//arg: refModule - name of a kapenta module [string]
//arg: refModel - type of object being liked [string]
//arg: refUID - UID of object being liked [string]

function images__cb_like_something($args) {
	global $kapenta;
	global $theme;
	global $notifications;

	//----------------------------------------------------------------------------------------------
	//	check event arguments
	//----------------------------------------------------------------------------------------------
	if (false == array_key_exists('refModule', $args)) { return false; }
	if (false == array_key_exists('refModel', $args)) { return false; }
	if (false == array_key_exists('refUID', $args)) { return false; }

	$refModule = $args['refModule'];
	$refModel = $args['refModel'];
	$refUID = $args['refUID'];

	if ('images' != $refModule) { return false; }

	//----------------------------------------------------------------------------------------------
	//	notify on like of images_image objects
	//----------------------------------------------------------------------------------------------

	if ('images_image' == $refModel) {
		$model = new Images_Image($refUID);

		if (true == $model->loaded) {

			$creatorName = '[[:users::name::userUID=' . $model->createdBy . ":]]'s";
			if ($model->createdBy == $kapenta->user->UID) { $creatorName = 'their own'; }
	
			$title = $kapenta->user->getName() . " likes $creatorName image '" . $model->title . "";
			$url = '%%serverPath%%images/show/' . $model->alias;

			$content = ''
			 . "[[:images::show::width=indent::imageUID=" . $model->UID . ":]]\n"
			 . "[[:like::otherusers"
			 . "::userUID=" . $kapenta->user->UID 
			 . "::refModule=" . $refModule
			 . "::refModel=" . $refModel
			 . "::refUID=" . $refUID
			 . ":]]";

			$nUID = $notifications->create(
				$refModule, $refModel, $refUID, 
				'like_something', $title, $content, $url
			);

			$notifications->addUser($nUID, $kapenta->user->UID);
			$notifications->addFriends($nUID, $kapenta->user->UID);
			$notifications->addSchoolGrade($nUID, $kapenta->user->school, $kapenta->user->grade);

		}
	}

}

?>
