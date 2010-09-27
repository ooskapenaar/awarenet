<?

//--------------------------------------------------------------------------------------------------
//*	object for storing wiki revisions
//--------------------------------------------------------------------------------------------------

class Wiki_Revision {

	//----------------------------------------------------------------------------------------------
	//	member variables (as retieved from database)
	//----------------------------------------------------------------------------------------------

	var $data;				//_	currently loaded database record [array]
	var $dbSchema;			//_	database table definition [array]
	var $loaded;			//_	set to true when an object has been loaded [bool]

	var $UID;				//_ UID [string]
	var $articleUID;		//_ varchar(33) [string]
	var $title;				//_ title [string]
	var $content;			//_ text [string]
	var $nav;				//_ text [string]
	var $locked;			//_ varchar(30) [string]
	var $namespace;			//_ title [string]
	var $createdOn;			//_ datetime [string]
	var $createdBy;			//_ ref:Users_User [string]
	var $editedOn;			//_ datetime [string]
	var $editedBy;			//_ ref:Users_User [string]

	var $allRevisions;		// php enclosed wikicode [array]


	//----------------------------------------------------------------------------------------------
	//. constructor
	//----------------------------------------------------------------------------------------------
	//opt: UID - UID of a Revision object [string]

	function Wiki_Revision($UID = '') {
		global $db;
		$this->allRevisions = array();
		$this->dbSchema = $this->getDbSchema();				// initialise table schema
		if ('' != $UID) { $this->load($UID); }				// try load an object from the database
		if (false == $this->loaded) {						// check if we did
			$this->data = $db->makeBlank($this->dbSchema);	// make new object
			$this->loadArray($this->data);					// initialize
			$this->namespace = 'New Revision ' . $this->UID;		// set default namespace
			$this->loaded = false;
		}
	}

	//----------------------------------------------------------------------------------------------
	//. load an object from the db given its UID
	//----------------------------------------------------------------------------------------------
	//arg: UID - UID of a Revision object [string]
	//returns: true on success, false on failure [bool]

	function load($UID = '') {
		global $db;
		$objary = $db->load($UID, $this->dbSchema);
		if ($objary != false) { $this->loadArray($objary); return true; }
		return false;
	}

	//----------------------------------------------------------------------------------------------
	//. load Revision object serialized as an associative array
	//----------------------------------------------------------------------------------------------
	//arg: ary - associative array of members and values [array]
	//returns: true on success, false on failure [bool]

	function loadArray($ary) {
		global $db;
		if (false == $db->validate($ary, $this->dbSchema)) { return false; }
		$this->UID = $ary['UID'];
		$this->articleUID = $ary['articleUID'];
		$this->title = $ary['title'];
		$this->content = $ary['content'];
		$this->nav = $ary['nav'];
		$this->reason = $ary['reason'];
		$this->locked = $ary['locked'];
		$this->namespace = $ary['namespace'];
		$this->createdOn = $ary['createdOn'];
		$this->createdBy = $ary['createdBy'];
		$this->editedOn = $ary['editedOn'];
		$this->editedBy = $ary['editedBy'];
		$this->loaded = true;
		return true;
	}

	//----------------------------------------------------------------------------------------------
	//. save the current object to database
	//----------------------------------------------------------------------------------------------
	//returns: null string on success, html report of errors on failure [string]
	//: $db->save(...) will raise an object_updated event if successful

	function save() {
		global $db, $aliases;
		$report = $this->verify();
		if ('' != $report) { return $report; }
		$check = $db->save($this->toArray(), $this->dbSchema);
		if (false == $check) { return "Database error.<br/>\n"; }
		return '';
	}

	//----------------------------------------------------------------------------------------------
	//. check that object is correct before allowing it to be stored in database
	//----------------------------------------------------------------------------------------------
	//returns: null string if object passes, warning message if not [string]

	function verify() {
		$report = '';
		if ('' == $this->UID) { $report .= "No UID.<br/>\n"; }
		return $report;
	}

	//----------------------------------------------------------------------------------------------
	//. database table definition and content versioning
	//----------------------------------------------------------------------------------------------
	//returns: information for constructing SQL queries [array]

	function getDbSchema() {
		$dbSchema = array();
		$dbSchema['module'] = 'wiki';
		$dbSchema['model'] = 'Wiki_Revision';
		$dbSchema['archive'] = 'no';


		//table columns
		$dbSchema['fields'] = array(
			'UID' => 'VARCHAR(33)',
			'title' => 'VARCHAR(255)',
			'content' => 'TEXT',
			'nav' => 'VARCHAR(255)',
			'locked' => 'VARCHAR(30)',
			'namespace' => 'VARCHAR(255)',
			'articleUID' => 'VARCHAR(33)',// changed from TEXT
			'reason' => 'TEXT',
			'createdOn' => 'DATETIME',
			'createdBy' => 'VARCHAR(33)',
			'editedOn' => 'DATETIME',
			'editedBy' => 'VARCHAR(33)' );

		//these fields will be indexed
		$dbSchema['indices'] = array(
			'UID' => '10',
			'createdOn' => '',
			'createdBy' => '10',
			'editedOn' => '',
			'editedBy' => '10' );

		//revision history will be kept for these fields
		$dbSchema['nodiff'] = array();

		return $dbSchema;		
	}

	//----------------------------------------------------------------------------------------------
	//. serialize this object to an array
	//----------------------------------------------------------------------------------------------
	//returns: associative array of all members which define this instance [array]

	function toArray() {
		$serialize = array(
			'UID' => $this->UID,
			'title' => $this->title,
			'content' => $this->content,
			'nav' => $this->nav,
			'locked' => $this->locked,
			'namespace' => $this->namespace,
			'articleUID' => $this->articleUID,
			'reason' => $this->reason,
			'createdOn' => $this->createdOn,
			'createdBy' => $this->createdBy,
			'editedOn' => $this->editedOn,
			'editedBy' => $this->editedBy
		);
		return $serialize;
	}

	//----------------------------------------------------------------------------------------------
	//.	make an extended array of all data a view will need
	//----------------------------------------------------------------------------------------------
	//returns: extended array of member variables and metadata [array]

	function extArray() {
		$ary = $this->data;
		$ary['viewUrl'] = '';	$ary['viewLink'] = '';	// view

		//------------------------------------------------------------------------------------------
		//	links
		//------------------------------------------------------------------------------------------
		if ($user->authHas('wiki', 'Wiki_Article', 'show', $this->UID)) { 
			$ary['viewUrl'] = '%%serverPath%%wiki/' . $ary['alias'];
			$ary['viewLink'] = "<a href='%%serverPath%%wiki/" . $ary['alias'] . "'>"
					 . "[read on &gt;&gt;]</a>"; 
		}

		//------------------------------------------------------------------------------------------
		//	strandardise date format to previous website
		//------------------------------------------------------------------------------------------
		$ary['editedOnLong'] = date('jS F, Y', strtotime($ary['editedOn']));

		//------------------------------------------------------------------------------------------
		//	done
		//------------------------------------------------------------------------------------------		
		return $ary;
	}

	//----------------------------------------------------------------------------------------------
	//. delete current object from the database
	//----------------------------------------------------------------------------------------------
	//: $db->delete(...) will raise an object_deleted event on success [bool]
	//returns: true on success, false on failure [bool]

	function delete() {
		global $db;
		if (false == $this->loaded) { return false; }		// nothing to do
		if (false == $db->delete($this->UID, $this->dbSchema)) { return false; }
		return true;
	}

	//----------------------------------------------------------------------------------------------
	//.	find UID of previous version of this wiki article or talk page
	//----------------------------------------------------------------------------------------------
	//returns: UID of previous revision, false if not found [string][bool]

	function getPreviousUID() {
		foreach($this->allRevisions as $key => $row) {	// for each revision
			if ($row['UID'] == $this->UID) {	// if this one is found

				if ($key > 0) {
					$prev = $this->allRevisions[($key - 1)];
					return $prev['UID'];
				} else {
					return false;	// this is the first revision
				}
			}
		}
	}

	//----------------------------------------------------------------------------------------------
	//.	find UID of next version of this wiki article or talk page
	//----------------------------------------------------------------------------------------------
	//returns: UID of next revision, false if not found [string][bool]

	function getNextUID() {
		foreach($this->allRevisions as $key => $row) {	// for each revision
			if ($row['UID'] == $this->UID) {	// if this one is found

				if ($key != (count($this->allRevisions) - 1)) {
					$next = $this->allRevisions[($key + 1)];
					return $next['UID'];
				} else {
					return false;	// this is the latest revision
				}

			}
		}
	}

	//----------------------------------------------------------------------------------------------
	//.	find all revisions to this wiki article or talk page, make list at $this->allRevisions
	//----------------------------------------------------------------------------------------------

	function getAllRevisions() {
	global $db;

		if ('' == $this->refUID) { return false; }
		$this->allRevisions = array();

	$conditions = array();
	$conditions[] = "articleUID='" . $db->addMarkup($this->articleUID) . "' AND '". $db->addMarkup($this->type) ."' ";
	$this->allRevisions[] = $db->loadRange('Wiki_Revision', 'UID, articleUID, type, reason, editedBy, editedOn', $conditions, 'editedOn DESC');
/*	
		//TODO: use $db->loadRange
		$sql = "select UID, articleUID, type, reason, editedBy, editedOn from Wiki_Revision "
			 . "where articleUID='" . $this->articleUID . "' "
			 . "and type='" . $this->type . "' "
			 . "order by editedOn";	// least to most recent  - TODO $db->loadRange(...)

		$result = $db->query($sql);
		while ($row = $db->fetchAssoc($result)) { $this->allRevisions[] = $db->rmArray($row); }
*/
		return true;
	}

}

?>
