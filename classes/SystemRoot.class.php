<?php

##########################################################################################
# File: SystemRoot.class.php
# Modified:
#   - [06.08.2007 :: Keith]  Created.
# Purpose: This class acts as the root object for [[Application]]
##########################################################################################

class SystemRoot {
    
    ######################################################################################
    # Function: __construct(...)  
    # Purpose : Creates the object.
    ######################################################################################
    public function __construct() {}
    
    ######################################################################################
    # Function: connectToDatabase(...)  
    # Purpose : Connects to the database defined in config.php
    ######################################################################################
    public function connectToDatabase() {
        
        /* Verify System Constants */
        if(!$this->_verifySystemConstants()) {
            throw new SystemException();
        }
        
        /*  */
        
    }
    
    ######################################################################################
    # Function: _verifySystemConstants(...)
    # Purpose : Verifies that all the required system constants are defined
    # Returns : (1) true - if all the required constants exist, (2) an array of constant
    #   names the system requires but could not find.
    ######################################################################################    
    private function _verifySystemConstants() {
        
        /* Check each constant for value */
        $missingConstants = array();
        if(!defined("SYS_DB_TYPE")) { $missingConstants[] = "SYS_DB_TYPE"; }
        if(!defined("SYS_DB_HOST")) { $missingConstants[] = "SYS_DB_HOST"; }
        if(!defined("SYS_DB_NAME")) { $missingConstants[] = "SYS_DB_NAME"; }
        if(!defined("SYS_DB_USER")) { $missingConstants[] = "SYS_DB_USER"; }
        if(!defined("SYS_DB_PASS")) { $missingConstants[] = "SYS_DB_PASS"; }
        
        return count($missingConstants) > 0 ? $missingConstants : true;
        
    }
    
}

?>