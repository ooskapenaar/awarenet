<?

//--------------------------------------------------------------------------------------------------
// 	common shared functions
//--------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------
//	create a unique ID 
//--------------------------------------------------------------------------------------------------

function createUID() {
	$tempUID = "";
	for ($i = 0; $i < 16; $i++) { $tempUID .= "" . mk_rand(); }
	return substr($tempUID, 0, 18);
}

//--------------------------------------------------------------------------------------------------
// 	clean a string of crap (to match searches and try stop SQL included into query)
//--------------------------------------------------------------------------------------------------

function clean_string($toClean) {
	$isClean = urldecode($toClean);
	$isClean = str_replace(";", ":", $isClean);
	$isClean = str_replace("'", "`", $isClean);
	$isClean = str_replace("\"", "``", $isClean);
	$isClean = str_replace("<", "<.", $isClean);
	return trim($isClean);
}

//--------------------------------------------------------------------------------------------------
// 	make a random number
//--------------------------------------------------------------------------------------------------

function make_seed() {
   list($usec, $sec) = explode(' ', microtime());
   return (float) $sec + ((float) $usec * 100000);
}

function mk_rand() {
	srand(make_seed());
	return rand();
}

function mk_rand_num($min, $max) {
	srand(make_seed());
	return rand($min, $max);
}

//--------------------------------------------------------------------------------------------------
//	Take tags out of HTML
//--------------------------------------------------------------------------------------------------

function stripHTML($someText) {
	return preg_replace("'<[\/\!]*?[^<>]*?>'si", "", $someText);
}

//--------------------------------------------------------------------------------------------------
// 	get the current date/time in mySQL format
//--------------------------------------------------------------------------------------------------

function mysql_dateTime() { return gmdate("Y-m-j H:i:s", time()); }

function mk_mysql_dateTime($date) { return gmdate("Y-m-j H:i:s", $date); }

//--------------------------------------------------------------------------------------------------
// 	get a field from $_GET, $_POST, or set it to a default value if it down not exist
//--------------------------------------------------------------------------------------------------

function requestField($fieldName, $default) {
	$retVal = $default;
	if (array_key_exists($fieldName, $_GET)) { $retVal = sqlMarkup($_GET[$fieldName]); }
	if (array_key_exists($fieldName, $_POST)) { $retVal = sqlMarkup($_POST[$fieldName]); }
	return $retVal;	
}

//--------------------------------------------------------------------------------------------------
// 	strip empty values from an array
//--------------------------------------------------------------------------------------------------

function arrayCleanEmpty($arr) {
	$retVal = array();
	foreach($arr as $value) {
		if (strlen(trim($value)) > 0) {
			$retVal[] = $value;
		}
	}
	return $retVal;
}

//--------------------------------------------------------------------------------------------------
// 	remove all but alphanumeric characters from a string (allow '-', '_', convert space to _ )
//--------------------------------------------------------------------------------------------------

function mkAlphaNumeric($txt) {
	$txt = trim($txt);
	if ($txt == '') { return ''; }
	$retVal = '';
	$numChars = strlen($txt);
	for($i = 0; $i < $numChars; $i++) {
		$currChar = substr($txt, $i, 1);
		$oCC = ord($currChar);
		
		if (($oCC >= 48) AND ($oCC <= 57)) { $retVal .= $currChar; } 	// 0-9
		if (($oCC >= 97) AND ($oCC <= 122)) { $retVal .= $currChar; } 	// a-z
		if (($oCC >= 65) AND ($oCC <= 90)) { $retVal .= $currChar; } 	// A-Z
		if ($oCC == 32) { $retVal .= '_'; } // space
		if ($oCC == 45) { $retVal .= '-'; } // minus
		if ($oCC == 95) { $retVal .= '_'; } // underscore
		if ($oCC == 47) { $retVal .= '_'; } // forwardslash
	}
	
	return $retVal;
}

//--------------------------------------------------------------------------------------------------
// 	get the last x characters from a string (hopefully multibyte safe)
//--------------------------------------------------------------------------------------------------

function mb_endstr($numChars, $txt) {
	$txt = trim($txt);
	$len = mb_strlen($txt);
	if ($len > $numChars) {
		return mb_substr($txt, $len - $numChars);
	} else { return $txt; }
}

//--------------------------------------------------------------------------------------------------
// 	determines if a file/dir exists and is readable + writeable
//--------------------------------------------------------------------------------------------------

function is_extantrw($fileName) {
	if (file_exists($fileName)) {
		if (is_readable($fileName) == false) { return false; }
		if (is_writable($fileName) == false) { return false; }
	} else { return false; }
	return true;
}

//--------------------------------------------------------------------------------------------------
// 	remove comments from beginning and end of php file
//--------------------------------------------------------------------------------------------------

function phpUnComment($raw) {
	$raw = trim($raw);
	if (substr($raw, 0, 5) == '<? /*') { $raw = substr($raw, 5); }
	$len = strlen($raw);
	if (substr($raw, ($len - 5)) == '*/ ?>') { $raw = substr($raw, 0, ($len - 5)); }
	return $raw;
}

//--------------------------------------------------------------------------------------------------
// 	some php versions do not support file_put_contents
//--------------------------------------------------------------------------------------------------
//	mode: w, w+, a, a+

function filePutContents($fileName, $contents, $mode) {
	fileMakeSubdirs($fileName);
	$fH = fopen($fileName, $mode);
	if ($fH == false) { return false; }
	fwrite($fH, $contents);
	fclose($fH);
	return true;
}

//--------------------------------------------------------------------------------------------------
// 	ensire that directory exists
//--------------------------------------------------------------------------------------------------

function fileMakeSubdirs($fileName) {
	global $installPath;
	$fileName = str_replace("//", '/', $fileName);	
	$dirName = str_replace($installPath, '', $fileName);
	$dirName = dirname($dirName);
	$base = $installPath;
	$subDirs = explode('/', $dirName);
	foreach($subDirs as $sdir) {
		$base = $base . $sdir . '/';
		if (file_exists($base) == false) { mkdir($base); }
	}
}

//--------------------------------------------------------------------------------------------------
// 	download a file using curl
//--------------------------------------------------------------------------------------------------

function curlGet($url, $password) {
	if (function_exists('curl_init') == false) { return false; }	// is curl installed?

	//---------------------------------------------------------------------------------------------
	//	create baisc cURL HTTP GET request
	//---------------------------------------------------------------------------------------------
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $postHeaders);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	//---------------------------------------------------------------------------------------------
	//	use HTTP proxy if enabled
	//---------------------------------------------------------------------------------------------
	if ($proxyEnabled == 'yes') {
		$credentials = $proxyUser . ':' . $proxyPass;
		curl_setopt($ch, CURLOPT_PROXY, $proxyAddress);
		curl_setopt($ch, CURLOPT_PROXYPORT, $proxyPort);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
		if (trim($credentials) != ':') {
			curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $credentials);
		}
	}

	//---------------------------------------------------------------------------------------------
	//	return result
	//---------------------------------------------------------------------------------------------
	$result = curl_exec($ch);
	return $result;
}

//--------------------------------------------------------------------------------------------------
// 	render a 2d array as a table
//--------------------------------------------------------------------------------------------------

function arrayToHtmlTable($ary, $wireframe = false, $firstrowtitle = false) {
	if (false == $wireframe) { 
		$html = "<table noborder width='100%'>";
		foreach($ary as $row) {
			$html .= "\t<tr>\n";
			foreach($row as $col) {	$html .= "\t\t<td>" . $col . "</td>\n"; }	
			$html .= "\t</tr>\n"; 
		}
		$html .= "</table>";

	} else {
		$html = "<table class='wireframe' width='100%'>";
		foreach($ary as $row) {
			$tdClass = 'wireframe';
			if (true == $firstrowtitle) { $firstrowtitle = false; $tdClass = 'title'; }
			$html .= "\t<tr>\n";
			foreach($row as $col) {	$html .= "\t\t<td class='". $tdClass ."'>". $col ."</td>\n"; }	
			$html .= "\t</tr>\n"; 
		}
		$html .= "</table>";
	}

	return $html;
}

//--------------------------------------------------------------------------------------------------
// 	mark up html for injection via javascript
//--------------------------------------------------------------------------------------------------

function jsMarkup($txt) {
	$txt = str_replace("'", '--squote--', $txt);
	$txt = str_replace("\"", '--dquote--', $txt);
	$txt = str_replace("\n", '--newline--', $txt);
	$txt = str_replace("\r", '', $txt);
	$txt = str_replace("[[:", '[[%%delme%%:', $txt);
	return $txt;
}

//--------------------------------------------------------------------------------------------------
// 	convert to base64 (TODO: make this more efficient)
//--------------------------------------------------------------------------------------------------

function base64EncodeJs($varName, $text, $scriptTags = true) {
	$b64 = base64_encode($text);										// encode
	$b64 = wordwrap($b64, 80, "\n", true);								// break into 80 car lines
	$break = "\"\n" . str_repeat(' ', strlen($varName) + 5) . "+ \"";	// newline/indent pattern
	$b64 = str_replace("\n", $break, $b64);								// apply pattern to lines
	$b64 = "var $varName = \"" . $b64 . "\";\n";						// add js varname
	if (true == $scriptTags) { $b64 = "<script language='Javascript'>\n" . $b64 . "</script>\n"; }
	return $b64;														// done
}

//--------------------------------------------------------------------------------------------------
// 	start a process in background (*nix only)
//--------------------------------------------------------------------------------------------------
//	source: http://nsaunders.wordpress.com/2007/01/12/running-a-background-process-in-php/
//	TODO: find equivalent for windows

function procExecBackground($Command, $Priority = 0) {
	$PID = false;
	if ($Priority) { 
		// consider removing this
		$PID = exec("$Command > > /dev/null 2>&1 &"); 
	} else { 
		$PID = exec("$Command > /dev/null 2>&1 &");
	}
	return $PID;
}

//--------------------------------------------------------------------------------------------------
// 	discover if a process is running
//--------------------------------------------------------------------------------------------------
//	source: http://nsaunders.wordpress.com/2007/01/12/running-a-background-process-in-php/

function procIsRunning($PID) {
	exec("ps $PID", $ProcessState);
	return(count($ProcessState) >= 2);
}

//--------------------------------------------------------------------------------------------------
// 	remove javascript from html (prevent XSS worms, etc)
//--------------------------------------------------------------------------------------------------
//	source: http://us3.php.net/manual/en/function.strip-tags.php

function stripJavascript(	$sSource, 
							$aAllowedTags = array(), 
							$aDisabledAttributes = array(	
									'onabort', 'onactivate', 'onafterprint', 'onafterupdate', 
									'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 
									'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 
									'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 
									'onbounce', 'oncellchange', 'onchange', 'onclick', 
									'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 
									'ondataavaible', 'ondatasetchanged', 'ondatasetcomplete', 
									'ondblclick', 'ondeactivate', 'ondrag', 'ondragdrop', 
									'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 
									'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 
									'onfilterupdate', 'onfinish', 'onfocus', 'onfocusin', 
									'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 
									'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 
									'onmouseenter', 'onmouseleave', 'onmousemove', 'onmoveout', 
									'onmouseover', 'onmouseup', 'onmousewheel', 'onmove',
									'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange',
									'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 
									'onresizestart', 'onrowexit', 'onrowsdelete', 
									'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 
									'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload') 
							) {

	if (empty($aDisabledAttributes)) return strip_tags($sSource, implode('', $aAllowedTags));

	$pattern = '/<(.*?)>/ie';

	$replacement = 	"'<' . preg_replace(array('/javascript:[^\"\']*/i', '/("
					. implode('|', $aDisabledAttributes)
					. ")[ \\t\\n]*=[ \\t\\n]*[\"\'][^\"\']*[\"\']/i', '/\s+/'), "
					. "array('', '', ' '), stripslashes('\\1')) . '>'";

	return preg_replace($pattern, $replacement, $sSource);

}

?>
