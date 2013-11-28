<?

//--------------------------------------------------------------------------------------------------
//	display all files and folders in a project, module or theme
//--------------------------------------------------------------------------------------------------
//	Simple text list of project files (relative paths) and the MD5 sum of their latest version


	if ($kapenta->request->ref != '') { $kapenta->request->args['project'] = $kapenta->request->ref; }
	if (array_key_exists('project', $kapenta->request->args) == false) { $page->do404(); }

	$args = array('project' => $kapenta->request->args['project']);

	$list = code_mkprojectlist($args);

	echo $list;


//--------------------------------------------------------------------------------------------------
//	show list of project files (tab delimited UID, SHA1, type, path)
//--------------------------------------------------------------------------------------------------
// * $args['project'] = project UID

function code_mkprojectlist($args) {
	global $db;

	if (array_key_exists('project', $args) == false) { return false; }
	$sql = "select * from code where project='". $db->addMarkup($args['project']) ."' and parent='root'";
	$result = $db->query($sql);
	if ($db->numRows($result) == 0) { return false; }
	$row = $db->fetchAssoc($result);
	$txt = code__mkprojectlistfrom($row['UID'], '/');
	return $txt;
}

function code__mkprojectlistfrom($UID, $relPath) {
	global $db;

	$txt = '';
	$sql = "select * from code where parent='" . $UID . "'";
	$result = $db->query($sql);

	while ($row = $db->fetchAssoc($result)) {
		$row = $db->rmArray($row);
		$txt .= $row['UID'] . "\t" 
				. $row['hash'] . "\t" 
				. $row['type'] . "\t" 
				. $relPath . $row['title'] . "\n";

		if ($row['type'] == 'folder') 
			{ $txt .= code__mkprojectlistfrom($row['UID'], $relPath . $row['title']); }

	}

	return $txt;		
}



?>