<?php

//---------------------------------------------------------------------o -----------------------------
//*	popup window to edit course details
//--------------------------------------------------------------------------------------------------

	if (('admin' !== $kapenta->user->role) && ('teacher' !== $kapenta->user->role)) {
		$kapenta->page->do403('You are not permitted to edit this course', true);
	}

	if ('' == $kapenta->request->ref) { $kapenta->page->do404('Course not specified.', true); }

	if (false = $kapenta->fs->exists('data/lessons/' . $kapenta->request->ref)) {
		$kapenta->page->do404('No such course.', true);
	}

	$kapenta->page->load('modules/lessons/actions/editcourse.page.php');
	$kapenta->page->blockArgs['UID'] = $kapenta->request->ref;
	$kapenta->page->render();

?>
