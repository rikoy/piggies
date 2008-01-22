<?php

##########################################################################################
# File: SystemRoot.class.php
# Modified:
#   - [06.08.2007 :: Keith]  Created.
# Purpose: This class acts as the root object for [[Application]]
##########################################################################################

class SystemRoot {
    
    private $db;
    
    ######################################################################################
    # Function: __construct(...)  
    # Purpose : Creates the object.
    ######################################################################################
    public function __construct() {
        $this->_createDatabaseResource();
        $this->_connectToDatabase();
    }
    
    ######################################################################################
    # Function: connectToDatabase(...)  
    # Purpose : Connects to the database defined in config.php
    ######################################################################################
    public function verifyDatabase() {
        
        /* Verify System Constants */
        if(!$this->_verifySystemConstants()) {
            throw new SystemException();
        }
        
        /* Attempt to connect to database and return results */
        try {
        	$this->db->db_connect(SYS_DB_HOST,SYS_DB_USER,SYS_DB_PASS);
        } catch(DatabaseException $ex) {
        	throw new SystemException();
        }
        
    }
    
    # ********************************************************************************** #
    # ***** ACCOUNTS ******************************************************************* #
    # ********************************************************************************** #
    
    ######################################################################################
    # Function: getAccountPodList(...)
    # Purpose : Returns array of accounts, balances, pending, and cleared
    ######################################################################################    
    public function getAccountPodList() {
        
        $p = SYS_DB_PREFIX; /* Get table prefix */
        try {
            $rs = $this->db->db_query(
                "SELECT account_id, account_name, transaction_cleared, 
                    SUM(details_amount) as total
                 FROM {$p}accounts 
                    INNER JOIN {$p}transaction_details
                        ON account_id = details_account_id
                    INNER JOIN {$p}transactions
                        ON transaction_id = details_transaction_id
                 GROUP BY account_name, transaction_cleared"
            );
        } catch (DatabaseException $ex) {
            throw new SystemException();
        }
        
        $arr = array();
        while($row = $this->db->db_fetch_assoc($rs)) {
            if(!isset($arr[$row['account_id']]['name'])) {
                $arr[$row['account_id']]['name']    = $row['account_name'];
                $arr[$row['account_id']]['balance'] = 0;
                $arr[$row['account_id']]['pending'] = 0;
                $arr[$row['account_id']]['shown']   = 0;
            }
            
            if($row['transaction_cleared'] == 0) {
                $arr[$row['account_id']]['pending'] += $row['total'];
            } else {
                $arr[$row['account_id']]['pending'] += $row['shown'];
            }
            $arr[$row['account_id']]['balance'] += $row['total'];
                
        }
        
        return $arr;
        
    }
    
    
    
    
    
    
    
    # ********************************************************************************** #
    # ***** PRIVATE ******************************************************************** #
    # ********************************************************************************** #    
    
    ######################################################################################
    # Function: _createDatabaseResource(...)
    # Purpose : Creates a DB class for the chosen database type
    # Returns : Nothing
    ######################################################################################    
    private function _createDatabaseResource() {
        
        $dbType   = "Database".SYS_DB_TYPE;
        $this->db = new $dbType();
        
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
    
    ######################################################################################
    # Function: _connectToDatabase(...)  
    # Purpose : Connects to the database defined in config.php
    ######################################################################################
    public function _connectToDatabase() {
               
        /* Attempt to connect to database and return results */
        try {
        	$this->db->db_connect(SYS_DB_HOST,SYS_DB_USER,SYS_DB_PASS,SYS_DB_NAME);
        	
        } catch(DatabaseException $ex) {
        	throw new SystemException();
        }
        
    }    
    
}

?>