<?

//--------------------------------------------------------------------------------------------------
//	object for managing user notifications
//--------------------------------------------------------------------------------------------------
//	All users have a notification queue. Notifications are equivalent to events on a facebook, when
//	something happens a notification is generated and added to the queue of all relevant parties, 
//	say an image is uploaded by a user, that users friends recieve a notification, if a club makes
//	an announcement, club members recieve a notification, if the school makes an announcement, 
//	everyone gets a notification.  Notifications are generated by other modules and pushed to 
//	users queues as an xml snippet.	 By default the notice queue is 100 items long.
//
//	Notifications are identified by a UID, which may differ slightly in form from record UIDS
//	but must still be unique.  This UID is to prevent the same notice from appearing multiple times
//	in a queue, say if a notice was sent to a users soccer team, friends and class, all of which
//	may overlap.
//
//	Notifications are added by special core functions:
//
// 	notifyUser('userUID', 'noticeUID', 'from', 'fromurl', 'title', 'content', 'url')
// 	notifySchool('schoolUID', 'noticeUID', 'from', 'fromurl', 'title', 'content', 'url');
// 	notifyGroup('groupUID', 'noticeUID', 'from', 'fromurl', 'title', 'content', 'url');
//	etc...
//
//	Notices have the form:
//  <notification>
//    <user>231095871</user>
//    <UID>231095871</UID>
//    <from>James Joyce (chess club)</from>
//    <fromurl>http://www.awarenet.co.za/users/JamesJoyce</fromurl>
//	  <title>Match against St Marys High</title>
//	  <content>Tomorrow we will be travelling....</content>
//    <url>http://www.awarenet.co.za/groups/ChessClub#announcement56345634</url>
//    <timestamp>23423409</timestamp>
//  </notification>

class NotificationQueue {

	//----------------------------------------------------------------------------------------------
	//	member variables (as retieved from database)
	//----------------------------------------------------------------------------------------------

	var $data;				// currently loaded record
	var $dbSchema;			// database structure
	var $notifications;		// array of notifications, ordered by timestamp

	var $noticeFields = 'user|UID|from|fromurl|title|content|url|imgUID|timestamp';

	//----------------------------------------------------------------------------------------------
	//	constructor
	//----------------------------------------------------------------------------------------------

	function NotificationQueue($userUID = '') {
		$this->dbSchema = $this->initDbSchema();
		$this->data = dbBlank($this->dbSchema);
		$this->notifications = array();
		if ($userUID != '') { $this->load($userUID); }
	}

	//----------------------------------------------------------------------------------------------
	//	load a record by userUID, create a notification queue if one does not exist
	//----------------------------------------------------------------------------------------------

	function load($userUID) {
		if (dbRecordExists('users', $userUID) == false) { return false; }
		$sql = "select * from notices where user='" . sqlMarkup($userUID) . "'";
		$result = dbQuery($sql);
		
		if (dbNumRows($result) == 0) {
			$this->data['UID'] = createUID();
			$this->data['user'] = $userUID;
			$this->data['notices'] = '';
			$this->save();
		} else {
			$this->data = sqlRMArray(dbFetchAssoc($result));
			$this->expandNotifications();
		}
		return true;
	}

	function loadArray($ary) { $this->data = $ary; }

	//----------------------------------------------------------------------------------------------
	//	save a record
	//----------------------------------------------------------------------------------------------

	function save() {
		$verify = $this->verify();
		if ($verify != '') { return $verify; }
		$this->collapseNotifications();
		dbSave($this->data, $this->dbSchema); 
	}

	//----------------------------------------------------------------------------------------------
	//	verify - check that a record is correct before allowing it to be stored in the database
	//----------------------------------------------------------------------------------------------
	//	nothing to check as yet

	function verify() { return ''; }

	//----------------------------------------------------------------------------------------------
	//	sql information
	//----------------------------------------------------------------------------------------------

	function initDbSchema() {
		$dbSchema = array();
		$dbSchema['table'] = 'notices';
		$dbSchema['fields'] = array(
			'UID' => 'VARCHAR(30)',	
			'user' => 'VARCHAR(30)',	
			'notices' => 'TEXT' );

		$dbSchema['indices'] = array('UID' => '10', 'user' => '20');
		$dbSchema['nodiff'] = array('UID', 'notices');
		return $dbSchema;
	}

	//----------------------------------------------------------------------------------------------
	//	return the data
	//----------------------------------------------------------------------------------------------

	function toArray() { return $this->data; }

	//----------------------------------------------------------------------------------------------
	//	make array of notifications, ordered by time
	//----------------------------------------------------------------------------------------------

	function extArray() {
		// TODO
	}

	//----------------------------------------------------------------------------------------------
	//	install this module
	//----------------------------------------------------------------------------------------------

	function install() {
		$report = "<h3>Installing Notices Module</h3>\n";

		//------------------------------------------------------------------------------------------
		//	create notices table if it does not exist
		//------------------------------------------------------------------------------------------

		if (dbTableExists('notices') == false) {	
			echo "installing notices module\n";
			dbCreateTable($this->dbSchema);	
			$this->report .= 'created notices table and indices...<br/>';
		} else {
			$this->report .= 'notices table already exists...<br/>';	
		}

		return $report;
	}
	
	//----------------------------------------------------------------------------------------------
	//	delete a record
	//----------------------------------------------------------------------------------------------

	function delete() {		
		dbDelete('notices', $this->data['UID']);
	}

	//----------------------------------------------------------------------------------------------
	//	expand notifications (from XML into array)
	//----------------------------------------------------------------------------------------------

	function expandNotifications() {
		$this->notifications = array();
		$nF = explode('|', $this->noticeFields);
		$xe = new XmlEntity($this->data['notices']);
		foreach($xe->children as $index => $notice) {
			$newNotice = array();
			foreach($nF as $field) 
				{ $newNotice[$field] = base64_decode($notice->getFirst($field)); }
			$this->notifications[$newNotice['UID']] = $newNotice;
		}
	}

	//----------------------------------------------------------------------------------------------
	//	collapse notifications
	//----------------------------------------------------------------------------------------------

	function collapseNotifications() {
		$nF = explode('|', $this->noticeFields);
		$xml = "<notificationqueue>\n";
		foreach($this->notifications as $notice) {
			$xml .= "\t<notification>\n";
			foreach($nF as $fd) 
				{ $xml .= "\t\t<" . $fd . ">" . base64_encode($notice[$fd]) . "</" . $fd . ">\n"; }

			$xml .= "\t</notification>\n";
		}
		$xml .= "</notificationqueue>\n";
		$this->data['notices'] = $xml;	
	}

	//----------------------------------------------------------------------------------------------
	//	add a notification, if it is not already in queue
	//----------------------------------------------------------------------------------------------

	function addNotification($noticeUID, $from, $fromurl, $title, $content, $url, $imgUID) {
		if (array_key_exists($noticeUID, $this->notifications) == true) { return false; }
		$newNotice = array();
		$newNotice['user'] = $this->data['user'];
		$newNotice['UID'] = $noticeUID;
		$newNotice['from'] = $from;
		$newNotice['fromurl'] = $fromurl;
		$newNotice['title'] = $title;
		$newNotice['content'] = $content;
		$newNotice['url'] = $url;
		$newNotice['imgUID'] = $imgUID;
		$newNotice['timestamp'] = time();
		$this->notifications[$newNotice['UID']] = $newNotice;
		$this->sort();
		$this->save();
		return true;
	}

	//----------------------------------------------------------------------------------------------
	//	sort notifications by timestamp
	//----------------------------------------------------------------------------------------------

	function sort() {
		$twoD = array();
		$notifications = $this->notifications;
		foreach($notifications as $key => $notice) { 
					$twoD[$key] = $notice['timestamp']; 
		}
		asort($twoD);
		$this->notifications = array();
		foreach($twoD as $key => $timestamp) {
			$this->notifications[$key] = $notifications[$key];
		}
	}

}

?>
