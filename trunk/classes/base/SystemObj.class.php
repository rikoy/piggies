<?php

##########################################################################################
# Class: SystemObj
# Modified:
#   - [06.07.2007 :: Keith]  Created.
# Purpose: Forms the base of the systems class hierarchy.  Contains static functions used
#            to support general System functions.
##########################################################################################

class SystemObj {
    
    ######################################################################################
    # Members
    ######################################################################################
    public $db;            // Static reference to this applications database 
                            //    resource link.
    public $namespace;    // Prefix used to identify tables for this application
                            //    in the database.        
    
    ######################################################################################
    # Method: readInSettings(...)
    # Arguments:
    #   - $prefix (string) :: Prefix used to identify ini file.
    # Purpose: Reads in ini file and store as constants.
    ######################################################################################
    public function readInSettings($prefix) {
        
        /* Local variables */
        $iniArray = array();
        
        /* Check for file depth */
        if(!defined("FILE_DEPTH")) { define("FILE_DEPTH", ""); }
        
        /* Try and read in the ini file */
        if(file_exists(FILE_DEPTH."settings.{$prefix}.ini")) {
            $iniArray = parse_ini_file(FILE_DEPTH."settings.{$prefix}.ini");
        } else {
            throw new ExceptionFile("Could not load settings.{$prefix}.ini");
        }
        
        /* Store as constants */
        foreach($iniArray as $ini=>$value) {
            if(!defined($prefix ."_" . $ini)) {
                define($prefix ."_" . $ini, $value);
            }
        }
        
    }

    ######################################################################################
    # Method: c(...)
    # Arguments:
    #   - $name (string) :: Name of the constant to refer return
    # Purpose: Returns the value of the given constant, or an empty string if the 
    #            constant is not defined.
    ######################################################################################
    public function c($name) {
        
        if($name == "") { return ""; }
        if(defined($this->namespace . $name)) { 
            return constant($this->namespace . $name); 
        } else { 
            return ""; 
        }
        
    }    

    ######################################################################################
    # Method: d(...)
    # Arguments:
    #   - $name (string) :: Name of the constant to refer return status
    # Purpose: Returns true if the given constant is defined.
    ######################################################################################
    public function d($name) {
        
        return defined($this->namespace . $name);
        
    }
    
    ######################################################################################
    # Method: connectToDatabase(...)
    # Arguments: <none>
    # Purpose: Connects to the database.
    ######################################################################################
    public function connectToDatabase() {
    
        /* Make sure the proper configuration files exist */
        if(!$this->d("DBHOST") || !$this->d("DBNAME") || 
           !$this->d("DBUSER") || !$this->d("DBPASS")) {
        
            throw new ExceptionDB("Could not find database connection settings");
        
        }
        
        /* Connect to database or send exception upwards */
        try {
            $this->db = $this->db_connect($this->c("DBHOST"), $this->c("DBUSER"), 
                            $this->c("DBPASS"));
            $this->db_select_db($this->c("DBNAME"),$this->db);            
        } catch(ExceptionDB $ex) {
            throw $ex;
        }

    }    
    
}

?>