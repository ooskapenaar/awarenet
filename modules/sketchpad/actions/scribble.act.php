<?php

	require_once($kapenta->installPath . 'modules/images/models/image.mod.php');

//--------------------------------------------------------------------------------------------------
//*	initialize and render canvas sketchpad app and load a given image
//--------------------------------------------------------------------------------------------------
	
	//----------------------------------------------------------------------------------------------
	//	check arguments and permissions
	//----------------------------------------------------------------------------------------------
	if ('public' == $user->role) { $page->do403(); }

	if ('' == $kapenta->request->ref) { $page->do404('No image specified.'); }

	$model = new Images_Image($kapenta->request->ref);
	if (false == $model->loaded) { $page->do404('Unkown image'); }

	$model->transforms->load();
	$check = $model->transforms->loadImage();

	if (false == $check) { $page->do404('Could not load image file (s).'); }

	//echo ''
	//	 . "Image dimensions: "
	//	 . $model->transforms->width . "x" . $model->transforms->height . " <br/>\n";
	//die();

	//----------------------------------------------------------------------------------------------
	//	render the template
	//----------------------------------------------------------------------------------------------

	$template = $kapenta->fs->get('modules/sketchpad/templates/sketchpad.template.php');

	$labels = array(
		'defaultTheme' => $kapenta->defaultTheme,
		'serverPath' => $kapenta->serverPath,
		'userUID' => $user->UID,
		'imageUrl' => 'images/full/' . $model->UID,
		'width' => $model->transforms->width,
		'height' => $model->transforms->height,
		'title' => $model->title,
		'title64' => base64_encode($model->title)
	);

	$html = $theme->replaceLabels($labels, $template);
	echo $html;

?>