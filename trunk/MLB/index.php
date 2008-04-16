<?php

ini_set("display_errors",1);
	
##########################################################################################
# CLASSES
##########################################################################################
require("classes/miniboxscores.class.php");

?>

<html>

	<head>
		<title>Real-Time MLB Stats</title>
		
		<script language="javascript" src="scripts/jquery.js"></script>
		<script language="javascript" src="scripts/mlb.js"></script>
		
		<link rel="stylesheet" type="text/css" href="css/site.css" />
		
		<script language="javascript">
            $(document).ready(
                function(){
                    initAJAX();
                    initSidebarUI();
                }
            );
		</script>
		
	</head>

	<body>
	
		<div class="sidebar">
			<?php 
                $sidebar = new miniBoxScores( date("m-d-Y") ); 
                echo $sidebar->create_interface();
            ?>
		</div>
		
		<div class="content">
			test
		</div>
	
	</body>

</html>