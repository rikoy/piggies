<?php

##########################################################################################
# File: DatabaseMySQL.class.php
# Modified:
#   - [06.08.2007 :: Keith]  Created.
# Purpose: This class implements the IDatabase interface and uses MySQLi functions
#   to perform database operations.
##########################################################################################

class DatabaseMySQL implements IDatabase {
    
    public function __construct() {}
    public function db_connect($host,$user,$pass) {}
    
}

?>