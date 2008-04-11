<?php

################################################################################
# MODES
################################################################################
define("DEBUG", 3);

function out($msg,$level=1) {
	if($level <= DEBUG) {
		$timeDisplay = date("H:i:s");
		echo "<p style=\"width:600px;margin:0 0 0 25px;text-indent:-15px;\">" . 
			$timeDisplay . " - " . $msg . "</p>";
	}
}

################################################################################
# SUDOKU CLASS
################################################################################
class SudokuSolver {

    #############   SAMPLE GRID
	# 0 # 1 # 2 #	-------------
	#############   - This is how the cubes are laid out 
	# 3 # 4 # 5 #	  in an example puzzle.
	#############   - Rows are labeled 0 to 8 from top to bottom.
	# 6 # 7 # 8 # 	- Columns are labeled 0 to 8 from left to right.
	#############   
    
    private $_puzzle;
    
    ############################################################################
	# CONSTRUCTOR
	############################################################################
    public function __construct($s) {
        
        out("...BEGIN __construct(...)",1);
		
		/* Error if the parameters are wrong */
		if(!is_array($s) && count($s) != 81) {
			out("ERROR.  Bad Argument in SudokuPuzzle::__construct(...)",0);
			exit();
		}
     
        /* Loop through array and create squares */
        $this->_puzzle = "";
		for($y=0;$y<9;$y++) {
		    
			for($x=0;$x<9;$x++) {
			    
				if(is_numeric($s[(9*$y)+$x]))
				    $this->_puzzle .= "{".$x.$this->_getCubeByCoord($x,$y).$y.":".$s[(9*$y)+$x]."}";
				else
				    $this->_puzzle .= "{".$x.$this->_getCubeByCoord($x,$y).$y.":"."}";

			}
			
			$this->_puzzle .= "<br />";
			
		}
		
		/* Create initial state */
		echo $this->_build();
		
		out("...END __construct(...)",1);
		 
    }
    
    ############################################################################
	# PUBLIC INTERFACE
	############################################################################
	public function solvePuzzle() {
		
		out("...BEGIN solvePuzzle(...)",1);
		
		$strategies = array(
			"exposeNakedPairs"
		);
		
		do {
            
		    $wasSolved = $this->_isSolved();$this->_build();
			foreach($strategies as $strategy) {
				out("......BEGIN _strategy_{$strategy}(...)",2);
				
				$func = "_strategy_".$strategy;
				if($this->$func()) { break; }
				
				out("......END _strategy_{$strategy}(...)",2);
			}
			
		} while($this->_isSolved() > $wasSolved);
		
		out("...END solvePuzzle(...) SOLVED = ".$this->_isSolved()."/81",1);
		
	}
	public function showSolution() {
	    
	    
	    echo $this->_puzzle . "<br /><br /><br />";
	    
	    $leftPadding = "padding-left: 25px;";
        $topPadding  = "padding-top: 25px;";
        $center      = "text-align: center;";
	    
	    preg_match_all("/{\d\d\d:(\d+)}/",$this->_puzzle,$m,PREG_SET_ORDER);

	    echo "<table>";
	    $x = 0;
        for($i=0;$i<9;$i++) {
            echo "<tr>";
            for($j=0;$j<9;$j++) {
                $pad = ""; 
                if($j % 3 == 0 && $j != 0) { $pad = $leftPadding; }
                if($i % 3 == 0 && $i != 0) { $pad .= $topPadding; }

                echo "<td style=\"{$pad};\">";
                echo $m[$x++][1];
                echo "</td>";
            }
            echo "</tr>";
        }	    
	    echo "</table>";
	    
	    
	}
    
	############################################################################
	# PRIVATE INTERFACE - SOLUTION STRATEGIES
	############################################################################
	private function _strategy_exposeNakedPairs() {
	    
	    
	    
	}
	
    ############################################################################
	# PRIVATE INTERFACE - STATE MODELING FUNCTIONS
	############################################################################
	private function _build() {
	    
	    /* Loop through all unsolved squares and build state */
	    if(preg_match_all("/{(\d)(\d)(\d):(\d\d+)?}/", $this->_puzzle, $m, PREG_SET_ORDER)) {

	        foreach($m as $i => $match) {
                
	            /* Gather all the solved square values linked to unsolved square */
                preg_match_all(
                    "/{(?:".$match[1]."\d\d|\d".$match[2]."\d|\d\d".$match[3]."):(\d)}/",
                    $this->_puzzle,$m2,
                    PREG_PATTERN_ORDER
                );
                
                /* Create a list of candidates */
	            $candidates = implode("",array_diff(range(1,9,1),$m2[1]));
	            
	            /* Replace unsolved square with updated candidates */
	            $this->_puzzle = preg_replace(
	               "/{".$match[1].$match[2].$match[3].":(\d\d+)?}/",
	               "{".$match[1].$match[2].$match[3].":".$candidates."}",
	               $this->_puzzle
                );
	            
	        }
	    
	    }
	    
	}

    ############################################################################
	# PRIVATE INTERFACE - HELPER FUNCTIONS
	############################################################################
    private function _getCubeByCoord($x,$y) {
		switch($x) {
			case 0:case 1:case 2:
				if($y <= 2) { return 0; }
				if($y >= 3 && $y <= 5) { return 3; }
				if($y >= 6 && $y <= 8) { return 6; }
				break;
			case 3:case 4:case 5:
				if($y <= 2) { return 1; }
				if($y >= 3 && $y <= 5) { return 4; }
				if($y >= 6 && $y <= 8) { return 7; }
				break;	
			case 6:case 7:case 8:
				if($y <= 2) { return 2; }
				if($y >= 3 && $y <= 5) { return 5; }
				if($y >= 6 && $y <= 8) { return 8; }
				break;
		}
	}
    private function _isSolved() {
        return preg_match_all("/{\d\d\d:\d}/",$this->_puzzle,$m);
    }

}

################################################################################
# SUDOKU SCRIPT
################################################################################

out("Starting",0);

list($_startMSec,$_startSec) = explode(" ",microtime());

$board = new SudokuSolver($_POST['space']);
$board->solvePuzzle();

list($_endMSec,$_endSec) = explode(" ",microtime());

$_sec  = intval($_endSec) - intval($_startSec);
$_msec = floatval($_endMSec) - floatval($_startMSec);
$_msec = ( $_msec < 0 ? $_msec * -1 : $_msec );

out("Ending [ " . ($_sec + $_msec) . " secs ]",0);

echo "<br /><br >";$board->showSolution();

?>