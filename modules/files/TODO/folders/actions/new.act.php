<?

	require_once($kapenta->installPath . 'modules/folders/models/folder.mod.php');

//--------------------------------------------------------------------------------------------------
//	add a new (root) folder
//--------------------------------------------------------------------------------------------------
//TODO: fix this up, probably with autogenerated code

	if (false == $user->authHas('files', 'files_folder', 'new')) { $page->do403(); }

	$model = new Folder();
	$model->save();
	
	$page->do302('folders/edit/' . $model->alias);

?>
