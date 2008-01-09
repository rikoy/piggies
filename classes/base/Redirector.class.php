<?php

##########################################################################################
# Class: Redirector
# Modified:
#   - [06.07.2007 :: Keith]  Created.
#	- [06.18.2007 :: Keith]  Added methods error(...) and thank(...)
# Purpose: Contains static methods used for redirecting within the system.
##########################################################################################

class Redirector extends UtilityObj {
	
	######################################################################################
	# Method: thank(...)
	# Arguments:
	#	- $filename (string) :: File to send to header(...)
	#   - $html     (string) :: String of thank you content
	# Purpose: Sets the thanks and pushes user to Thank you page
	######################################################################################
	public static function thank($filename,$html) {
	
		/* Set $_SESSION vars */
		$_SESSION["thanks"] = $html;
	
		/* Redirect */
		self::redirect($filename);
	
	}
	
	######################################################################################
	# Method: error(...)
	# Arguments:
	#	- $filename (string) :: File to send to header(...)
	#   - $text     (string) :: Text describing error
	# Purpose: Sets the error $_SESION var and pushes user to error page
	######################################################################################
	public static function error($filename,$text="") {
	
		/* Set $_SESSION vars */
		$_SESSION["errors"] = $text;
	
		/* Redirect */
		self::redirect($filename);
	
	}

	######################################################################################
	# Method: success(...)
	# Arguments:
	#	- $filename (string) :: File to send to header(...)
	#   - $text     (string) :: Text describing error
	# Purpose: Sets the success $_SESION var and redirects
	######################################################################################
	public static function success($filename,$text="") {
	
		/* Set $_SESSION vars */
		$_SESSION["success"] = $text;
	
		/* Redirect */
		self::redirect($filename);
	
	}	
	
	######################################################################################
	# Method: redirect(...)
	# Arguments:
	#	- $filename (string) :: File to send to header(...)
	# Purpose: Pushes user to Thank you page
	######################################################################################	
	public static function redirect($filename) {

		/* If the buffers are full, flush them */
		if( ob_get_length() > 0 ) {
			ob_flush();
		}
		
		/* If we haven't sent any headers, use the preferred method */
		if( !headers_sent() ) {
			header("location: $filename");
			exit();
		} else {
			echo "<meta http-equiv=\"refresh\" 
					content=\"0;url=$filename\">";
			exit();
		}

	}

	
}

?>
