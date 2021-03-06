<?

	require_once($kapenta->installPath . 'modules/users/models/role.mod.php');

//--------------------------------------------------------------------------------------------------
//*	revoke a permission
//--------------------------------------------------------------------------------------------------
//reqarg: role - name of a user role [string]
//reqarg: module - name of a kapenta module [string]
//reqarg: model - object type [string]
//reqarg: permission - name of a permission applying to this object type [string]

	//----------------------------------------------------------------------------------------------
	//	check arguments and user role
	//----------------------------------------------------------------------------------------------
	if ('admin' != $kapenta->user->role) { $kapenta->page->do403(); }

	if (false == array_key_exists('role', $kapenta->request->args)) { $kapenta->page->do404('Role not given.'); }
	if (false == array_key_exists('module', $kapenta->request->args)) { $kapenta->page->do404('Module not given.'); }
	if (false == array_key_exists('model', $kapenta->request->args)) { $kapenta->page->do404('Model not given.'); }
	if (false == array_key_exists('permission', $kapenta->request->args)) { $kapenta->page->do404('Perm not given.'); }

	$module = $kapenta->request->args['module'];
	$model = $kapenta->request->args['model'];
	$permission = $kapenta->request->args['permission'];

	$role = new Users_Role($kapenta->request->args['role'], true);
	if (false == $role->loaded) { $kapenta->page->do404('Unknown role.'); }	

	//----------------------------------------------------------------------------------------------
	//	remove the permission from this role
	//----------------------------------------------------------------------------------------------

	$check = $role->permissions->remove($module, $model, $permission);
	if (true == $check) { $kapenta->session->msg('Permission revoked.', 'ok'); }
	else { $kapenta->session->msg('Cound not revoke permission, not found.', 'bad'); }

	$report = $role->save();
	if ('' == $report) { $kapenta->session->msg('Updated user role.', 'ok'); }
	else { $kapenta->session->msg('Could not update user role:<br/>' . $report, 'bad'); }

	//----------------------------------------------------------------------------------------------
	//	redirect back to admin console for this module
	//----------------------------------------------------------------------------------------------
	$kapenta->page->do302('admin/module/' . $module);

?>
