<?

//--------------------------------------------------------------------------------------------------
//|	show a page of deleted objects
//--------------------------------------------------------------------------------------------------
//opt: pageNo - results page to display (int) [string]
//opt: objectType - type of deleted object to show, * for all [string]
//opt: num - number of objects per page, default is 50 (int) [string]

function revisions_listdeleted($args) {
		global $kapenta;
		global $kapenta;
		global $theme;
		global $revisions;


	$html = '';					//%	return value [string]
	$pageNo = 1;				//%	page number (starts at 1) [int]
	$num = 50;					//%	number of items per page [int]
	$objectType = '*';			//%	type of deleted object to show [string]
	$pagination = 'yes';		//%	display pagination links (yes|no) [string]

	//----------------------------------------------------------------------------------------------
	//	check arguments and user role
	//----------------------------------------------------------------------------------------------
	if ('admin' != $kapenta->user->role) { return ''; }
	if (true == array_key_exists('pageNo', $args)) { $pageNo = (int)$args['pageNo']; }
	if (true == array_key_exists('num', $args)) { $num = (int)$args['num']; }
	if (true == array_key_exists('pagination', $args)) { $pagination = $args['pagination']; }

	if ((true == array_key_exists('objectType', $args)) && ('*' != $args['objectType'])) {
		if (false == $kapenta->db->tableExists($args['objectType'])) { return '(unknown object type)'; }
		$objectType = $args['objectType'];
	}

	//----------------------------------------------------------------------------------------------
	//	count matching objects and load a page of deleted objects from the database
	//----------------------------------------------------------------------------------------------
	$conditions = array();
	if ('*' != $objectType) { $conditions[] = "refModel='" . $kapenta->db->addMarkup($objectType) . "'"; }
	//TODO: add conditions here

	$totalItems = $kapenta->db->countRange('revisions_deleted', $conditions);
	$totalPages = ceil($totalItems / $num);

	$start = (($pageNo - 1) * $num);

	$range = $kapenta->db->loadRange('revisions_deleted', '*', $conditions, 'editedOn', $num, $start);

	//----------------------------------------------------------------------------------------------
	//	make the block
	//----------------------------------------------------------------------------------------------

	$table = array();
	$table[] = array('Module', 'Model', 'UID', 'Rs', 'Deleted');
	foreach($range as $row) {
		$typeUrl = '%%serverPath%%revisions/listdeleted/type_' . $row['refModel'];
		$typeLink = "<a href='" . $typeUrl . "'>" . $row['refModel'] . "</a>";

		$itemUrl = '%%serverPath%%revisions/showdeleted/' . $row['UID'];
		$itemLink = "<a href='" . $itemUrl . "'>" . $row['refUID'] . "</a>";

		$restored = 'no';
		if (true == $revisions->isDeleted($row['refModel'], $row['refUID'])) {
			$restored = "<span class='ajaxerror'>no</span>";
		} else {
			$restored = "<span class='ajaxmsg'>yes</span>";
		}

		$table[] = array($row['refModule'], $typeLink, $itemLink, $restored, $row['createdOn']);
	}

	$html .= $theme->arrayToHtmlTable($table, true, true);

	if ('yes' == $pagination) {
		$paginationBlock = ''
		 . "[[:theme::pagination::page=$pageNo::total=$totalPages::link=/revisions/listdeleted/:]]";

		$pageNav = $theme->expandBlocks($paginationBlock, '');
		$html = $pageNav . $html . $pageNav;
	}

	return $html;
}

?>
