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
    
    public function db_connect($host,$user,$pass,$db) {

        $this->_db = mysqli_connect($host,$user,$pass,$db);
        if(mysqli_connect_errno()) {
            throw new DatabaseException();
        }

    }
    
    public function db_query($query) {

        if(!$this->_db) {
            throw new DatabaseException();
        }
        
        $rs = $this->_db->query($query);
        if(!$rs) {
            echo $this->_db->error;
            throw new DatabaseException();
        }
        
        return $rs;
        
    }

    public function db_fetch_assoc($rs) {
        return $rs->fetch_assoc();
    }
    
}

?>