<?php

################################################################################
# SUDOKU FORM
################################################################################

$leftPadding = "padding-left: 10px;";
$topPadding  = "padding-top: 10px;";
$center      = "text-align: center;";

echo "<form method=\"POST\" action=\"script_sudoku.php\"><table>";
for($i=0;$i<9;$i++) {
	echo "<tr>";
	for($j=0;$j<9;$j++) {
		$pad = ""; 
		if($j % 3 == 0 && $j != 0) { $pad = $leftPadding; }
		if($i % 3 == 0 && $i != 0) { $pad .= $topPadding; }
		
		echo "<td style=\"{$pad};\">";
		echo "<input type=\"text\" name=\"space[]\" size=\"2\" />";
		echo "</td>";
	}
	echo "</tr>";
}

echo "<tr>";
echo "<td colspan=\"9\" style=\"$center\">";
echo "<input type=\"reset\" value=\"Reset Puzzle!\" />";
echo "<input type=\"submit\" value=\"Solve Puzzle!\" />";
echo "</td>";
echo "</tr>";
echo "</table></form>";

?>