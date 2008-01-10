<?php

##########################################################################################
# Class: phpMyWallet
# Modified:
#   - [06.07.2007 :: Keith]  Created.
# Purpose: The root of a given application, this class contains static methods that 
#     defines how an application is going to load it's working state.
##########################################################################################

class phpMyWallet extends AppObj {

    ######################################################################################
    # Method: __construct(...)
    # Arguments:
    #    - $namespace (string) :: Name of the objects namespace
    # Purpose: 
    ######################################################################################
	function __construct($namespace) {
	
		parent::__construct($namespace);
		
		try {
			$this->connectToDatabase();
		} catch(ExceptionDB $ex) {
			throw $ex;
		}
	
	}

}

?>