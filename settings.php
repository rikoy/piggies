<?php

##########################################################################################
# File: settings.php
# Modified:
#   - [06.08.2007 :: Keith]  Created.
# Purpose: File used to set autoloader, error handling sub-system and system constants.
##########################################################################################

##########################################################################################
# System Constants
##########################################################################################

/* Error Emailing */
define("SYSTEM_DEVELOPER_EMAIL", "keith.framnes@gmail.com");
define("SYSTEM_EMAIL_HOST", "localhost");

/* System Timezone */
date_default_timezone_set("America/Chicago");

##########################################################################################
# Function: __autoload(...)
# Arguments:
#   - $classname (string) :: Name of the class being imported into the application.
# Purpose: Import unincluded classes during runtime.
##########################################################################################
function __autoload($classname) {
    
    /* Figure out class directory */
    if(is_dir("./classes")) {
        $fileBase = "./classes/";
    } elseif(is_dir("../classes")) {
        $fileBase = "../classes/";
    } elseif(is_dir("../../classes")) {
        $fileBase = "../../classes/";
    } else {
        die("Error: Could not locate classes directory!");
    }
    
    /* Import class */
    if(file_exists($fileBase . $classname . ".class.php")) {
        require_once($fileBase . $classname . ".class.php");
    } else if(file_exists($fileBase . "base/" . $classname . ".class.php")) { 
        require_once($fileBase . "base/" . $classname . ".class.php");
    }else {
        require_once($classname . ".php");
    }
    
}

##########################################################################################
# Function: set_exception_handler
# Arguments:
#   - $exception (Exception) :: Uncaught Exception Object
# Purpose: Catch uncaught exceptions
##########################################################################################
function handleExceptions($exception) {
    echo "<em>UNCAUGHT EXCEPTION</em><br />";
    $exception->exceptionCrash();
}
set_exception_handler("handleExceptions");

##########################################################################################
# Function: set_error_handler
# Arguments:
#   - $errno   (int)    :: 
#    - $errstr  (string) ::
#    - $errfile (string) ::
#    - $errline (int)    :: 
# Purpose: Catch and process errors
##########################################################################################
function handleErrors($errno,$errstr,$errfile,$errline) {
    if($errno != E_STRICT) { // I have no interest in these suggestions.
        throw new ExceptionError($errstr);
    }
}
set_error_handler("handleErrors");

?>