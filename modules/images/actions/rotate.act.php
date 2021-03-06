<?

	require_once($kapenta->installPath . 'modules/images/models/image.mod.php');
	require_once($kapenta->installPath . 'modules/images/models/transforms.set.php');
	require_once($kapenta->installPath . 'modules/images/inc/utils.inc.php');

//--------------------------------------------------------------------------------------------------
//*	rotate an image 90 degrees left or right
//--------------------------------------------------------------------------------------------------
//postarg: UID - UID of an images_image object [string]
//postopt: direction - direction to rotate (clockwise|anticlockwise) [string]
//postopt: return - optional return url [string]

	//----------------------------------------------------------------------------------------------
	//	check arguments and permissions
	//----------------------------------------------------------------------------------------------
	if (false == array_key_exists('UID', $_POST)) { $kapenta->page->do403('Image UID not given.'); }
	if (false == array_key_exists('direction', $_POST)) { $kapenta->page->do403('Direction not given.'); }

	$model = new Images_Image($_POST['UID']);
	if (false == $model->loaded) { $kapenta->page->do404("Image not found."); }

	if (false == $kapenta->user->authHas($model->refModule, $model->refModel, 'images-add', $model->refUID)) {
		$kapenta->page->do403('You are not authorized to edit this image.'); 
	}

	if (false == $kapenta->fs->exists($model->fileName)) { $kapenta->page->do404('File missing.'); }

	$angle = 270;
	if (
		(true == array_key_exists('direction', $_POST)) &&
		('anticlockwise' == $_POST['direction']))
	{ $angle = 90;	}

	//----------------------------------------------------------------------------------------------
	//	rotate the image
	//----------------------------------------------------------------------------------------------


	foreach($model->transforms->members as $label => $fileName) {
		if ('' !== $fileName) {
			$check = $kapenta->fileDelete($fileName, true);
			if (false == $check) {
				$msg = ''
				 . "Could not delete tranform of image: " . $model->title
				 . " (" . $model->UID . "):<br/>" . $fileName;
				$kapenta->session->msg($msg);
			}
		}
	}

	//----------------------------------------------------------------------------------------------
	//	rotate the image // NOTE: this is a lossy operation for jpegs
	//----------------------------------------------------------------------------------------------
	//header("Content-type: image/jpeg");

	$source = imagecreatefromjpeg($model->fileName);
	$rotate = images_rotate($source, $angle, 0);

	imagejpeg($rotate, $model->fileName, $angle);

	$model->hash = $kapenta->fileSha1($model->fileName);
	$report = $model->save();
		
	if ('' == $report) {
		$kapenta->session->msg("Rotated image.", 'ok');
	} else {
		$kapenta->session->msg("Error while rotating image:<br/>" . $report, 'bad');
	}

	//----------------------------------------------------------------------------------------------
	//  clear transforms
    //----------------------------------------------------------------------------------------------
    
    $transforms = new Images_Transforms($model->UID, $model->fileName);
    $transforms->deleteAll();

	//----------------------------------------------------------------------------------------------
	//	redirect back to images module
	//----------------------------------------------------------------------------------------------
	$kapenta->page->do302('images/show/' . $model->alias);

?>
