<?php
	ini_set("display_errors",1);
	require("classes/miniboxscores.class.php");
?>

<html>

	<head>
		<title>Real-Time MLB Stats</title>
		<script language="javascript" src="scripts/jquery.js"></script>
		<script language="javascript" src="scripts/mlb.js"></script>
		<link rel="stylesheet" type="text/css" href="css/site.css" />
	</head>

	<body>
	
		<div class="sidebar">
			<?php 
                $sidebar = new miniBoxScores( date("m-d-Y") ); 
                echo $sidebar->create_interface();
            ?>
            <script>initSidebarUI();</script>
		</div>
		
		<div class="content">
			test
		</div>
	
	</body>

</html>