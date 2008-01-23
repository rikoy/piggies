<?php

##########################################################################################
# File: functions.php
# Modified:
#   - [01.15.2008 :: Keith]  Created.
# Purpose: Collection of basic functions used through the application
##########################################################################################

function kSystem_currency($amount) {
	return ( $amount >= 0 ? sprintf('$%.2f',$amount) : sprintf('-$%.2f',abs($amount)) );
}

function kSystem_date($ts) {
	return date("m/d/Y",$ts);
}

function kSystem_redirect($filename) {
    
    /* If the buffers are full, flush them */
    if( ob_get_length() > 0 ) {
        ob_flush();
    }
		
    /* If we haven't sent any headers, use the preferred method */
    if( !headers_sent() ) {
        header("location: $filename");
        exit();
    } else {
        echo "<meta http-equiv=\"refresh\" content=\"0;url=$filename\">";
        exit();
    }
    
}


?>