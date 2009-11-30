<?

//-------------------------------------------------------------------------------------------------
//	exports table dbSchemas as php code, for making install script
//-------------------------------------------------------------------------------------------------

if ($user->data['ofGroup'] != 'admin') { do403(); }
if (($request['ref'] == '') || (dbTableExists($request['ref']) == false)) { 
	echo "no table specified";
	die(); 

} else {
	//---------------------------------------------------------------------------------------------
	//	make the code
	//---------------------------------------------------------------------------------------------

	$dbs = xdbGetSchema($request['ref']);

	$code = '';
	$code .= "\t//----------------------------------------------------------------------------------------------\n";
	$code .= "\t//\t" . $request['ref'] . " table\n";
	$code .= "\t//----------------------------------------------------------------------------------------------\n\n";

	$code .= "\t\$dbSchema = array();\n";
	$code .= "\t\$dbSchema['table'] = '" . $request['ref'] . "';\n";
	$code .= "\t\$dbSchema['fields'] = array(\n";

	foreach($dbs['fields'] as $field => $type) {
		$code .= "\t\t'" . $field . "' => '" . $type . "',\n";
	}

	$code .= ");\n";
	$code = str_replace(",\n);", " );\n", $code);


	echo "<html>
	<body>
	<textarea rows='50' cols='140'>$code</textarea>
	</body>
	</html>
	";

}


function xdbGetSchema($tableName) {
	if (dbTableExists($tableName) == false) { return false; }

	//----------------------------------------------------------------------------------------------
	//	create dbSchema array
	//----------------------------------------------------------------------------------------------
	$dbSchema = array(	'table' => $tableName, 'fields' => array(), 
						'indices' => array(), 'nodiff' => array()	);

	//----------------------------------------------------------------------------------------------
	//	add fields
	//----------------------------------------------------------------------------------------------
	$sql = "describe " . sqlMarkup($tableName);
	$result = dbQuery($sql);
	while ($row = dbFetchAssoc($result)) 
		{ $dbSchema['fields'][$row['Field']] = strtoupper($row['Type']); }

	//----------------------------------------------------------------------------------------------
	//	add indices
	//----------------------------------------------------------------------------------------------
	$sql = "show indexes from " . sqlMarkup($tableName);
	$result = dbQuery($sql);
	while ($row = dbFetchAssoc($result)) 
		{ $dbSchema['indices'][$row['Column_name']] = $row['Sub_part']; }

	return $dbSchema;
}

?>
