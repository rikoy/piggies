<?php

##########################################################################################
# Class: Validator
# Modified:
#   - [06.07.2007 :: Keith]  Created.
#   - [07.12.2007 :: Alex]  Added isSpamInjectionArray(...) and isSpamInjection(...).
#   - [07.12.2007 :: Alex]  Expanded isSpamInjection(...) to account for if its passed an 
#		array.
#	- [07.16.2007 :: Keith]  Added isDate(...).
#	- [07.16.2007 :: Keith]  Added isMySQLDate(...).
#	- [07.16.2007 :: Keith]  Added isAmericanDate(...).
#	- [07.17.2007 :: Keith]  Added isAmericanPhoneNumber(...). 
# Purpose: Contains static methods used for validating arguments in the system.
##########################################################################################

class Validator extends UtilityObj {

	######################################################################################
	# Members
	######################################################################################
	public static $errors;

	######################################################################################
	# Method: exists(...)
	# Arguments:
	#   - $name (string) :: How to reference the variable in error
	#   - $var  (string) :: Variable to check existance of
	# Purpose: Stores error if asserted variable doesn't exist
	######################################################################################
	public static function existsInArray($arr,$name,$index) {
		if(!isset($arr[$index])) {
			self::$errors[] = "Parameter $name is required";
			return false;
		}
		return true;
	}
	
	######################################################################################
	# Method: isEmail(...)
	# Arguments:
	#   - $value (string) :: Value to test as an email
	# Purpose: Stores error if asserted variable isn't an email
	######################################################################################
	public static function isEmail($value) {
		$pattern  = "/^([a-zA-Z0-9_'+*$%\^&!\.\-])";
		$pattern .= "+\@(([a-zA-Z0-9\-])+\.)";
		$pattern .= "+([a-zA-Z0-9:]{2,4})+$/";
		if(!preg_match($pattern,$value)) {
			self::$errors[] = "Invalid email: $value";
			return false;
		}
		return true;
	}

	######################################################################################
	# Method: 
	# Arguments:
	#   - $value (string) :: Value to test as a string with some length
	#	- $name  (string) :: Name of the field being tested (for display)
	# Purpose: Stores error if asserted variable doesn't have SOME value
	######################################################################################	
	public static function hasValue($value, $name) {
		if(!strlen(trim($value)) > 0) {
			self::$errors[] = "Please provide your $name.";
			return false;
		}
		return true;
	}
	
	######################################################################################
	# Method: isUnsignedInteger(...)
	# Arguments:
	#   - $value (string) :: Value to test as an Integer
	# Purpose: Stores error if asserted variable isn't an unsigned (positive) integer
	######################################################################################
	public static function isUnsignedInteger($value) {
		$pattern  = "/^[0-9]+/";
		if(!preg_match($pattern,$value)) {
			self::$errors[] = "Invalid integer: $value";
			return false;
		}
		return true;
	}

	######################################################################################
	# Method: hasLength(...)
	# Arguments:
	#   - $value (string) :: Value to test length
	#	- $min   (int)    :: Minimum value of the string
	#	- $max   (int)    :: Maximum value of the string (-1 means no limit)
	# Purpose: Stores error if asserted variable isn't in the length range
	######################################################################################
	public static function hasLength($value,$min = -1,$max = -1) {
		if( strlen($value) < $min || ( $max > 0 && strlen($value) > $max ) ) {
			self::$errors[] = "Invalid length: $value";
			return false;
		}
		return true;
	}

	######################################################################################
	# Method: isArray(...)
	# Arguments:
	#   - $arr (array) :: Array to test
	#	- $min (int)   :: Number of elements to test for (at least)
	#	- $max (int)   :: Number of elements to test for (at most : -1 means no limit)
	# Purpose: Stores error if asserted variable isn't in the element range
	######################################################################################
	public static function isArray($arr,$min = -1,$max = -1) {
		if(!is_array($arr)) {
			self::$errors[] = "Asserted variable is NOT an array";
			return false;
		}elseif(($min>0&&count($arr)<$min)||($max>0&&count($arr)>$max)) {
			self::$errors[] = "Invalid number of elements: " . count($arr);
			return false;
		}
		return true;
	}

	######################################################################################
	# Method: isDate(...)
	# Arguments:
	#   - $day   (int) :: day to test as date
	#	- $month (int) :: month to test as date
	#	- $year  (int) :: year to test as date
	# Purpose: Stores error if asserted variable isn't a date
	######################################################################################
	public static function isDate($day,$month,$year) {
		
		/* Create display date (standard format) */
		$displayDate = $month . "-" . $day . "-" . $year;
		
		/* Make sure that we have a valid numeric value for year */
		switch(intval($month)) {

			/* 30 days */
			case 4: case 6: case 9: case 11:
				
				/* Make sure that we have a valid numeric value for day */
				if(!is_numeric($day) || intval($day) < 1 || intval($day) > 30) {
					self::$errors[] = "Date is malformed {$displayDate}";
					return false;
				}
				
				break;
				
			/* 31 days */
			case 1: case 3: case 5: case 7: case 8: case 10: case 12:

				/* Make sure that we have a valid numeric value for day */
				if(!is_numeric($day) || intval($day) < 1 || intval($day) > 31) {
					self::$errors[] = "Date is malformed {$displayDate}";
					return false;
				}

				break;
				
			/* February */
			case 2:
				
				/* Make sure that we have a valid numeric value for day (leap year!) */
				if((($year % 4) == 0) && ((($year % 100) != 0) || (($year % 400) == 0))) {
					if(!is_numeric($day) || intval($day) < 1 || intval($day) > 29) {
						self::$errors[] = "Date is malformed {$displayDate}";
						return false;
					}		
				} else {
					if(!is_numeric($day) || intval($day) < 1 || intval($day) > 28) {
						self::$errors[] = "Date is malformed {$displayDate}";
						return false;
					}									
				}
				
				break;
				
			default:
				
				self::$errors[] = "Date is malformed {$displayDate}";
				return false;
				
		}
		return true;
	}
	
	######################################################################################
	# Method: isMySQLDate(...)
	# Arguments:
	#   - $value (string) :: Value to test as date
	# Purpose: Stores error if asserted variable isn't a date (YYYY-MM-DD)
	######################################################################################
	public static function isMySQLDate($value,$name="Date") {

		/* Create array of permitted delimiters */
		$permitted = array("-","\/");
		
		/* Create pattern for date format matching */
		$pattern = "/^([1-9][0-9]{3})(" . implode("|",$permitted) . 
			")([0]?[1-9]|1[0-2])(\\2)([0]?[1-9]|[1-2][0-9]|3[0-1])$/";

		/* Validate format */
		if(!preg_match($pattern,$value,$matches)) {
			self::$errors[] = "$name is invalid (YYYY-MM-DD)";
			return false;
		}
		
		/* Pull delimiter */
		$delimiter = $matches[2];
		
		/* Parse our date */
		list($year,$month,$day) = explode($delimiter,$value);
		
		/* Call generic function */
		return self::isDate($day,$month,$year);
		
	}
	
	######################################################################################
	# Method: isAmericanDate(...)
	# Arguments:
	#   - $value (string) :: Value to test as date
	# Purpose: Stores error if asserted variable isn't a date (MM-DD-YYYY)
	######################################################################################
	public static function isAmericanDate($value,$name="Date") {

		/* Create array of permitted delimiters */
		$permitted = array("-","\/");
		
		/* Create pattern for date format matching */
		$pattern = "/^([0]?[1-9]|1[0-2])(" . implode("|",$permitted) . 
			")([0]?[1-9]|[1-2][0-9]|3[0-1])(\\2)([1-9][0-9]{3})$/";
		
		/* Validate format */
		if(!preg_match($pattern,$value,$matches)) {
			self::$errors[] = "$name is invalid (ex: MM-DD-YYYY)";
			return false;
		}
		
		/* Pull delimiter */
		$delimiter = $matches[2];
		
		/* Parse our date */
		list($month,$day,$year) = explode($delimiter,$value);
		
		/* Call generic function */
		return self::isDate($day,$month,$year);
		
	}
	
	######################################################################################
	# Method: isSSN(...)
	# Arguments:
	#   - $value (string) :: Value to test as date
	# Purpose: Stores error if asserted variable isn't a SSN (XXX-XX-XXXX)
	######################################################################################
	public static function isSSN($value) {

		/* Create pattern for SSN format matching */
		$pattern = "/[0-9]{3}-[0-9]{2}-[0-9]{4}/";
		
		/* Validate format */
		if(!preg_match($pattern,$value)) {
			self::$errors[] = "SSN is invalid (ex: 111-22-3333)";
			return false;
		}
		return false;
		
	}	
	
	######################################################################################
	# Method: isSpamInjection(...)
	# Arguments:
	#   - $item (string) :: Value to test as spam injection
	# Purpose: Stores error if asserted variable is suspected spam
	######################################################################################
	public static function isSpamInjection($item){
		
		if(stristr($item, "MIME-Version:")) {
            self::$errors[] = "Possible Spam Injection: " . 
            	htmlentities(substr($item,1,10) . "...");
            return true;
        } else if(stristr($item, "Content-Type:")) { 
            self::$errors[] = "Possible Spam Injection: " . 
            	htmlentities(substr($item,1,10) . "...");
            return true;
        } else if(stristr($item, "Content-Transfer-Encoding:")) { 
            self::$errors[] = "Possible Spam Injection: " . 
            	htmlentities(substr($item,1,10) . "...");
            return true;
        } else if(preg_match("/<\s*script\s*(src)?/",$item)) {
        	self::$errors[] = "Possible Spam Injection: " . 
            	htmlentities(substr($item,1,10) . "...");
        	return true;
        } else if(preg_match("/<\s*a\s*(href)?/",$item)) {
        	self::$errors[] = "Possible Spam Injection: " . 
            	htmlentities(substr($item,1,10) . "...");
        	return true;
        } else if(preg_match("/<\s*img\s*(src)?/",$item)) {
        	self::$errors[] = "Possible Spam Injection: " . 
            	htmlentities(substr($item,1,10) . "...");
        	return true;
        } else if(preg_match("/\[\s*url\s*=/",$item)) {
        	self::$errors[] = "Possible Spam Injection: " . 
            	htmlentities(substr($item,1,10) . "...");
        	return true;
        }
        
		return false;
		
	}
	
	######################################################################################
	# Method: isSpamInjectionArray(...)
	# Arguments:
	#   - $items (array) :: Values to test as spam injection
	# Purpose: Stores error if any of the asserted variable is suspected spam
	######################################################################################
	public static function isSpamInjectionArray($items){

		if(!isset($items) || !is_array($items)) { return false; }
		
		foreach($items as $aitem){

			if(is_array($aitem)) {
				self::isSpamInjectionArray($aitem);
			} else {
				if(self::isSpamInjection($aitem)) {
					return true;
				}
			}
		
		}
		
		return false;

	}
	
	######################################################################################
	# Method: isAmericanPhoneNumber(...)
	# Arguments:
	#   - $value (string) :: Values to test as american phone number
	# Purpose: Stores error if any of the asserted variable is not phone number
	######################################################################################	
	public static function isAmericanPhoneNumber($value,$name="Phone number") {
		
		/* Create pattern for date format matching */
		$pattern = "/^([0-9]{3}|\([0-9]{3}\))(-|\s)[0-9]{3}-[0-9]{4}$/";
		
		/* Validate format */
		if(!preg_match($pattern,$value,$matches)) {
			self::$errors[] = "$name is invalid (ex: 111-222-3333)";
			return false;
		}
		return false;
		
	}
	
	######################################################################################
	# Method: clearErrors(...)
	# Arguments: <none>
	# Purpose: Clears errors already stored
	######################################################################################
	public static function clearErrors() {
		self::$errors = array();
	}
	
	######################################################################################
	# Method: hasErrors(...)
	# Arguments: <none>
	# Purpose: Returns TRUE if errors exist
	######################################################################################
	public static function hasErrors() {
		if(count(self::$errors)>0) { return true; } else { return false; }
	}

	######################################################################################
	# Method: getErrors(...)
	# Arguments: <none>
	# Purpose: Returns the errors in a listed string.
	######################################################################################
	public static function getErrors() {

		/* Return errors as list */
		$errList = implode("<br />",self::$errors);

		/* Clear errors */
		self::clearErrors();
		
		/* Return string */
		return $errList;
		
	}
			
}

?>