<?

//--------------------------------------------------------------------------------------------------
//*	(temprary) upgrade action to fix references to old tables
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	authentication
	//----------------------------------------------------------------------------------------------
	if ('admin' != $kapenta->user->role) { $kapenta->page->do403(); }



?>
