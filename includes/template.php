<?php

##########################################################################################
# File: template.php
# Modified:
#   - [01.09.2008 :: Keith]  Created.
# Purpose: Template of the main interface
##########################################################################################

?>

<html>
    <head>
        <title>TITLE</title>
        <link type="text/css" href="<?php echo FILE_DEPTH; ?>styles/main.css" rel="stylesheet" />
        <script type="text/javascript" src="<?php echo FILE_DEPTH; ?>scripts/jquery.js"></script>
    </head>

    <body>
        <?php require_once($content); ?>
    </body>    
    
</html>