<?php

##########################################################################################
# File: index.php
# Modified:
#   - [01.09.2008 :: Keith]  Created.
# Purpose: Entry point of application
##########################################################################################

/* Make sure the settings.phpMyWallet.ini exists */
if(!file_exists(FILE_DEPTH . "settings.phpMyWallet.ini")) {
	echo "<p>We could not find your settings.phpMyWallet.ini file.  Use the 
		template to create your settings file and then refresh the page.</p>";
	exit();
}

/* Try to create an object */
try {
	$systemObj = new phpMyWallet("phpMyWallet");
} catch(ExceptionDB $ex) {
	echo "<p>We could not connect to the database you provided in your 
		settings.phpMyWallet.ini file.</p>";
	exit();
}

?>