<?

	require_once($kapenta->installPath . 'modules/projects/models/project.mod.php');

//--------------------------------------------------------------------------------------------------
//|	summary list or recent projects order by 'editedOn' date
//--------------------------------------------------------------------------------------------------
//opt: page - page no to display, default is 1 (int) [string]
//opt: pageNo - overrides page (int) [string]
//opt: num - number of records per page (default is 30) [string]
//opt: pagination - set to 'no' to disable page nav bar (yes|no) [string]
//opt: status - show only projects with the given status (open|locked|closed) [string]

function projects_summarylist($args) {
	global $kapenta;
	global $kapenta;
	global $kapenta;
	global $theme;
	global $kapenta;
	global $session;

	$start = 0;					//%	index of first item in result set [int]
	$num = 5;					//%	number of items per page [int]
	$pageNo = 1;				//%	results page to display [int]
	$html = '';					//%	return value [string]
	$pagination = 'yes';		//%	show pagination (yes|no) [string]
	$orderBy = 'editedOn';		//%	list order on this field [string]
	$status = '';				//%	constrain to status, default is active && locked [string]

	//----------------------------------------------------------------------------------------------
	//	check arguments and permissions
	//----------------------------------------------------------------------------------------------
	if (false == $kapenta->user->authHas('projects', 'projects_project', 'show')) { return ''; }

	if (true == array_key_exists('num', $args)) { $num = (int)$args['num']; }
	if (true == array_key_exists('pagination', $args)) { $pagination = $args['pagination']; }
	if (true == array_key_exists('pageNo', $args)) { $args['page'] = $args['pageNo']; }
	if (true == array_key_exists('page', $args)) { 
		$pageNo = $args['page']; 
		$start = ($pageNo - 1) * $num; 
	}

	if (true == array_key_exists('status', $args)) {
		$status = $args['status'];
	}

	//----------------------------------------------------------------------------------------------
	//	count visible projects
	//----------------------------------------------------------------------------------------------
	$conditions = array();
	if ('closed' == $status) { $conditions[] = "(status='closed' OR status='')"; }
	if ('notclosed' == $status) { $conditions[] = "(status='open' OR status='locked')"; }

	$totalItems = $kapenta->db->countRange('projects_project', $conditions);
	$totalPages = ceil($totalItems / $num);

	$link = '%%serverPath%%projects/';
	$pagination = "[[:theme::pagination::page=" . $kapenta->db->addMarkup($pageNo) 
				. "::total=" . $totalPages . "::link=" . $link . ":]]\n";

	//----------------------------------------------------------------------------------------------
	//	load a page worth of objects from the database
	//----------------------------------------------------------------------------------------------
	$range = $kapenta->db->loadRange('projects_project', '*', $conditions, 'editedOn DESC', $num, $start);

	//$block = $theme->loadBlock('modules/projects/views/summary.block.php');

	foreach($range as $UID => $row) {
		//$model = new Projects_Project();
		//$model->loadArray($row);
		//$labels = $model->extArray();

		$html .= "[[:projects::summary::projectUID=" . $row['UID'] . ":]]";

		//$html .= $theme->replaceLabels($labels, $block);
	}

	if (($start + $num) > $totalItems) { $html .= "<!-- end of results -->"; }

	if ('yes' == $pagination) { $html = $pagination . $html . $pagination; }

	return $html;
}

//--------------------------------------------------------------------------------------------------

?>
