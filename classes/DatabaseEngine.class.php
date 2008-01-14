<?php


class DatabaseEngine {
    
    private $_rs;               // Internal pointer to MySQLi resource
    private $_fieldHash;        // Hash Map: <field> => <table>
    private $_constraintMap;    // Hash Map: <table.field> => <ref'd table.field>
    
    private $_buffer;           // A place to hold field values before select(...)
    private $_results;          // Results of the last SELECT query ran
    private $_cursor;           // Cursor for results array
    
    public function __construct($_table,$_db="",$_host=array()) {
        
        ##################################################################################
        # Make sure we have something that COULD be valid inside the $_host array.  We
        # can work down a set of possibilities and ultimately will set the settings to
        # be MAMP defaults.
        ##################################################################################
        if(is_array($_host) && count($_host) != 0) {
            
            /* Fantastic, we'll just use what we were sent */
            
        } elseif(defined("SYS_DB_HOST") && defined("SYS_DB_USER") && defined("SYS_DB_PASS")) {
                        
            /* Set array using default constants */
            $_host = array();
            $_host['host'] = SYS_DB_HOST;
            $_host['user'] = SYS_DB_USER;
            $_host['pass'] = SYS_DB_PASS;
            
        } else {
            
            /* Assume default MAMP setup */
            $_host = array();
            $_host['host'] = "localhost";
            $_host['user'] = "root";
            $_host['pass'] = "root";
            
        }

        ##################################################################################
        # If we weren't explicitly given a database name, we are going to have to pick
        # something, so we'll look for a database name in the table we were passed.  We
        # will look for a . (using the <db>.<table>.<fieldname> convention).
        ##################################################################################
        if(!empty($_db)) {
            
            /* Again, great!  We'll just use what we're told */
            
        } elseif(stristr($_db,".")) {
            
            /* Use first piece as database */
            list($_db) = explode(".",$_table);
            
        } elseif(defined("SYS_DB_NAME")) {
            
            /* Set from defaults */
            $_db = SYS_DB_NAME;
            
        }
        
        ##################################################################################
        # Create a connection with the settings we've figured out.
        ##################################################################################
        $this->_rs =   mysqli_connect(
                            $_host['host'],
                            $_host['user'],
                            $_host['pass'],
                            $_db
                        );
                        
        /* If we suffered an error, do something */
        if(mysqli_connect_errno($this->_rs)) {
            die("ERROR #" . mysqli_connect_errno($this->_rs) . ": " . 
                mysqli_connect_error($this->_rs));
        }
     
        ##################################################################################
        # Check for the table inside our database.  If this table doesn't exist, we have
        # to crash with an error.
        ##################################################################################
        $_result = $this->_rs->query("SHOW FIELDS FROM {$_table}");
        if(mysqli_errno($this->_rs)) {
            die("ERROR #" . mysqli_errno($this->_rs) . ": " . 
                mysqli_error($this->_rs));
        }
        $_result->close();
        
        $this->_populateConstraintMap($_db);
        $this->_populateFieldHash($_db);

    }
    public function __get($field) {}
    public function __set($field,$value) {}
    
    public function insert($fields) {}
    public function update($fields,$condition) {}
    public function replace($fields,$onduplicate=array(),$condition){}
    public function delete($condition) {}

    public function select($fields=array("*"),$condition=array()) {
        
        
        $this->_cursor = -1;
        
    }
    public function getByPK() {}
    public function getResultsArray() {}
    
    private function _populateConstraintMap($_db) {

        ##################################################################################
        # Create internal representation of the database relationships (there needs to
        # be a faster way to do this).
        ##################################################################################
        $_result = $this->_rs->query(
                    "SELECT TABLE_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, 
                        REFERENCED_COLUMN_NAME
                     FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                     WHERE TABLE_SCHEMA =  '{$_db}' AND 
                        REFERENCED_TABLE_NAME IS NOT NULL"
            );
            
        $this->_constraintMap = array();
        while($_row = $_result->fetch_assoc()) {
            $this->_constraintMap[$_row['TABLE_NAME'].".".$_row['COLUMN_NAME']] = 
                $_row['REFERENCED_TABLE_NAME'].".".$_row['REFERENCED_COLUMN_NAME'];
        }

    }
    private function _populateFieldHash($_db) {
        
        ##################################################################################
        # Create internal representation of the database fields to quickly find a path
        # of joins.
        ##################################################################################
        $_result = $this->_rs->query(
                    "SELECT TABLE_NAME, COLUMN_NAME
                     FROM INFORMATION_SCHEMA.COLUMNS
                     WHERE TABLE_SCHEMA =  '{$_db}'"
            );
            
        $this->_fieldHash = array();
        while($_row = $_result->fetch_assoc()) {
            if(!isset($this->_fieldHash[$_row['COLUMN_NAME']])) {
                $this->_fieldHash[$_row['COLUMN_NAME']] = array($_row['TABLE_NAME']);
            } else {
                $this->_fieldHash[$_row['COLUMN_NAME']][] = $_row['TABLE_NAME'];
            }
        }
        
    }
    
}

?>