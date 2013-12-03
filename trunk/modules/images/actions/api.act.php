<?

//--------------------------------------------------------------------------------------------------
//*	images API (DEPRECATED)
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	check that user is logged in and refmodule, refuid supplied
	//----------------------------------------------------------------------------------------------

	if ($user->role == 'public') { $page->doXmlError('not logged in'); }

	if (false == array_key_exists('refuid', $kapenta->request->args)) { $page->doXmlError('refUID not given'); }
	if (false == array_key_exists('refmodule', $kapenta->request->args)) { $page->doXmlError('module not specified'); }

	//----------------------------------------------------------------------------------------------
	//	list images 
	//----------------------------------------------------------------------------------------------

	if ('list' == $kapenta->request->ref) {

		$conditions = array();
		if (array_key_exists('refuid', $kapenta->request->args) == true) 
			{ $conditions[] = "refUID='" . $db->addMarkup($kapenta->request->args['refuid']) . "'"; }

		if (array_key_exists('refmodule', $kapenta->request->args) == true) 
			{ $conditions[] = "refModule='" . $db->addMarkup($kapenta->request->args['refmodule']) . "'"; }

		$range = $db->loadRange('images_image', '*', $conditions);

		echo "<?xml version=\"1.0\"?>\n";
		echo "<imageset>\n";	

		foreach ($range as $row) {
			$model = new Images_Image();
			$model->loadArray($row);
			echo $model->toXml('    ');
		}

		echo "</imageset>\n";

	}

?>