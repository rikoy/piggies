<?php

##########################################################################################
# Class: DBObj_mysql
# Modified:
#   - [06.07.2007 :: Keith]  Created.
# Purpose: Implementation of the MySQL version of DBInterface
##########################################################################################

class DBObj_mysql implements DBInterface {
	
	######################################################################################
	# Method: db_connect(...)
	# Arguments:
	#   - $server   (string) :: The IP address / host address of the database server.
	#   - $username (string) :: Username used to connect to the database.
	#   - $password (string) :: Password used to connect to the database.
	# Purpose: Connects to the database using parameters.  Returns the resource link on
	#			on success, and throws an exception on error.
	######################################################################################	
	public function db_connect($server,$username,$password ) {
		
		/* Run mysql function */
		return mysql_connect($server,$username,$password);
		
	}
	
	######################################################################################
	# Method: db_select_db(...)
	# Arguments:
	#   - $name (string) :: The name of the database to connect to.
	#   - $rs   (string) :: Resource to select from
	# Purpose: Selects the given database through the given resource link.
	######################################################################################		
	public function db_select_db($name,$rs) {

		/* Run mysql function */
		return mysql_select_db($name,$rs);
	
	}

	######################################################################################
	# Method: db_query(...)
	# Arguments:
	#   - $query (string) :: The query to run against the database.
	#   - $rs    (string) :: Resource to use.
	# Purpose: Executes query and returns the result record.
	######################################################################################		
	public function db_query($query,$rs) {

		/* Run mysql function (after replacing magical functions */
		//return mysql_query($query,$rs);
		return mysql_query( preg_replace("/\'_sql_:([^\']*)\'/","$1",$query),$rs );
		
	}

	######################################################################################
	# Method: db_query(...)
	# Arguments:
	#   - $rs    (string) :: Resource to count within.
	# Purpose: Returns the number of rows in the result resource.
	######################################################################################			
	public function db_num_rows($rs) {
		
		/* Run mysql function */
		return mysql_num_rows($rs);
		
	}
	
	######################################################################################
	# Method: db_fetch_array(...)
	# Arguments:
	#   - $result (string) :: Result set to grab row from
	# Purpose: Returns an array of results.
	######################################################################################				
	public function db_fetch_array($result,$type="") {
	
		/* Run mysql function */
		if($type=="") {
			return mysql_fetch_array($result);
		} else {
			return mysql_fetch_array($result,$type);
		}
	
	}
	
	######################################################################################
	# Method: db_insert_id(...)
	# Arguments:
	#   - $rs    (string) :: Resource
	# Purpose: Returns the last used insert_id (auto increment) on this resource.
	######################################################################################	
	public function db_insert_id($rs) {
	
		/* Run mysql function */
		return mysql_insert_id($rs);
	
	}
	
	######################################################################################
	# Method: db_errno(...)
	# Arguments:
	#   - $rs    (string) :: Resource
	# Purpose: Returns the error number of the last error on this resource.
	######################################################################################	
	public function db_errno($rs) {
	
		/* Run mysql function */
		return mysql_errno($rs);
	
	}
	
	######################################################################################
	# Method: db_error(...)
	# Arguments:
	#   - $rs    (string) :: Resource
	# Purpose: Returns the error number of the last error on this resource.
	######################################################################################	
	public function db_error($rs) {
	
		/* Run mysql function */
		return mysql_error($rs);
	
	}
	
}

?>
