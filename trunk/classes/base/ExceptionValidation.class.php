<?php

##########################################################################################
# Class: ExceptionValidation
# Modified:
#   - [06.07.2007 :: Keith]  Created.
# Purpose: 
##########################################################################################

class ExceptionValidation extends ExceptionObj {

    ######################################################################################
    # Method: __construct(...)
    # Arguments:
    #   - $message (string) :: Message to display for user
    # Purpose: Creates a new application object
    ######################################################################################
    public function __construct( $message = null, $code = 0 ) {
    
        parent::__construct( $message, $code );
    
    }

    
    
}

?>
