<?php

##########################################################################################
# File: IDatabase.class.php
# Modified:
#   - [06.08.2007 :: Keith]  Created.
# Purpose: This interface defines the functions that all database classes must implement.
##########################################################################################

interface IDatabase {
    
    public function db_connect($host,$user,$pass,$db);
    public function db_query($query);
    public function db_fetch_assoc($rs);
    
}

?>