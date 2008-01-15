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
define("SYS_VERSION", "0.0.1-alpha");
define("SYS_DEBUG", true);

/* System Timezone */
date_default_timezone_set("America/Chicago");

if(SYS_DEBUG) { 
    ini_set("display_errors",1); 
} else {
    ini_set("display_errors",0); 
}

/* Include functions */
require_once("functions.php");

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
    } else if($classname == "PHPMailer") { 
        require_once($fileBase . "phpmailer/class.phpmailer.php");
    }
    
}

?>