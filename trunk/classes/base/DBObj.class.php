<?php

##########################################################################################
# Class: DBObj
# Modified:
#   - [06.07.2007 :: Keith]  Created.
# Purpose: The DB Interface implemented generically (dependency injection / 
#	loose coupling).
##########################################################################################

class DBObj extends SystemObj implements DBInterface {
	
	######################################################################################
	# Members
	######################################################################################
	public $protocol;	// Stores a reference to the protocol object
	
	######################################################################################
	# Method: __construct(...)
	# Arguments:
	#   - $p (string) :: The protocol to use for this database object.
	# Purpose: Creates the object with the given protocol.
	######################################################################################
	function __construct($p="mysql") {
		
		switch($p) {
			case "mysql":
				$this->protocol = new DBObj_mysql;
				break;
			default:
				throw new ExceptionDB("Could not load requested protocol ($p)");
		}
		
	}

	######################################################################################
	# Method: db_connect(...)
	# Arguments:
	#   - $server   (string) :: The IP address / host address of the database server.
	#   - $username (string) :: Username used to connect to the database.
	#   - $password (string) :: Password used to connect to the database.
	# Purpose: Connects to the database using parameters.  Returns the resource link on
	#			on success, and throws an exception on error.
	######################################################################################	
	public function db_connect($server,$username,$password) {

		$r = $this->protocol->db_connect($server, $username, $password);
		if(!$r) { 
			throw new ExceptionDB("Failed to connect: \"$server\"<br />
				DB Code: " . $this->db_errno($rs) . "<br />DB Error: " . 
				$this->db_error($rs) . "<br />"); 
		} else {
			return $r;
		}
		
	}

	######################################################################################
	# Method: db_select_db(...)
	# Arguments:
	#   - $name (string) :: The name of the database to connect to.
	#   - $rs   (string) :: Resource to select from
	# Purpose: Selects the given database through the given resource link.
	######################################################################################		
	public function db_select_db($name,$rs) {

		if(!$this->protocol->db_select_db($name,$rs)) { 
			throw new ExceptionDB("Failed to connect: \"$name\"<br />
				DB Code: " . $this->db_errno($rs) . "<br />DB Error: " . 
				$this->db_error($rs) . "<br />"); 
		}
	
	}
	
	######################################################################################
	# Method: db_query(...)
	# Arguments:
	#   - $query (string) :: The query to run against the database.
	#   - $rs    (string) :: Resource to use.
	# Purpose: Executes query and returns the result record.
	######################################################################################		
	public function db_query($query,$rs) {

		/* Run mysql function */
		$r = $this->protocol->db_query($query,$rs);
		if(!$r) {
			throw new ExceptionDB("Query failed to run.<br />Query: \"$query\"<br />
				DB Code: " . $this->db_errno($rs) . "<br />DB Error: " . 
				$this->db_error($rs) . "<br />");
		} else {
			return $r;
		}

	}
	
	######################################################################################
	# Method: db_num_rows(...)
	# Arguments:
	#   - $rs    (string) :: Resource to count within.
	# Purpose: Returns the number of rows in the result resource.
	######################################################################################			
	public function db_num_rows($rs) {
		
		/* Run mysql function */
		return $this->protocol->db_num_rows($rs);
		
	}
	
	######################################################################################
	# Method: db_fetch_array(...)
	# Arguments:
	#   - $result (string) :: Result set to grab row from
	#	- $type   (string) :: The type of return to use (ASSOC, NUMERIC, BOTH, etc)
	# Purpose: Returns an array of results.
	######################################################################################				
	public function db_fetch_array($result,$type="") {
	
		/* Run mysql function */
		return $this->protocol->db_fetch_array($result,$type);
	
	}	
	
	######################################################################################
	# Method: db_insert_id(...)
	# Arguments:
	#   - $rs    (string) :: Resource
	# Purpose: Returns the last used insert_id (auto increment) on this resource.
	######################################################################################	
	public function db_insert_id($rs) {
	
		/* Run mysql function */
		return $this->protocol->db_insert_id($rs);
	
	}	
	
	######################################################################################
	# Method: db_errno(...)
	# Arguments:
	#   - $rs    (string) :: Resource
	# Purpose: Returns the error number of the last error on this resource.
	######################################################################################	
	public function db_errno($rs) {
	
		/* Run mysql function */
		return $this->protocol->db_errno($rs);
	
	}
	
	######################################################################################
	# Method: db_error(...)
	# Arguments:
	#   - $rs    (string) :: Resource
	# Purpose: Returns the error on this resource.
	######################################################################################	
	public function db_error($rs) {
	
		/* Run mysql function */
		return $this->protocol->db_error($rs);
	
	}

}

?>