<?

	require_once(dirname(__FILE__) . '/login.mod.php');
	require_once(dirname(__FILE__) . '/friendship.mod.php');
	require_once(dirname(__FILE__) . '/registry.mod.php');
	require_once(dirname(__FILE__) . '/settings.set.php');
	require_once(dirname(__FILE__) . '/../../schools/models/school.mod.php');
	require_once(dirname(__FILE__) . '/friendships.set.php');
	require_once(dirname(__FILE__) . '/permissions.set.php');

//--------------------------------------------------------------------------------------------------
//*	object to represent site users.  This module is required.
//--------------------------------------------------------------------------------------------------
//+
//+	TODO: rename $this->settings to $this->registry
//+	TODO: consider a better way for admins to decide on profile fields
//+	TODO: make profile expansion explicit, current method is wasteful, parses XML that is 
//+	almost never used.
//+	
//+	SETTINGS
//+
//+	Per-user regisry keys (key/value pairs) are stored in Users_Registry objects, accessed by the
//+	set() and get() methods respectively.  The Users_Settings collection object handles encoding
//+	and decoding of the individual values.
//+
//+	The UID of the Users_Registry object for a given user is stored in the 'settings' field
//+	of this object.
//+
//+	This set of values is lazily initialized - ie, it is only loaded from the database on the
//+	first get() call, or on an explicit loadSettings() call.

class Users_User {

	//----------------------------------------------------------------------------------------------
	//	member variables (as retieved from database)
	//----------------------------------------------------------------------------------------------

	var $data;				//_	currently loaded record [array]
	var $dbSchema;			//_	database structure [array]
	var $loaded = false;	//_	set to true when an object has been loaded [bool] 

	var $UID;				//_	UID
	var $role;				//_	title
	var $school;			//_	varchar(30)
	var $grade;				//_	varchar(30)
	var $firstname;			//_	varchar(100)
	var $surname;			//_	varchar(100)
	var $username;			//_	varchar(50)
	var $password;			//_	varchar(50)
	var $lang;				//_	varchar(30)
	var $profile;			//_	stored as XML in a text field [array]
	var $permissions;		//_	object: Users_Permissions [array]
	var $settings;			//_	ref: Users_Registry [string]
	var $lastOnline;		//_	datetime [string]
	var $createdOn;			//_	datetime [string]
	var $createdBy;			//_	ref:Users_User [string]
	var $editedOn;			//_	datetime [string]
	var $editedBy;			//_	ref:Users_User [string]
	var $alias;				//_	alias [string]

	var $registry;			//_	Registry keys collection object [object:Users_Settings]
	var $registryLoaded;	//_ Set to true when user registry scetion loaded [bool]

	//TODO: make this a registry setting
	var $profileFields = 'interests|hometown|music|goals|books|also|birthyear|tel|email';
	
	//----------------------------------------------------------------------------------------------
	//. constructor
	//----------------------------------------------------------------------------------------------
	//opt: raUID - UID or alias of a User object [string]
	//opt: byName - set to true to load by username and not raUID [string]

	function Users_User($raUID = '', $byName = false) {
		global $kapenta;

		$this->dbSchema = $this->getDbSchema();				//	initialise table schema
		$this->registry = new Users_Settings();				//	create user registry
		$this->permissions = new Users_Permissions();		//	create permission set		
		$this->friendships = new Users_Friendships();		//	this user's friends

		if ('public' == $raUID) { $raUID = ''; }			//	no user object
		if ('' != $raUID) {										// 	try load from the database
			if (false == $byName) { $this->load($raUID); }		//	.. by alias or UID
			if (true == $byName) { $this->loadByName($raUID); }	//	.. by username
		}							

		if (false == $this->loaded) {						// check if we did
			//note: we can't use $kapenta->db->makeBlank yet, it requires global user
			$this->UID = 'public';
			$this->role = 'public';							// set default role
			$this->firstname = 'public';
			$this->firstname = 'user';
			$this->createdOn = $kapenta->datetime();
			$this->createdBy = 'public';
			$this->editedOn = $kapenta->datetime();
			$this->editedBy = 'public';
		}
	}

	//----------------------------------------------------------------------------------------------
	//. load an object from the database given its UID or an alias
	//----------------------------------------------------------------------------------------------
	//arg: raUID - UID or alias of a User object [string]
	//returns: true on success, false on failure [bool]

	function load($raUID = '') {
		global $kapenta;
		$objary = $kapenta->db->loadAlias($raUID, $this->dbSchema);
		if ($objary != false) { $this->loadArray($objary); return true; }
		return false;
	}

	//----------------------------------------------------------------------------------------------
	//.	load a use given their username
	//----------------------------------------------------------------------------------------------
	//arg: username - username [string]
	//returns: true on success, false on failure [bool]

	function loadByName($username) {
		global $kapenta;
		$conditions = array("username='" . $kapenta->db->addMarkup($username) . "'");
		$range = $kapenta->db->loadRange('users_user', '*', $conditions);
		foreach($range as $objary) { return $this->loadArray($objary); }
		return false;
	}

	//----------------------------------------------------------------------------------------------
	//.	load an object serialized as an associative array
	//----------------------------------------------------------------------------------------------
	//arg: ary - associative array of fields and values [array]
	//returns: true on success, false on failure [bool]

	function loadArray($ary) {
		if (false == $ary) { return false; }
		$this->data = $ary; 
		$this->UID = $ary['UID'];
		$this->role = $ary['role'];
		$this->school = $ary['school'];
		$this->grade = $ary['grade'];
		$this->firstname = $ary['firstname'];
		$this->surname = $ary['surname'];
		$this->username = $ary['username'];
		$this->password = $ary['password'];
		$this->lang = $ary['lang'];
		$this->profile = $this->expandProfile($ary['profile']);
		$this->permissions->expand($ary['permissions']);
		$this->settings = $ary['settings'];
		$this->lastOnline = $ary['lastOnline'];
		$this->createdOn = $ary['createdOn'];
		$this->createdBy = $ary['createdBy'];
		$this->editedOn = $ary['editedOn'];
		$this->editedBy = $ary['editedBy'];
		$this->alias = $ary['alias'];

		$this->friendships->userUID = $this->UID;	//	make queires with this UID
		$this->friendships->loaded = false;			//	clear any existsing recordset

		$this->loaded = true;
		return true; 
	}

	//----------------------------------------------------------------------------------------------
	//. save the current object to database
	//----------------------------------------------------------------------------------------------
	//returns: null string on success, html report of errors on failure [string]
	//: $kapenta->db->save(...) will raise an object_updated event if successful

	function save() {
		global $kapenta;

		$report = $this->verify();
		if ('' != $report) { return $report; }
		$this->alias = $kapenta->aliases->create('users', 'users_user', $this->UID, $this->username);
		$check = $kapenta->db->save($this->toArray(), $this->dbSchema);
		if (false == $check) { return "Database error.<br/>\n"; }
		return '';
	}

	//----------------------------------------------------------------------------------------------
	//.	verify - check that an object is correct before allowing it to be stored in the database
	//----------------------------------------------------------------------------------------------
	//returns: null string if object passes, warning message if not [string]

	function verify() {
		global $kapenta;
		$report = '';

		//------------------------------------------------------------------------------------------
		//	check this object
		//------------------------------------------------------------------------------------------

		if (strlen($this->UID) < 5) 
			{ $report .= "UID not present.\n"; }

		if (strlen($this->firstname) < 1) 
			{ $report .= "Please enter the users first name.\n"; }

		if (strlen($this->surname) < 1) 
			{ $report .= "Please enter the users surname.\n"; }

		if (strlen($this->username) < 4) 
			{ $report .= "Please enter a username (4 characters or more).\n"; }

		if (strlen($this->password) < 6) 
			{ $report .= "Please enter a password (6 characters or more).\n"; }

		if (false == $kapenta->db->objectExists('schools_school', $this->school)) 
			{ $report .= "Please select a school for this user.\n"; }

		//------------------------------------------------------------------------------------------
		//	check that a user with this name does not already exist
		//------------------------------------------------------------------------------------------
		$extantUID = $this->getUserUID($this->username);
		if (('' != $extantUID) && ($this->UID != $extantUID)) {
			$report .= "The username '" . $this->username . "' is already taken by someone else.";
		}

		return $report;
	}

	//----------------------------------------------------------------------------------------------
	//. database table definition and content versioning
	//----------------------------------------------------------------------------------------------
	//returns: information for constructing SQL queries [array]

	function getDbSchema() {
		$dbSchema = array();
		$dbSchema['module'] = 'users';
		$dbSchema['model'] = 'users_user';
		$dbSchema['archive'] = 'yes';

		//table columns
		$dbSchema['fields'] = array(
			'UID' => 'VARCHAR(33)',
			'role' => 'VARCHAR(33)',
			'school' => 'VARCHAR(33)',
			'grade' => 'VARCHAR(30)',
			'firstname' => 'VARCHAR(100)',
			'surname' => 'VARCHAR(100)',
			'username' => 'VARCHAR(50)',
			'password' => 'VARCHAR(50)',
			'lang' => 'VARCHAR(30)',
			'profile' => 'TEXT',
			'permissions' => 'TEXT',
			'settings' => 'VARCHAR(33)',
			'lastOnline' => 'VARCHAR(255)',
			'createdOn' => 'DATETIME',
			'createdBy' => 'VARCHAR(33)',
			'editedOn' => 'DATETIME',
			'editedBy' => 'VARCHAR(33)',
			'alias' => 'VARCHAR(255)' );

		//these fields will be indexed
		$dbSchema['indices'] = array(
			'UID' => '10',
			'username' => '10',
			'createdOn' => '',
			'createdBy' => '10',
			'editedOn' => '',
			'editedBy' => '10',
			'alias' => '10' );

		//revision history will be kept for these fields
		$dbSchema['nodiff'] = array('UID', 'lastOnline');
		//TODO: add more nodiff fields

		return $dbSchema;
		
	}

	//----------------------------------------------------------------------------------------------
	//.	serialize this object to an array
	//----------------------------------------------------------------------------------------------
	//returns: associative array of all variables which define this instance [array]

	function toArray() {
		$serialize = array(
			'UID' => $this->UID,
			'role' => $this->role,
			'school' => $this->school,
			'grade' => $this->grade,
			'firstname' => $this->firstname,
			'surname' => $this->surname,
			'username' => $this->username,
			'password' => $this->password,
			'lang' => $this->lang,
			'profile' => $this->collapseProfile(),
			'permissions' => $this->permissions->collapse(),
			'settings' => $this->settings,
			'lastOnline' => $this->lastOnline,
			'createdOn' => $this->createdOn,
			'createdBy' => $this->createdBy,
			'editedOn' => $this->editedOn,
			'editedBy' => $this->editedBy,
			'alias' => $this->alias
		);
		return $serialize;
	}

	//----------------------------------------------------------------------------------------------
	//.	make an extended array of all data a view will need
	//----------------------------------------------------------------------------------------------
	//returns: extended array of member variables and metadata [array]

	function extArray() {
		global $kapenta;
		global $theme;
		global $utils;		// note that current user and $this may not be the same;

		$ary = $this->toArray();

		$ary['fullName'] = trim($ary['firstname'] . ' ' . $ary['surname']);

		$ary['editUrl'] = '';			$ary['editLink'] = '';
		$ary['editProfileUrl'] = '';	$ary['editProfileLink'] = '';
		$ary['viewUrl'] = '';			$ary['viewLink'] = '';
		$ary['delUrl'] = '';			$ary['delLink'] = '';
		$ary['newUrl'] = '';			$ary['newLink'] = '';

		//------------------------------------------------------------------------------------------
		//	links
		//------------------------------------------------------------------------------------------

		//if (false == $kapenta->user->authHas('users', 'users_user', 'show', '') == false) 
		//	{ echo "no permission to view users.<br/>"; }

		if ( (true == $kapenta->user->authHas('users', 'users_user', 'show', $this->UID)) 
			OR ($kapenta->user->UID == $this->UID)) { 
			$ary['viewUrl'] = '%%serverPath%%users/' . $ary['alias'];
			$ary['viewLink'] = "<a href='" . $ary['viewUrl'] . "'>[profile]</a>"; 
		}

		if ( (true == $kapenta->user->authHas('user', 'users_user', 'edit', $this->UID)) 
			OR ($kapenta->user->UID == $this->UID) ) {
			$ary['editUrl'] =  '%%serverPath%%users/edit/' . $this->alias;
			$ary['editLink'] = "<a href='" . $ary['editUrl'] . "'>[edit]</a>"; 
			$ary['editProfileUrl'] =  '%%serverPath%%users/editprofile/' . $this->alias;
			$ary['editProfileLink'] = "<a href='" . $ary['editProfileUrl'] . "'>[edit]</a>"; 
		}

		// to ponder - should users be able to delete their profile?
		if (true == $kapenta->user->authHas('users', 'users_user', 'delete', $this->UID)) { 
			$ary['delUrl'] =  '%%serverPath%%users/confirmdelete/UID_' . $this->UID . '/';
			$ary['delLink'] = "<a href='" . $ary['delUrl'] . "'>[delete]</a>"; 
		}
		
		if (true == $kapenta->user->authHas('users', 'users_user', 'new', $this->UID)) { 
			$ary['newUrl'] = "%%serverPath%%users/new/"; 
			$ary['newLink'] = "<a href='" . $ary['newUrl'] . "'>[new user]</a>"; 
		}

		//------------------------------------------------------------------------------------------
		//	chatlink- if logged in to awarenet (not necessarily on this peer)
		//------------------------------------------------------------------------------------------
		$block = '[[:users::chatlink::userUID='. $ary['UID'] .':]]';
		$ary['chatLink'] = $theme->expandBlocks($block, '');

		//------------------------------------------------------------------------------------------
		//	add profile fields
		//------------------------------------------------------------------------------------------

		foreach($this->profile as $field => $value) { 
			$value = str_replace("\n", "<br/>\n", $value);
			if (trim($value) == '') { $ary['profile_' . $field] = '';  }
			else { $ary['profile_' . $field] = "<p><b>" . $field . ":</b> " .  $value . "</p>\n"; }
		}

		//------------------------------------------------------------------------------------------
		//	look up school info
		//------------------------------------------------------------------------------------------
		$mySchool = new Schools_School($ary['school']);
		$ary['schoolName'] = '';
		$ary['schoolCountry'] = 'no school chosen';
		$ary['schoolRecordAlias'] = '';
		$ary['schoolUrl'] = '%%serverPath%%schools/';
		$ary['schoolLink'] = "<a href='" . $ary['schoolUrl'] . "'></a>";

		if (true == $mySchool->loaded) {
			$ary['schoolName'] = $mySchool->name;
			$ary['schoolCountry'] = $mySchool->country;
			$ary['schoolRecordAlias'] = $mySchool->alias;
			$ary['schoolUrl'] = '%%serverPath%%schools/' . $mySchool->alias;
			$ary['schoolLink'] = "<a href='" . $ary['schoolUrl'] . "'>" . $mySchool->name . "</a>";
		} 

		return $ary;
	}

	//----------------------------------------------------------------------------------------------
	//.	delete the current user
	//----------------------------------------------------------------------------------------------
	//: $kapenta->db->delete(...) will raise an object_deleted event on success [bool]
	//returns: true on success, false on failure [bool]

	function delete() {
		global $kapenta;
		if (false == $this->loaded) { return false; }		// nothing to do
		if (false == $kapenta->db->delete($this->UID, $this->dbSchema)) { return false; }
		return true;
	}

	//----------------------------------------------------------------------------------------------
	//.	get user UID given username
	//----------------------------------------------------------------------------------------------
	//arg: username - a kapenta username [string]
	//returns: UID if username exists, empty string if not [string]

	function getUserUID($username) {
		global $kapenta;
		$conditions = array("LOWER(username)='" . $kapenta->db->addMarkup(strtolower($username)) . "'");
		$range = $kapenta->db->loadRange('users_user', '*', $conditions);
		foreach($range as $item) { return $item['UID']; }
		return '';
	}

	//==============================================================================================
	//	PASSWORD
	//==============================================================================================

	//----------------------------------------------------------------------------------------------
	//.	discover if a password is vaid for this user
	//----------------------------------------------------------------------------------------------
	//arg: candidate - password to test [string]
	//returns: true on succes, false on failure [bool]

	function checkPassword($candidate) {
		if ($this->password == sha1($candidate . $this->UID)) { return true; }
		return false;
	}

	//----------------------------------------------------------------------------------------------
	//.	set user's password
	//----------------------------------------------------------------------------------------------
	//arg: password - new password [string]
	//returns: true on success, false on failure [bool]

	function setPassword($password) {
		//TODO: this, make it check for length and common passwords
	}

	//==============================================================================================
	//	AUTHENTICATION AND PERMISSIONS
	//==============================================================================================

	//----------------------------------------------------------------------------------------------
	//.	check if the current user has a permission on a specified object
	//----------------------------------------------------------------------------------------------
	//arg: module - name of a kapenta module [string]
	//arg: model - object class [string]
	//arg: permission - name of permission sought [string]
	//opt: UID - specific object on which permission is sought (if any) [string]
	//returns: true if the user is authorized, otherwise false [bool]

	function authHas($module, $model, $permission, $UID = '') {
		global $kapenta;
		global $role;
		global $kapenta;

		if ('admin' == $this->role) { return true; }			//	admins have all permissions
		if (false == $role->loaded) { return false; }			//	no such role
		$model = strtolower($model);							//	fixes some calls

		$kapenta->page->logDebugItem('auth', "Checking: $module - $model - $permission - $UID <br/>\n");

		//------------------------------------------------------------------------------------------
		//	check role permisisons first
		//------------------------------------------------------------------------------------------
		if (true == array_key_exists($module, $role->permissions->members)) {
			foreach($role->permissions->members[$module] as $p) {
				if (($model == strtolower($p['model'])) && ($permission == $p['permission'])) {

					if ('p' == $p['type']) {
						//--------------------------------------------------------------------------
						//	user has permission on all objects of this type
						//--------------------------------------------------------------------------
						$kapenta->page->logDebugItem('auth', "Role has blanket permission.<br/>\n");
						return true; 		
					}

					if ('c' == $p['type']) {
						//--------------------------------------------------------------------------
						// user has permission to perform this action is a condition is met
						//--------------------------------------------------------------------------
						$met = $kapenta->relationshipExists(
							$module,	
							$model,
							$UID,
							$p['condition'],
							$this->UID
						);

						if (true == $met) {
							$kapenta->page->logDebugItem('auth', "Condition met: ". $p['condition'] ."<br/>\n");
							return true;
						}
					}
				}
			}
		}

		$kapenta->page->logDebugItem('auth', "Role does not support permission.<br/>\n");

		//------------------------------------------------------------------------------------------
		//	if role does not authorize action, try user-specific permissions
		//------------------------------------------------------------------------------------------
		if (true == array_key_exists($module, $this->permissions->members)) {
			foreach($this->permissions->members[$module] as $p) {
				if (($model == $p['model']) && ($permission == $p['permission'])) {
					if ('p' == $p['type']) { return true; }		// has blanket permission
					if ('c' == $p['type']) {
						//--------------------------------------------------------------------------
						// user has permission to perform this action is a condition is met
						//--------------------------------------------------------------------------
						$met = $kapenta->relationshipExists(
							$module,	
							$model,
							$UID,
							$p['condition'],
							$this->UID
						);

						if (true == $met) { return true; }
					}
				}
			}
		} 

		//------------------------------------------------------------------------------------------
		//	at this point to policy has been shown to authorize this user to permform this action
		//------------------------------------------------------------------------------------------
		return false;
	}

	//==============================================================================================
	//	USER PROFILES
	//==============================================================================================

	//----------------------------------------------------------------------------------------------
	//.	create blank profile - for new records
	//----------------------------------------------------------------------------------------------

	function initProfile() {
		$pF = explode('|', $this->profileFields);
		$this->profile = array();
		foreach($pF as $field) {
			$this->profile[$field] = '';
		}
	}

	//----------------------------------------------------------------------------------------------
	//.	expand profile  //TODO: fix this, security checks
	//----------------------------------------------------------------------------------------------
	//arg: xml - raw XML containing profile [string]
	//returns: hash table of profile fields and values [array]

	function expandProfile($xml) {
		global $session;
		// initialize all fields blank
		$profile = array();
		$pF = explode('|', $this->profileFields);
		foreach($pF as $profileField) { $profile[$profileField] = ''; }

		$doc = new KXMLDocument($xml);	//%	parse XML [class:KXMLDocument]
		$keys = $doc->getChildren2d();	//%	all children of root node [array]

		if (false == $keys) { return $profile; }

		foreach($keys as $field => $value) 
			{ if (true == array_key_exists($field, $profile)) { $profile[$field] = $value;  } }

		return $profile;
	}

	//----------------------------------------------------------------------------------------------
	//.	collapse profile
	//----------------------------------------------------------------------------------------------

	function collapseProfile() {
		$xml = '';

		if (false == is_array($this->profile)) 
			{ $this->profile = $this->expandProfile($this->profile); }

		foreach($this->profile as $field => $value) 
			{ $xml .= "<" . $field . ">" . strip_tags($value) . "</" . $field . ">\n"; }

		$xml = "<profile>\n$xml</profile>\n";
		return $xml;
	}


	//==============================================================================================
	//	SETTINGS / USER REGISTRY
	//==============================================================================================
	
	//----------------------------------------------------------------------------------------------
	//.	load user registry settings into memory
	//----------------------------------------------------------------------------------------------
	//returns: true on success, false on failure [bool]

	function loadRegistry() {
		global $kapenta;
		global $session;

		if (false == $this->loaded) { return false; }

		//------------------------------------------------------------------------------------------
		//	create a new user registry section if one does not exist for this user
		//------------------------------------------------------------------------------------------
		if ('' == $this->settings) {
			$this->settings = $kapenta->createUID();
			$report = $this->save();
			if ('' != $report) { return false; }
		}

		//------------------------------------------------------------------------------------------
		//	(re)create the object is not present
		//------------------------------------------------------------------------------------------
		if (false == $kapenta->db->objectExists('users_registry', $this->settings)) {
			$model = new Users_Registry();
			$model->UID = $this->settings;
			$model->userUID = $this->UID;
			$report = $model->save();
			if ('' != $report) { 
				$kapenta->session->msg("Could not create user registry " . $this->settings . ".");
				//echo "Could not create user registry " . $this->settings . "<br/>\n";
				return false;
			} else {
				$kapenta->session->msg("Created new user registry " . $this->settings . ".");
				//echo "Created new user registry " . $this->settings . "<br/>\n";	
			}
		}

		//------------------------------------------------------------------------------------------
		//	try to load the Users_Registry object
		//------------------------------------------------------------------------------------------
		$model = new Users_Registry($this->settings);
		if (false == $model->loaded) { return false; }

		$this->registry = new Users_Settings($model->settings);
		$this->registryLoaded = true;
		return true;
	}

	//----------------------------------------------------------------------------------------------
	//.	create / update a user setting
	//----------------------------------------------------------------------------------------------
	//arg: key - name of a registry key in the user section [string]
	//arg: value - valuse to set this to [string]
	//returns: true on success, false on failure [bool]	

	function set($key, $value) {
		if (false == $this->registryLoaded) { $this->loadRegistry(); }
		if (false == $this->registryLoaded) { return false; }

		$model = new Users_Registry($this->settings);
		if (false == $model->loaded) { return false; }
		$this->registry->set($key, $value);

		$model->settings = $this->registry->toString();
		$report = $model->save();
		if ('' != $report) { return false; }
		return true;
	}

	//----------------------------------------------------------------------------------------------
	//.	read a user setting
	//----------------------------------------------------------------------------------------------
	//arg: key - name of a registry key in the user section [string]
	//returns: value of the key, or empty string if it does not exist [string]

	function get($key) {
		if (false == $this->registryLoaded) { $this->loadRegistry(); }
		if (false == $this->registryLoaded) { return ''; }
		return $this->registry->get($key);	
	}

	//==============================================================================================
	//	TODO: remove this
	//==============================================================================================
	//----------------------------------------------------------------------------------------------
	//.	find users in the same grade as this user  //TODO: $kapenta->db->loadRange
	//----------------------------------------------------------------------------------------------
	//returns: array of UID => row for loadArray [array]

	function sameGrade() {
		global $kapenta;
		$conditions = array();
		$conditions[] = "school='" . $kapenta->db->addMarkup($this->school) . "'";
		$conditions[] = "grade='" . $kapenta->db->addMarkup($this->grade) . "'";
		$range = $kapenta->db->loadRange('users_user', '*', $conditions, 'surname, firstname');
		return $range;
	}

	//==============================================================================================
	//	TOSTRING METHODS
	//==============================================================================================

	//----------------------------------------------------------------------------------------------
	//.	get the current user's name
	//----------------------------------------------------------------------------------------------
	//returns: firstname and surname [string]

	function getName() { return trim($this->firstname . ' ' . $this->surname);	}

	//----------------------------------------------------------------------------------------------
	//.	get the current user's profile URL
	//----------------------------------------------------------------------------------------------
	//returns: absolute URL [string]

	function getUrl() { return "%%serverPath%%users/profile/". $this->alias; }

	//----------------------------------------------------------------------------------------------
	//.	make a link to the current user's profile
	//----------------------------------------------------------------------------------------------
	//returns: HTML anchor tag [string]

	function getNameLink() { return "<a href='". $this->getUrl() ."'>". $this->getName() ."</a>"; }

	//----------------------------------------------------------------------------------------------
	//.	get the name of the corrent user's school
	//----------------------------------------------------------------------------------------------
	//returns: name of user's school [string]

	function getSchoolName() { 
		global $theme;
		$schoolNameBlock = '[[:schools::name::schoolUID=' . $this->school . ':]]';
		$schoolName = $theme->expandBlocks($schoolNameBlock, '');
		return $schoolName;	
	}

}

?>
