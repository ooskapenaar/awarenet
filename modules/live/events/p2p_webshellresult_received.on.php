<?php

//--------------------------------------------------------------------------------------------------
//*	fired when a peer returns the result of a webshell command
//--------------------------------------------------------------------------------------------------
//arg: uid - UID of the request [string]
//arg: for - UID of peer this reponse is for [string]
//arg: from - UID of peer this result is from / was generated by [string]
//arg: cmd64 - base64 encoded command [string]
//arg: result64 - base64 encoded response [string]

function live__cb_p2p_webshellresult_received($args) {
	global $kapenta;	

	if (false == array_key_exists('uid', $args)) { return false; }
	if (false == array_key_exists('session', $args)) { return false; }
	if (false == array_key_exists('for', $args)) { return false; }
	if (false == array_key_exists('from', $args)) { return false; }
	if (false == array_key_exists('cmd64', $args)) { return false; }
	if (false == array_key_exists('result64', $args)) { return false; }

	//----------------------------------------------------------------------------------------------
	//	TODO check the result is for this peer, relay if not
	//----------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	add result to text file to be polled by admin console
	//----------------------------------------------------------------------------------------------

	$fileName = 'data/live/remoteshell/' . $args['session'] . '.txt';

	$content = ''
	 . "<b>response from peer: " . $args['from'] . "</b><br/>\n"
	 . "<b>cmd: " . base64_decode($args['cmd64']) . "</b><br/>\n"
	 . base64_decode($args['result64']);

	$kapenta->fs->put($fileName, $content, true, false, 'a+');
}

?>