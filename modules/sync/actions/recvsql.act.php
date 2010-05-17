<?

//-------------------------------------------------------------------------------------------------
//	recieve a SQL update from a peer
//-------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------
	//	authorize
	//---------------------------------------------------------------------------------------------

	if (syncAuthenticate() == false) { doXmlError('could not authenticate'); }

	//---------------------------------------------------------------------------------------------
	//	add to database
	//---------------------------------------------------------------------------------------------

	if (array_key_exists('detail', $_POST) == false) { doXmlError('update not sent'); }
	if (trim($_POST['detail']) == '') { doXmlError('update is empty'); }
	
	$data = syncBase64DecodeSql($_POST['detail']);

	logSync("table: " . $data['table'] . "\n");
	foreach($data['fields'] as $f => $v) { logSync("field: $f value: $v \n"); }

	syncDbSave($data['table'], $data['fields']);

	//---------------------------------------------------------------------------------------------
	//	pass on to peers
	//---------------------------------------------------------------------------------------------	
	
	$syncHeaders = syncGetHeaders();
	$source = $syncHeaders['Sync-Source'];
	syncBroadcastDbUpdate($source, $data['table'], $data['fields']);

	//---------------------------------------------------------------------------------------------
	//	done
	//---------------------------------------------------------------------------------------------		

	echo "<ok/>"; flush();

?>