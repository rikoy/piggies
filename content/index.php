<?php

##########################################################################################
# File: index.php
# Modified:
#   - [01.09.2008 :: Keith]  Created.
# Purpose: Entry point of application.  Checks installation and configuration details
#   before forwarding the user appropriately.
##########################################################################################

?>

<h1>Welcome to Piggies</h1>

<?php
    /* Response variables */
    $hasError                 = false;
    $responseTemplate['good'] = "<span class='initialize_good'>[[msg]]</span>";
    $responseTemplate['bad']  = "<span class='initialize_bad'>[[msg]]</span>";
?>

<table>

    <!-- Looking for the config.php file -->
    <tr><td>Checking for a valid config.php file</td>
    <?php 
        if(!file_exists(FILE_DEPTH . "config.php")) {
            $status   = str_replace($responseTemplate['bad'],"[[msg]]","Failed!");
            $hasError = true;
        } else {
            $status = str_replace($responseTemplate['good'],"[[msg]]","Success!");
            require_once(FILE_DEPTH . "config.php");
        }
    ?>    
    <td><?php echo $status; ?></td></tr>
    <?php if($hasError) { echo "</table>";exit(); } ?>
    
    <!-- Making sure we have a define database type -->
    <tr><td>Checking for a valid database type</td>
    <?php 
        $validDBSystems = array("MYSQL");
        if(!defined("SYS_DB_TYPE") || !in_array(SYS_DB_TYPE,$validDBSystems)) {
            $status   = str_replace($responseTemplate['bad'],"[[msg]]","Failed!");
            $hasError = true;
        } else {
            $status = str_replace($responseTemplate['good'],"[[msg]]","Success!");
        }
    ?>    
    <td><?php echo $status; ?></td></tr>
    <?php if($hasError) { echo "</table>";exit(); } ?>
    
    <!-- Validating the database connection -->
    <tr><td>Verifying the database connection</td>
    <?php 
        $obj = new SystemRoot;
        try {
            $obj->verifyDatabase();
        } catch(SystemException $ex) {
            $status   = str_replace($responseTemplate['bad'],"[[msg]]","Failed!");
            $hasError = true;
        } catch(DatabaseException $ex) {
            $status   = str_replace($responseTemplate['bad'],"[[msg]]","Failed!");
            $hasError = true;
        }
    ?>    
    <td><?php echo $status; ?></td></tr>
    <?php if($hasError) { echo "</table>";exit(); } ?>
    
</table>