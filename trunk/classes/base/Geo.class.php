<?php

##########################################################################################
# Class: Geo
# Modified:
#   - [06.07.2007 :: Keith]  Created.
# Purpose: Contains static methods used for geographical proximity and other functions.
##########################################################################################

class Geo extends UtilityObj {
	
	######################################################################################
	# Method: distance(...)
	# Arguments:
	#   - $pointA (array) :: Array of lat and long
	#	- $pointB (array) :: Array of lat and long
	# Purpose: Calculates difference in miles between to coordinates
	######################################################################################
	public static function distance($pointA,$pointB) {
		
		$R = 3963; // miles
	
		/* Convert from degrees to radians */
		$pointA["lat"]  = deg2rad($pointA["lat"]);
		$pointA["long"] = deg2rad($pointA["long"]);
		$pointB["lat"]  = deg2rad($pointB["lat"]);
		$pointB["long"] = deg2rad($pointB["long"]);		

		/* Calculate distance */
		return acos(
			sin($pointA["lat"]) * sin($pointB["lat"]) + 
			cos($pointA["lat"]) * cos($pointB["lat"]) * 
			cos($pointB["long"] - $pointA["long"])
		) * $R;
                                    	
	}
	
	
	
	
}

?>
