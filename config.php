<?php

##########################################################################################
# File: config.php
# Modified:
#   - [06.08.2007 :: Keith]  Created.
# Purpose: This file holds all the configuration details needed to run [[Application]]
##########################################################################################

/* (1) Choose the type of database you are going to use and uncomment that line */
define("SYS_DB_TYPE", "MySQL");

/* (2) Define the database connection settings */
define("SYS_DB_HOST", "localhost");
define("SYS_DB_NAME", "piggy");
define("SYS_DB_USER", "root");
define("SYS_DB_PASS", "root");

/* (3) Define a table prefix.  This is only needed if you want to run more than one
    instance of [[Application]] using the same database. */
define("SYS_DB_PREFIX","piggy_");

?>