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
	
		<!-- STATE VARIABLES -->
		<input type="hidden" id="hGameID" value="" />
		
		<!-- SIDEBAR -->
		<div class="sidebar">
			<?php 
                $sidebar = new miniBoxScores( date("m-d-Y") ); 
                echo $sidebar->create_interface();
            ?>
		</div>
		
		<!-- CONTENT -->
		<div class="content">
			
			<div class="boxscore">
				<table>
					<tr class="innings">
						<td class="spacer">&nbsp;</td>
						<td>1</td>
						<td>2</td>
						<td>3</td>						
						<td>4</td>
						<td>5</td>
						<td>6</td>
						<td>7</td>
						<td>8</td>
						<td>9</td>
						<td class="team_score">R</td>
						<td class="team_hits">H</td>
						<td class="team_errors">E</td>
					</tr>
					<tr class="away_team">
						<td class="team_name">
							Cubs
						</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td class="team_score">
							12
						</td>
						<td class="team_hits">
							14
						</td>
						<td class="team_errors">
							0
						</td>
					</tr>
					<tr class="home_team">
						<td class="team_name">
							Reds
						</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>						
						<td class="team_score">
							3
						</td>
						<td class="team_hits">
							6
						</td>
						<td class="team_errors">
							1
						</td>
					</tr>
				</table>
</div>
			
			
			
		</div>
	
	</body>

</html>