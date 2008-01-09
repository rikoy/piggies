<?php

##########################################################################################
# Class: AppObj
# Modified:
#   - [06.07.2007 :: Keith]  Created.
# Purpose: The root of a given application, this class contains static methods that 
#			defines how an application is going to load it's working state.
##########################################################################################

class AppObj extends DBObj {
		
	######################################################################################
	# Method: __construct(...)
	# Arguments:
	#   - $prefix (string) :: Table prefix used identify tables for this application
	# Purpose: Creates a new application object
	######################################################################################
	function __construct($namespace) {
		
		/* Store table prefix */
		if($namespace != "") {
			$this->namespace = $namespace . "_";
		} else {
			$this->namespace = "";
		} 
		
		/* Read in the settings file for the given application */
		try {
			$this->readInSettings($namespace);
		} catch(ExceptionFile $e) {
			$e->exceptionCrash();
		}
		
		/* Call the DBObj constructor with protocol */
		try {
			if($this->d("DBPROT")) {
				parent::__construct($this->c("DBPROT"));
			} else {
				parent::__construct();
			}
		} catch(ExceptionFile $e) {
			$e->exceptionCrash();
		}
		
	}
	
}

?>
