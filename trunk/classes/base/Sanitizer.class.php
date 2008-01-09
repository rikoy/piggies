<?php

##########################################################################################
# Class: Sanitizer
# Modified:
#   - [06.07.2007 :: Keith]  Created.
# Purpose: Contains static methods used for sanitizing arguments in the system.
##########################################################################################

class Sanitizer extends UtilityObj {
	
	######################################################################################
	# Method: cleanInteger(...)
	# Arguments:
	#   - $val (int) :: Integer to get clean.
	# Purpose: Cleans the integer.
	######################################################################################
	public static function cleanInteger(&$val) {
		$val = intval($val);
		return $val;
	}

	######################################################################################
	# Method: cleanMySQLText(...)
	# Arguments:
	#   - $val (string) :: string to get clean.
	# Purpose: Cleans the integer.
	######################################################################################
	public static function cleanMySQLText(&$val) {
		$val = addslashes($val);
		return $val;
	}
	
}

?>
