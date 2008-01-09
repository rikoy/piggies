<?php

##########################################################################################
# Class: AuthObj
# Modified:
#   - [06.07.2007 :: Keith]  Created.
#	- [10.10.2007 :: Keith]  Created several methods for administrating Users and Groups
# Purpose: This class contains methods that are used to control Authentication 
#	(in general)
##########################################################################################

abstract class AuthObj extends AppObj {

	######################################################################################
	# Method: create(...)
	# Arguments:
	#	- $scope (string) :: Name of the authentication scope
	# Purpose: Constructs an auth object using the given scope
	######################################################################################
	public function __construct($namespace) {
		
		parent::__construct($namespace);
		
		/* Connect to database */
		try {
			$this->connectToDatabase();
		} catch(ExceptionDB $ex) {
			$ex->exceptionCrash();
		}
	
	}
	
	######################################################################################
	# Method: hasAllPermission(...)
	# Arguments:
	#	- $perms (array) :: permissions to check for
	# Purpose: Returns true if this user has ALL of these permissions  
	######################################################################################
	public function hasAllPermission($perms) {

		/* We're looking into both tables and combining (implicit DISTINCT) results and
			comparing it to the perms sent in */
		$p = $this->namespace;
		try {
			$res = $this->db_query(
				"SELECT {$p}permissions.permissionCode 
				 FROM {$p}users 
				 	INNER JOIN {$p}users_link_permissions ON 
				 		{$p}users.userID = {$p}users_link_permissions.userID
				 	INNER JOIN {$p}permissions ON
				 		{$p}permissions.permissionID = 
				 		{$p}users_link_permissions.permissionID
				 WHERE {$p}users.userID = " . $this->getAuthData("userID") . " AND
				 	{$p}permissions.permissionCode IN ('".implode("','",$perms)."')
				 	
				 UNION
				 
				 SELECT {$p}permissions.permissionCode 
				 FROM {$p}users 
				 	INNER JOIN {$p}groups_link_permissions ON 
				 		{$p}users.userGroupID = {$p}groups_link_permissions.groupID
				 	INNER JOIN {$p}permissions ON
				 		{$p}permissions.permissionID = 
				 		{$p}groups_link_permissions.permissionID
				 WHERE {$p}users.userGroupID = " . $this->getAuthData("userGroupID") . " AND
				 	{$p}permissions.permissionCode IN ('".implode("','",$perms)."')",
				$this->db
			);
		} catch(ExceptionDB $ex) {
			$ex->exceptionCrash();
		}
		
		if(count($perms) == $this->db_num_rows($res)) {
			return true;
		} else {
			return false;
		}
		
	}	
	
	######################################################################################
	# Method: hasAnyPermission(...)
	# Arguments:
	#	- $perms (array) :: permissions to check for
	# Purpose: Returns true if this user has ANY of these permissions 
	######################################################################################
	public function hasAnyPermission($perms) {

		/* We're looking into both tables and combining (implicit DISTINCT) results and
			comparing it to the perms sent in */
		$p = $this->namespace;
		try {
			$res = $this->db_query(
				"SELECT {$p}permissions.permissionCode 
				 FROM {$p}users 
				 	INNER JOIN {$p}users_link_permissions ON 
				 		{$p}users.userID = {$p}users_link_permissions.userID
				 	INNER JOIN {$p}permissions ON
				 		{$p}permissions.permissionID = 
				 		{$p}users_link_permissions.permissionID
				 WHERE {$p}users.userID = " . $this->getAuthData("userID") . " AND
				 	{$p}permissions.permissionCode IN ('".implode("','",$perms)."')
				 	
				 UNION
				 
				 SELECT {$p}permissions.permissionCode 
				 FROM {$p}users 
				 	INNER JOIN {$p}groups_link_permissions ON 
				 		{$p}users.userGroupID = {$p}groups_link_permissions.groupID
				 	INNER JOIN {$p}permissions ON
				 		{$p}permissions.permissionID = 
				 		{$p}groups_link_permissions.permissionID
				 WHERE {$p}users.userGroupID = " . $this->getAuthData("userGroupID") . " AND
				 	{$p}permissions.permissionCode IN ('".implode("','",$perms)."')",
				$this->db
			);
		} catch(ExceptionDB $ex) {
			$ex->exceptionCrash();
		}
		
		if($this->db_num_rows($res) > 0) {
			return true;
		} else {
			return false;
		}		
		
	}		

	######################################################################################
	# Method: getUserArray(...)
	# Arguments: <none>
	# Purpose: Returns array of user info
	######################################################################################
	public function getUserArray($id) {
	
		/* Run query */
		try {
			$rs = $this->db_query(
				"SELECT *
				 FROM admin_users INNER JOIN admin_groups
				 	ON admin_users.userGroupID = admin_groups.groupID 
				 WHERE userID = $id",
				$this->db
			);
		} catch(ExceptionDB $ex) {
			$ex->exceptionCrash();
		}
		
		$ret = array();
		while($row = $this->db_fetch_array($rs)){
			$ret['userID']     = $row['userID'];
			$ret['username']   = $row['username'];
			$ret['firstName']  = $row['userFirstName'];
			$ret['lastName']   = $row['userLastName'];
			$ret['groupName']  = $row['groupName'];
			$ret['groupID']    = $row['groupID'];
		}
		
		return $ret;
	
	}	
	
	######################################################################################
	# Method: getUserArray(...)
	# Arguments: <none>
	# Purpose: Returns array of user info
	######################################################################################
	public function getPermsByUserID($uid,$gid) {
	
		/* Run query */
		try {
			$rs = $this->db_query(
				"SELECT admin_permissions.permissionID, 
				 	admin_permissions.permissionName,userBasedPermID,groupBasedPermID 
				 FROM admin_permissions 
				 	LEFT JOIN (

				 	SELECT DISTINCT admin_permissions.permissionID AS userBasedPermID 
				 	FROM admin_permissions 
				 		LEFT JOIN admin_users_link_permissions ON 
				 			admin_permissions.permissionID = admin_users_link_permissions.permissionID 
				 	WHERE admin_users_link_permissions.userID = $uid
				 	
				 	) AS userPerms ON 
				 		admin_permissions.permissionID = userBasedPermID 
				 	LEFT JOIN (
				 		
				 	SELECT DISTINCT admin_permissions.permissionID AS groupBasedPermID 
				 	FROM admin_permissions 
				 		LEFT JOIN admin_groups_link_permissions ON 
				 			admin_permissions.permissionID = admin_groups_link_permissions.permissionID 
				 	WHERE admin_groups_link_permissions.groupID = $gid
				 	
				 	) AS groupPerms ON 
				 		admin_permissions.permissionID = groupBasedPermID",
				$this->db
			);
		} catch(ExceptionDB $ex) {
			$ex->exceptionCrash();
		}
		
		$ret = array();
		while($row = $this->db_fetch_array($rs)){
			
			$ret[$row['permissionID']]['name']     = $row['permissionName'];
			$ret[$row['permissionID']]['userHas']  = $row['userBasedPermID'];
			$ret[$row['permissionID']]['groupHas'] = $row['groupBasedPermID'];
			
		}
		
		return $ret;
	
	}	
	
	######################################################################################
	# Method: addUser(...)
	# Arguments: 
	#	- $params (array) :: Array of table parameters
	# Purpose: Adds User
	######################################################################################
	public function addUser($params) {
	
		try {
			$this->db_query(
				"INSERT INTO admin_users (".
				 implode(",",array_keys($params)).") VALUES('".
				 implode("','",$params)."')",$this->db);
		} catch(ExceptionDB $ex) {
			$ex->exceptionCrash();
		}
	
	}

	######################################################################################
	# Method: deleteUser(...)
	# Arguments: 
	#	- $id (int) :: ID of user to delete
	# Purpose: Deletes User
	######################################################################################
	public function deleteUser($id) {
	
		try {
			$this->db_query(
				"DELETE FROM admin_users 
				 WHERE userID = $id
				 LIMIT 1",
				$this->db);
		} catch(ExceptionDB $ex) {
			$ex->exceptionCrash();
		}
	
	}
	
	######################################################################################
	# Method: addGroup(...)
	# Arguments: 
	#	- $params (array) :: Array of table parameters
	# Purpose: Adds Group
	######################################################################################
	public function addGroup($params) {
	
		try {
			$this->db_query(
				"INSERT INTO admin_groups (".
				 implode(",",array_keys($params)).") VALUES('".
				 implode("','",$params)."')",$this->db);
		} catch(ExceptionDB $ex) {
			$ex->exceptionCrash();
		}
	
	}

	######################################################################################
	# Method: deleteGroup(...)
	# Arguments: 
	#	- $id (int) :: ID of group to delete
	# Purpose: Deletes Group
	######################################################################################
	public function deleteGroup($id) {
	
		try {
			$this->db_query(
				"DELETE FROM admin_groups 
				 WHERE groupID = $id
				 LIMIT 1",
				$this->db);
		} catch(ExceptionDB $ex) {
			$ex->exceptionCrash();
		}
	
	}
	
	######################################################################################
	# Method: addPerm(...)
	# Arguments: 
	#	- $params (array) :: Array of table parameters
	# Purpose: Adds Permission
	######################################################################################
	public function addPerm($params) {
	
		try {
			$this->db_query(
				"INSERT INTO admin_permissions (".
				 implode(",",array_keys($params)).") VALUES('".
				 implode("','",$params)."')",$this->db);
		} catch(ExceptionDB $ex) {
			$ex->exceptionCrash();
		}
	
	}

	######################################################################################
	# Method: deletePerm(...)
	# Arguments: 
	#	- $id (int) :: ID of group to delete
	# Purpose: Deletes Group
	######################################################################################
	public function deletePerm($id) {
	
		try {
			$this->db_query(
				"DELETE FROM admin_permissions
				 WHERE permissionID = $id
				 LIMIT 1",
				$this->db);
		} catch(ExceptionDB $ex) {
			$ex->exceptionCrash();
		}
	
	}	
	
	######################################################################################
	# Method: getUserList(...)
	# Arguments: <none>
	# Purpose: Returns array of users
	######################################################################################
	public function getUserList() {
	
		/* Run query */
		try {
			$rs = $this->db_query(
				"SELECT *
				 FROM admin_users INNER JOIN admin_groups
				 	ON admin_users.userGroupID = admin_groups.groupID 
				 ORDER BY username ASC",
				$this->db
			);
		} catch(ExceptionDB $ex) {
			$ex->exceptionCrash();
		}
		
		$ret = array();
		while($row = $this->db_fetch_array($rs)){
			$ret[$row['userID']]['username']   = $row['username'];
			$ret[$row['userID']]['firstName']  = $row['userFirstName'];
			$ret[$row['userID']]['lastName']   = $row['userLastName'];
			$ret[$row['userID']]['groupName']  = $row['groupName'];
		}
		
		return $ret;
	
	}	
	
	######################################################################################
	# Method: getGroupList(...)
	# Arguments: <none>
	# Purpose: Returns array of users
	######################################################################################
	public function getGroupList() {
	
		/* Run query */
		try {
			$rs = $this->db_query(
				"SELECT *
				 FROM admin_groups
				 ORDER BY groupName ASC",
				$this->db
			);
		} catch(ExceptionDB $ex) {
			$ex->exceptionCrash();
		}
		
		$ret = array();
		while($row = $this->db_fetch_array($rs)){
			$ret[$row['groupID']]['name']   = $row['groupName'];
			$ret[$row['groupID']]['desc']  = $row['groupDesc'];
		}
		
		return $ret;
	
	}

	######################################################################################
	# Method: getGroupList(...)
	# Arguments: <none>
	# Purpose: Returns array of users
	######################################################################################
	public function getPermissionList() {
	
		/* Run query */
		try {
			$rs = $this->db_query(
				"SELECT *
				 FROM admin_permissions
				 ORDER BY permissionName ASC",
				$this->db
			);
		} catch(ExceptionDB $ex) {
			$ex->exceptionCrash();
		}
		
		$ret = array();
		while($row = $this->db_fetch_array($rs)){
			$ret[$row['permissionID']]['name'] = $row['permissionName'];
			$ret[$row['permissionID']]['code'] = $row['permissionCode'];
			$ret[$row['permissionID']]['desc'] = $row['permissionDesc'];
		}
		
		return $ret;
	
	}
	
}

?>
