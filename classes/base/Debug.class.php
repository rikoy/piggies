<?php

##########################################################################################
# Class: Debug
# Modified:
#   - [06.07.2007 :: Keith]  Created.
# Purpose: Contains static methods used for debugging the system.
##########################################################################################

class Debug extends UtilityObj {
    
    ######################################################################################
    # Method: log(...)
    # Arguments:
    #    - $msg     (string) :: Message to print in log
    #   - $logfile (string) :: Log name to use as file name.
    # Purpose: Adds an entry to the log (appends to file)
    ######################################################################################
    public static function log($msg,$logfile="system") {
    
        /* Open file handle for appending */
        $file       = $_SERVER['DOCUMENT_ROOT'] . "/{$logfile}." . date("Ymd") . ".html";
        $fileHandle = fopen($file,"a");
        if(!$fileHandle) {
            throw new ExceptionFile("Could not open log file");
        }
        
        /* Write message to log */
        $msg = "<b>" . date("h:i:s a") . "</b> :: " . $msg . "<br />";
        if(!fwrite($fileHandle, $msg)) {
            throw new ExceptionFile("Could not write to log file");
        }
    
    }
    
}

?>
