<?php

##########################################################################################
# Class: ExceptionObj
# Modified:
#   - [06.07.2007 :: Keith]  Created.
# Purpose: Represents a generic Exception.
##########################################################################################

class ExceptionObj extends Exception {

	######################################################################################
	# Method: __construct(...)
	# Arguments:
	#   - $message (string) :: Message to display for user
	# Purpose: Creates a new exception object
	######################################################################################
	public function __construct( $message = null, $code = 0 ) {
	
		parent::__construct( $message, $code );
	
	}

	######################################################################################
	# Method: exceptionCrash(...)
	# Arguments: <none>
	# Purpose: Crashes on this exception, notifies user, sends email
	######################################################################################
	function exceptionCrash($redirectURI = "errors.php") {
		
		/* Email me about errors */
		if(!defined("ERROR_EMAIL_TO")) { define("ERROR_EMAIL_TO","kframnes@demicooper.com"); }
		if(!defined("ERROR_EMAIL_FROM")) { define("ERROR_EMAIL_FROM","errors@unknown.com"); }
		if(!defined("ERROR_EMAIL_HOST")) { define("ERROR_EMAIL_HOST","localhost"); }
		if(!defined("ERROR_EMAIL_SUBJ")) { define("ERROR_EMAIL_SUBJ","Generic Error"); }
		
		Emailer::sendmail(
			array(
				"to"      => array(ERROR_EMAIL_TO),
				"from"    => ERROR_EMAIL_FROM,
				"host"    => ERROR_EMAIL_HOST,
				"message" => str_replace("<br />","\n",$this->__toString()),
				"subject" => ERROR_EMAIL_SUBJ
			)	
		);
		
		/* Check to see if an errors file is defined and forward */
		if(!defined("FILE_DEPTH")) { define("FILE_DEPTH",""); }
		if(file_exists(FILE_DEPTH . $redirectURI)) {
			
			/* Display Message in directed URI */
			Redirector::error($redirectURI,$this->message);

		} else {
	
			/* Display Message */
			echo $this->__toString();
			exit();

		}
		
	}
	
	######################################################################################
	# Method: exceptionWarn(...)
	# Arguments: <none>
	# Purpose: Issues a warning to the user by directing the browser to the given page
	#			and setting the $_SESSION["errors"] variable (for display).
	######################################################################################
	function exceptionWarn($redirectURI = "NOFILE") {
	
		/* Replace default with self */
		if($redirectURI == "NOFILE") { $redirectURI = $_SERVER['PHP_SELF']; }
	
		/* Display Message in directed URI */
		Redirector::error($redirectURI,$this->message);
		
	}
	
	######################################################################################
	# Method: exceptionNotify(...)
	# Arguments: <none>
	# Purpose: Sends email to Developer about error but remains
	#			transparent to users.
	######################################################################################
	function exceptionNotify() {
	
		/* Email me about errors */
		if(!defined("ERROR_EMAIL_TO")) { define("ERROR_EMAIL_TO","kframnes@demicooper.com"); }
		if(!defined("ERROR_EMAIL_FROM")) { define("ERROR_EMAIL_FROM","errors@unknown.com"); }
		if(!defined("ERROR_EMAIL_HOST")) { define("ERROR_EMAIL_HOST","localhost"); }
		if(!defined("ERROR_EMAIL_SUBJ")) { define("ERROR_EMAIL_SUBJ","Generic Error"); }
		
		Emailer::sendmail(
			array(
				"to"      => array(ERROR_EMAIL_TO),
				"from"    => ERROR_EMAIL_FROM,
				"host"    => ERROR_EMAIL_HOST,
				"message" => str_replace("<br />","\n",$this->__toString()),
				"subject" => ERROR_EMAIL_SUBJ
			)	
		);
		
	}
	
	######################################################################################
	# Method: __toString(...)
	# Arguments: <none>
	# Purpose: Prints message and backtrace
	######################################################################################
	function __toString() {
	
		/* Display Message */
		return $this->message . "<br />" . $this->getTraceAsString();
		
	}
	
}

?>
