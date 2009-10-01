<?

	require_once($installPath . 'modules/forums/models/forum.mod.php');
	require_once($installPath . 'modules/forums/models/forumreply.mod.php');
	require_once($installPath . 'modules/forums/models/forumthread.mod.php');

//--------------------------------------------------------------------------------------------------
//	return thumbnails of random forums images <--- NOT USED YET
//--------------------------------------------------------------------------------------------------
// * $args['page'] = page we're at
// * $args['num'] = maximum number of thumbs to show (most recent first) (optional)

function forums_recentthumbsall($args) {
	$page = 1; $num = 20; $size = 'thumb'; $html = '';
	if (array_key_exists('size', $args) == true) { $size = $args['size']; }
	if (array_key_exists('page', $args) == true) { $page = $args['page']; }
	if (array_key_exists('num', $args) == true) { $num = $args['num']; }

	//----------------------------------------------------------------------------------------------
	//	count total records owned by this module
	//----------------------------------------------------------------------------------------------

	$sql = "select count(UID) as numRecords from images where refModule='forums'";	
	$result = dbQuery($sql);
	$row = sqlRMArray(dbFetchAssoc($result));
	$total = ceil($row['numRecords'] / $num);

	//----------------------------------------------------------------------------------------------
	//	make thumbs of images on this page
	//----------------------------------------------------------------------------------------------

	$limit = "limit " . (($page - 1) * $num) . ", ". sqlMarkup($num);
	$sql = "select * from images where refModule='forums' order by createdOn DESC " . $limit;	

	$result = dbQuery($sql);
	while ($row = dbFetchAssoc($result)) {
		$row = sqlRMArray($row);
		$viewUrl = '%%serverPath%%forums/image/' . $row['recordAlias'];
		$thumbUrl = '%%serverPath%%images/' . $size . '/' . $row['recordAlias'];
		$html .= "<a href='" . $viewUrl . "'>"
			  . "<img src='" . $thumbUrl . "' title='" . $row['title']
			  . "' border='0' vspace='2px' hspace='2px' /></a>\n";

	}

	$link = '%%serverPath%%forums/superforums/';

	$pagination .= "[[:theme::pagination::page=" . sqlMarkup($page) 
				. "::total=" . $total . "::link=" . $link . ":]]\n";

	return $pagination . $html . $pagination;
}

//--------------------------------------------------------------------------------------------------

?>