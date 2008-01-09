<?php

##########################################################################################
# Class: DBInterface
# Modified:
#   - [06.07.2007 :: Keith]  Created.
# Purpose: Interface to define a common database implementation across different 
#            protocols (mysql, mssql, etc).
##########################################################################################

interface DBInterface {
    
    function db_connect($server,$username,$password);
    function db_select_db($name,$rs);
    function db_query($query,$rs);
    function db_num_rows($rs);
    function db_fetch_array($result,$type="");
    function db_errno($rs);
    function db_error($rs);
    function db_insert_id($rs);
    
}

?>
