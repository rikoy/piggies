<?php

ini_set("display_errors",1);

######################################################################################
# array2JSON
# Purpose: Builds a best guess JSON representation from a PHP Array
# @args    $arr    Array to translate
# @args    $type   OBJ or ARRAY, chosen by key name (_x is array, x is object)
######################################################################################		
function array2JSON($arr,$type="OBJ") {
	$json = "";$first = 1;
	foreach($arr as $key=>$val) {
		$json .= ($first-- == 1 ? "" : ",") . ($type=="OBJ" ? $key.":" : "");
		if(is_array($val)) {
			$json .= (substr($key,0,1)!="_" && is_array($val) ? 
				array2JSON($val) : array2JSON($val,"ARRAY"));
		} else {
			$json .= "'" . $val . "'";
		}
	}return ($type=="OBJ" ? "{".$json."}" : "[".$json."]");
}

##########################################################################################
# CLASSES
##########################################################################################
require("../classes/miniboxscores.class.php");
require("../classes/boxscores.class.php");

##########################################################################################
# server.php
# Purpose: Returns JSON encoded string with data
##########################################################################################
switch($_REQUEST["rqst"]) {
    
    ######################################################################################
    # SIDEBAR
    ######################################################################################
    case "sidebar" :
    
        $obj = new miniBoxScores( date("m-d-Y") );
        echo array2json($obj->get_update());
        
        break;
        
    case "boxscore":
    
    	$obj = new boxScores( $_REQUEST["game"], date("m-d-Y") );
    	echo array2json($obj->get_update());
        
    default:
    
}

?>