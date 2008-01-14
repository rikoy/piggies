<?php

define("FILE_DEPTH", "");
require_once(FILE_DEPTH . "settings.php");

define("HOST", "localhost");
define("USER", "root");
define("PASS", "root");
$connection =   array(
                    "host"=>HOST,
                    "user"=>USER,
                    "pass"=>PASS
                );

$e = new DatabaseEngine("piggy_accounts","piggy",$connection);

?>