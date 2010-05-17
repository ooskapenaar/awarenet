<?

//--------------------------------------------------------------------------------------------------
//*	functions for reading and writing /theme/x/style.xml.php
//--------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------
//|	load theme style variables into an array (usually attached to page object)
//--------------------------------------------------------------------------------------------------
//arg: theme - theme name [string]
//returns: theme stylesheet; associative array [array]

function themeReadStyle($theme) {
	global $installPath;
	$ary = array();
	$xe = xmlLoad($installPath . 'themes/' . $theme . '/style.xml.php');  // construct fileName
	foreach($xe->children as $index => $child) { $ary[$child->type] = $child->value; }
	return $ary;
}

//--------------------------------------------------------------------------------------------------
//	save an array of style variables
//--------------------------------------------------------------------------------------------------
//arg: fileName - absolute file name [string]
//arg: style - theme stylesheet; associative array [array]
//returns: true on success, else false [bool]
//: TODO: use filePutContents

function themeWriteStyle($fileName, $style) {
	global $installPath;

	// construct XML
	$xe = new XmlEntity();	
	$xe->type = 'theme';
	foreach($style as $tag => $value) {	$xe->addChild($tag, $value); }
	$xml = "<" . "?\n" . $xe->toXml() . "\n?" . ">";

	// write it to style.xml.php
	$fH = fopen($fileName, 'w+');
	if ($fH != false) { fwrite($fH, $xml); fclose($fH); return true; } 
	return false;
}

?>
