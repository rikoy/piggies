<?php

##########################################################################################
# Class: AuthInterface
# Modified:
#   - [10.05.2007 :: Keith]  Created.
# Purpose: Interface to define a common auth implementation across different 
#            protocols (PEAR::Auth, LDAP, etc).
##########################################################################################

interface AuthInterface {
    
    function start($login=true);
    function checkAuth();
    function getAuthData($field);
    function logout();
    
}

?>
