<?php

##########################################################################################
# Class: Emailer
# Modified:
#   - [06.07.2007 :: Keith]  Created.
#    - [07.06.2007 :: Keith]  Diversified sendmail(...) parameter $params['to'] so that
#        it could be either an array of addresses or a scalar address.
# Purpose: Contains static methods used for emaiing in the system.
##########################################################################################

class Emailer extends UtilityObj {
    
    ######################################################################################
    # Method: sendmail(...)
    # Arguments:
    #   - $params (array) :: Array of parameters for phpmailer.  Possible indexes are
    #        to (req), from (req), subject (req), message (req), fromname, host, isHTML,
    #        altmessage.
    # Purpose: Uses phpmailer(...) to send an email
    ######################################################################################
    public static function sendmail($params) {
    
        /* Make sure we were sent an array */
        Validator::isArray($params);
        if(Validator::hasErrors()) {
            throw new ExceptionParameters(Validator::getErrors());
        }
        
        /* Validate the minimums exist (to,from,subject,message) */
        Validator::existsInArray($params,'To',      'to');
        Validator::existsInArray($params,'From',    'from');
        Validator::existsInArray($params,'Subject', 'subject');
        Validator::existsInArray($params,'Message', 'message');
        if(Validator::hasErrors()) {
            throw new ExceptionValidation(Validator::getErrors());
        }
        
        /* Make sure the from email is valid */
        Validator::isEmail($params['from']);
        if(Validator::hasErrors()) {
            throw new ExceptionValidation(Validator::getErrors());
        }
        
        /* Make sure the to emails are valid */
        if(!is_array($params['to'])) {
            Validator::isEmail($params['to']);
        } else {
            foreach($params['to'] as $addy) {
                Validator::isEmail($addy);
            }
        }
        if(Validator::hasErrors()) {
            throw new ExceptionValidation(Validator::getErrors());
        }
    
        /* Set arguments and send email */
        $mail = new PHPMailer();

        if(isset($params['isHTML'])) {
            $mail->isHTML(true);
        }
        $mail->From     = $params['from'];
        $mail->FromName = ( isset($params['fromname']) ? $params['fromname'] : "" );
        $mail->Host     = ( isset($params['host']) ? $params['host'] : "localhost" );
        $mail->Subject  = $params['subject'];
        $mail->Body     = $params['message'];
        $mail->AltBody    = ( isset($params['altmessage']) ? $params['altmessage'] : "");
        if(!is_array($params['to'])) {
            $mail->AddAddress($params['to']);
        } else {
            foreach($params['to'] as $addy) {
                $mail->AddAddress($addy);
            }            
        }
        
        /* Send email */
        if(!$mail->Send()) {
              throw new ExceptionError("There has been a mail error sending!");
        }
    
    }
    
}

?>
