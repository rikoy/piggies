<?php

######################################################################################
# Function: loginPage(...)
# Arguments: <none>
# Purpose: Displays the login form when required
######################################################################################
function loginPage($lastUser = NULL, $status = NULL, &$auth = NULL) {

	$pageTitle = "Login!";
	$content = "content/login.php";
	require_once("template.php");
	
}

##########################################################################################
# Class: PearAuthObj
# Modified:
#   - [06.07.2007 :: Keith]  Created.
#	- [10.05.2007 :: Keith]  Moved under a general AuthObj and controlled by interface
#		to provide a more hot-switchable style of object.  This greatly reduced
#		redundancy in the constructor and made swithcing Auth Protocols as simple as 
#		doing a find replace on PearAuthObj (not ideal, but things were already live
#		with PearAuthObj so I didn't want to break it.  It's better than nothing).
#	- [10.11.2007 :: Keith]  Added parameters array for constructor allowing for 
#		more customization within the PEAR::Auth Object.
# Purpose: This class contains methods that are used to control Authentication.
##########################################################################################

class PearAuthObj extends AuthObj implements AuthInterface {
	
	######################################################################################
	# Members
	######################################################################################
	public $a;			// Internal reference to Auth object

	######################################################################################
	# Method: create(...)
	# Arguments:
	#	- $scope (string) :: Name of the authentication scope
	# Purpose: Constructs an auth object using the given scope
	######################################################################################
	public function __construct($namespace,$scope = "PHPSESSID",$params=array()) {
		
		parent::__construct($namespace);
		
		/* Create parameters array */
		$params = array
		(
			"dsn"		  =>	$this->c("DBPROT") . "://" . $this->c("DBUSER") . ":" . 
								$this->c("DBPASS") . "@" . $this->c("DBHOST") . "/" . 
								$this->c("DBNAME"),
			"table"		  =>	$this->namespace . "users",
			"db_fields"	  =>	array("userID","userGroupID","userFirstName", 
									"userLastName"),
			"sessionName" =>	$scope
		);
		
		/* Create Auth object */
		$this->a = new Auth("DB",$params,"loginPage");
		$this->a->setSessionName($scope); // Just to be sure.. set it again!
	
	}
	
	######################################################################################
	# Method: start(...)
	# Arguments:
	#   - $login (bool) :: sets whether a login form will be shown
	# Purpose: Starts authentication services
	######################################################################################
	public function start($login=true) {
		
		/* Start authentication services */
		$this->a->setAllowLogin($login);
		$this->a->start();
		
	}
	
	######################################################################################
	# Method: checkAuth(...)
	# Arguments: <none>
	# Purpose: Returns true if their is a valid user logged in, false otherwise
	######################################################################################
	public function checkAuth() {
		
		/* Check authentication */
		return $this->a->checkAuth();
		
	}

	######################################################################################
	# Method: getAuthData(...)
	# Arguments:
	#	- $field (string) :: Name of the pre-registered field
	# Purpose: Returns true if their is a valid user logged in, false otherwise
	######################################################################################
	public function getAuthData($field) {
		
		/* Return value */
		return $this->a->getAuthData($field);
		
	}
	
	######################################################################################
	# Method: logout(...)
	# Arguments: <none>
	# Purpose: Logs user out of Auth
	######################################################################################
	public function logout() {
		
		/* Check authentication */
		$this->a->logout();
		
	}	
	
}

?>
