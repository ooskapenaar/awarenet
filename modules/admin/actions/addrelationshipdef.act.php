<?

	require_once($kapenta->installPath . 'core/kmodule.class.php');
	require_once($kapenta->installPath . 'core/kmodel.class.php');

//--------------------------------------------------------------------------------------------------
//*	creates a new relationship definition between a user and a model (module.xml.php)
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	check POST vars and user role
	//----------------------------------------------------------------------------------------------
	if ('admin' != $user->role) { $page->do403(); }

	$modulename = '';		//%	name of a kapenta module [string]
	$modelname = '';		//%	name of object type [string]
	$relationship = '';		//%	name of relationship between user and object [string]

	if (true == array_key_exists('module', $_POST)) { $modulename = $_POST['module']; }
	if (true == array_key_exists('model', $_POST)) { $modelname = $_POST['model']; }
	if (true == array_key_exists('relationship', $_POST)) { 
		$relationship = $utils->cleanTitle($_POST['relationship']); 
	}

	if ('' == trim($relationship)) { $page->do404('Relationship not specified.'); }

	$module = new KModule($modulename);
	if (false == $module->loaded) { $page->do404('Unkown module.'); }
	if (false == $module->hasModel($modelname)) { $page->do404('Unknown model.'); }

	$model = new KModel();
	$model->loadArray($module->models[$modelname]);

	$check = $model->addRelationship($relationship);

	if (true == $check) { 
		$module->models[$modelname] = $model->toArray();
		$module->save();
		$session->msg('Added relationship', 'ok'); 

	} else {
		$session->msg('Could not add relationship.', 'bad');
	}

	//----------------------------------------------------------------------------------------------
	//	redirect back to /editmodule/
	//----------------------------------------------------------------------------------------------
	$page->do302('admin/editmodule/' . $modulename);

?>