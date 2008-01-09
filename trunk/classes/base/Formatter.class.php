<?php

##########################################################################################
# Class: Formatter
# Modified:
#   - [06.07.2007 :: Keith]  Created.
#    - [11.09.2007 :: Keith]  Added Array2JSON to support JSON communication of AJAX
#        systems.
# Purpose: Contains static methods used for formatting output in the system.
##########################################################################################

class Formatter extends UtilityObj {

    ######################################################################################
    # Method: mySQL2NormalDate(...)
    # Arguments:
    #   - $mydate    (int)    :: Date to translate.
    #    - $delimiter (string) :: Delimiter used to seperate pieces
    # Purpose: Returns a string representing the provided date in a normal MM-DD-YYYY
    #            format.
    ######################################################################################
    public static function mySQL2NormalDate($mydate) {
        
        /* Create array of permitted delimiters */
        $permitted = array("-","\/");
        
        /* Create pattern for date format matching */
        $pattern = "/^([1-9][0-9]{0,3})(" . implode("|",$permitted) . 
            ")([0]?[1-9]|1[0-2])(\\2)([0]?[1-9]|[1-2][0-9]|3[0-1])$/";

        /* Validate format */
        if(!preg_match($pattern,$mydate,$matches)) {
            return $mydate;
        }
        $newDate = $matches[3] . $matches[2] . $matches[5] . $matches[2] . $matches[1];
        
        return $newDate;
        
    }
    
    ######################################################################################
    # Method: americanPhoneNumber(...)
    # Arguments:
    #   - $value (string) :: Values to format as american phone number
    # Purpose: Returns a string matching the format, or the original string if the 
    #    pattern doesn't match.
    ######################################################################################    
    public static function americanPhoneNumber($value) {
        
        /* Create pattern for date format matching */
        $pattern = "/^([0-9]{3}|\([0-9]{3}\))(-|\s)([0-9]{3})-([0-9]{4})$/";
        
        /* Validate format */
        if(!preg_match($pattern,$value,$matches)) {
            return $value;
        }
        return $matches[1]."-".$matches[3]."-".$matches[4];
        
    }    
    
    ######################################################################################
    # Method: displayErrors(...)
    # Arguments: <none>
    # Purpose: Displays contents of either the errors session variable (error from 
    #    seperate script file) or from the Validator (errors calculating inline).  This
    #    call makes sure there is an error to print before proceeding, making it able
    #    to be called everytime (no if-statement required).
    ######################################################################################
    public static function displayErrors() {
        
        if(isset($_SESSION["errors"])) {
            $msg = $_SESSION["errors"];
        } elseif(Validator::hasErrors()) {
            $msg = Validator::getErrors();
        }
        
        if(isset($msg) && trim(strlen($msg)) > 0) {
            echo "<div class=\"error\">";
            echo $msg;
            echo "</div>";
        }
        
        unset($_SESSION["errors"]);
        
    }
    
    ######################################################################################
    # Method: displaySuccess(...)
    # Arguments: <none>
    # Purpose: Displays contents of the success session variable.  This
    #    call makes sure there is a success to print before proceeding, making it able
    #    to be called everytime (no if-statement required).
    ######################################################################################
    public static function displaySuccess() {
        
        if(isset($_SESSION["success"])) {
            $msg = $_SESSION["success"];
        }
        
        if(isset($msg) && trim(strlen($msg)) > 0) {
            echo "<div class=\"success\">";
            echo $msg;
            echo "</div>";
        }
        
        unset($_SESSION["success"]);
        
    }
    
    ######################################################################################
    # Method: displayJavaScriptRequirement(...)
    # Arguments: <none>
    # Purpose: Creates a javascript required message and attempts to hide it using
    #    javascript.
    ######################################################################################
    public static function displayJavaScriptRequirement() {
        
        echo "<div id=\"javaWarning\">Javascript is required for this application or
            function to work properly.  Please enable javascript and reload the page 
            before proceeding.</div>";
        echo "<script>document.getElementById('javaWarning').style.display = 
            'none';</script>";
        
    }
    
    ######################################################################################
    # Method: displayErrors($indexes,$location)
    # Arguments:
    #    - $indexes     (Array) :: Array of indexes to pull into my redisplay array
    #    - $location (Array) :: Array of values (with indexes) to pull into my redisplay
    #                            array.
    # Purpose: Builds an array that contains values for items where they are available 
    #            and "" where no values are available.  Used to easily redisplay content
    #            in forms after a validation error is detected.
    ######################################################################################    
    public static function prepForRedisplay($indexes,$location) {
        
        $redisplay = array();
        
        foreach($indexes as $var) {
            if(isset($location[$var])) {
                $redisplay[$var] = $location[$var];
            } else {
                $redisplay[$var] = "";
            }
        }
        
        return $redisplay;
        
    }
        
    ######################################################################################
    # Method: displayErrors($indexes,$location)
    # Arguments:
    #    - $arr  (Array)  :: Array to turn into JSON
    #    - $type    (String) :: Whether the current object should become a JSON "object" or
    #        JSON "array".  This is based on whether the key starts with _ or not.
    # Purpose: Builds a best guess JSON representation from a PHP Array
    ######################################################################################        
    public static function array2JSON($arr,$type="OBJ") {
        $json = "";$first = 1;
        foreach($arr as $key=>$val) {
            $json .= ($first-- == 1 ? "" : ",") . ($type=="OBJ" ? $key.":" : "");
            if(is_array($val)) {
                $json .= (substr($key,0,1)!="_" && is_array($val) ? 
                    Formatter::array2JSON($val) : Formatter::array2JSON($val,"ARRAY"));
            } else {
                $json .= "'" . $val . "'";
            }
        }return ($type=="OBJ" ? "{".$json."}" : "[".$json."]");
    }

    
}

?>