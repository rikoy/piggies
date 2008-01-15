<?php

##########################################################################################
# File: DatabaseMySQL.class.php
# Modified:
#   - [06.08.2007 :: Keith]  Created.
# Purpose: This class implements the IDatabase interface and uses MySQLi functions
#   to perform database operations.
##########################################################################################

class DatabaseMySQL implements IDatabase {
    
    private $_db;
    
    public function __construct() {
        $this->_db = null;
    }
    
    public function db_connect($host,$user,$pass) {
        $this->_db = mysqli_connect($host,$user,$pass);
        if(mysqli_connect_errno()) {
            throw new DatabaseException();
        }
    }
    
}

?>