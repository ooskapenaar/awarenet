<?

	require_once($kapenta->installPath . 'core/krss.class.php');

//--------------------------------------------------------------------------------------------------
//*	make an Atom feed of moblog posts
//--------------------------------------------------------------------------------------------------
//+	TODO: registry keys and settings to select max posts to show in feed


	$userUID = '';			//%	UID of a Users_User object if showing a single blog [string]
	$numItems = 10;			//%	default number of items in feed [int]

	$chanTitle = $kapenta->websiteName . ' - Blog (all)';
	$chanDesc = "Aggregated feed for all authors on " . $kapenta->websiteName;	
	$chanLink = $kapenta->serverPath . 'moblog/';

	//----------------------------------------------------------------------------------------------
	//	check reference and permissions
	//----------------------------------------------------------------------------------------------
	if ('' != $kapenta->request->ref) {
		$model = new Users_User($kapenta->request->ref);
		if (true == $model->loaded) { $userUID = $model->UID; }
	}

	if (false == $kapenta->user->authHas('moblog', 'moblog_post', 'show')) { $kapenta->page->do403(); }

	//----------------------------------------------------------------------------------------------
	//	query database
	//----------------------------------------------------------------------------------------------

	$conditions = array();
	$conditions[] = "published='yes'";
	if ('' != $userUID) { $conditions[] = "createdBy='$userUID'"; }

	$range = $kapenta->db->loadRange('moblog_post', '*', $conditions, 'createdOn DESC', $numItems);

	//----------------------------------------------------------------------------------------------
	//	render the feed
	//----------------------------------------------------------------------------------------------

	$feed = new KRSS($chanTitle, $chanDesc, $chanLink);

	foreach($range as $item) {
		$author = $theme->expandBlocks('[[:users::name::userUID=' . $item['createdBy'] . ':]]', '');
		$link = $kapenta->serverPath . 'moblog/show/' . $item['alias'];
		$feed->add($item['title'], $author, $item['content'], $link, $item['editedOn']);
	}

	header("Content-type: application/atom+xml");
	echo $feed->toAtom();

?>
