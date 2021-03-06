<?

//--------------------------------------------------------------------------------------------------
//	API of galleries module. No public actions.
//--------------------------------------------------------------------------------------------------

if ($kapenta->user->role == 'public') { $kapenta->page->doXmlError('not logged in'); }

//--------------------------------------------------------------------------------------------------
//	list records owned by the current user
//--------------------------------------------------------------------------------------------------

if ($kapenta->request->ref == 'myrecords') {
	$sql = "select * from gallery_gallery where createdBy='" . $kapenta->user->UID . "' order by title";
	$result = $kapenta->db->query($sql);
	
	echo "<?xml version=\"1.0\"?>\n";
	echo "<recordset>\n";
	while ($row = $kapenta->db->rmArray($kapenta->db->fetchAssoc($result))) { 
		$ary = array(	'uid' => $row['UID'], 
						'module' => 'gallery',
						'title' => $row['title'],
						'recordalias' => $row['alias'],
						'files' => 'none',
						'images' => 'uploadmultiple',
						'videos' => 'none' );

		echo $utils->arrayToXml2d($ary, 'record', '  '); 
	}
	echo "</recordset>\n";	
}

?>
