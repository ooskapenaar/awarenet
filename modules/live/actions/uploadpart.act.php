<?

	require_once($kapenta->installPath . 'modules/live/inc/upload.class.php');

//--------------------------------------------------------------------------------------------------
//*	upload a file part
//--------------------------------------------------------------------------------------------------
//postarg: filehash - sha1 hash of all part hashes [string]
//postarg: parthash - sha1 hash of raw hash [string]
//postarg: index - index of this part [string]
//postarg: part64 - base64 encoded binary data [string]

	//----------------------------------------------------------------------------------------------
	//	check arguments
	//----------------------------------------------------------------------------------------------
	if (false == array_key_exists('filehash', $_POST)) { $page->doXmlError('filehash not given'); }
	if (false == array_key_exists('parthash', $_POST)) { $page->doXmlError('parthash not given'); }
	if (false == array_key_exists('index', $_POST)) { $page->doXmlError('index not given'); }
	if (false == array_key_exists('part64', $_POST)) { $page->doXmlError('part not given'); }

	$filehash = $_POST['filehash'];
	$parthash = $_POST['parthash'];
	$index = (int)$_POST['index'];
	$length = (int)$_POST['length'];

	$part64 = $_POST['part64'];
	$part64 = str_replace(' ', '+', $part64);
	
	$part = base64_decode($part64);

	$kapenta->fs->put('data/live/some64.txt', $part64);
	$kapenta->fs->put('data/live/some.jpg', $part);
	
	if (mb_strlen($part, 'ASCII') != $length) {
	 	header( "HTTP/1.1 500 Internal Server Error" );
		echo "LENGTH MISMATCH: " . mb_strlen($part, 'ASCII') . '  != ' . $length . "<br/>\n";
		//echo "starts:" . substr($part, 0, 100) . "<br/>\n";
		//echo "ends:" . substr($part, strlen($part) - 100) . "<br/>\n";
		//$part = substr($part, 0, $length);
	}

	$sha1 = sha1($part);
	if ($sha1 != $parthash) {
	 	header( "HTTP/1.1 500 Internal Server Error" ); 
		$msg = "<b>Hash mismatch:</b><br/>\n"
		 . "index: $index <br/>\n"
		 . "sha1 hash of received data: $sha1 <br/>\n"
		 . "parthash (expected): $parthash <br/>\n"
		 . "filehash: $filehash <br/>\n"
		 . "recieved size: " . strlen($part64) . " (b64 encoded)<br/>\n"
		 . "part size: " . strlen($part) . " (b64 decoded)<br/>\n"
		 . "";
		// . "<textarea rows='10' cols='80'>$part</textarea>\n"

		$page->doXmlError($msg);
	}

	//echo "filehash: $filehash<br/>";

	$upload = new Live_Upload($filehash);
	if (false == $upload->loaded) { $page->doXmlError('Unknown upload.'); }

	if (false == array_key_exists($index, $upload->parts)) { $page->doXmlError('Unexpected part.'); }
	if ('ok' == $upload->parts[$index]['status']) { $page->doXmlError('Already have this'); }

	//----------------------------------------------------------------------------------------------
	//	add the part
	//----------------------------------------------------------------------------------------------
	$check = $upload->storePart($index, $part64, $parthash);
	if (false == $check) { $page->doXmlError('Could not store part.'); }
	else { $upload->saveXml(); }

	echo '<b>' . $upload->getBitmapTemp() . '</b>';

?>
