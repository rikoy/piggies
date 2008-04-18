<?php

##########################################################################################
# BoxScores
# Purpose: Extracts information from XML and initializes Boxscore UI or sends
#	JSON for update.
##########################################################################################

class boxScores {

	######################################################################################
	# MEMBERS
	######################################################################################
	private $basePath = "http://gd2.mlb.com/components/game/mlb/";
	private $parser;
	private $data;

	######################################################################################
	# __construct(...)
	# Purpose: Grabs feed and parses
	# @arg	$_date	MM-DD-YYYY
	######################################################################################
	public function __construct($_game,$_date) {
	
		/* Disassemble Date */
		list($m,$d,$y) = explode("-",$_date);
	
		/* Construct XML Path */
		$xmlPath = $this->basePath . 
					"year_{$y}/month_{$m}/day_{$d}/gid_{$_game}/linescore.xml";
		
		/* Get Raw Data */
		ob_start();
		$c = curl_init($xmlPath);curl_exec($c);curl_close($c);
		$xml = ob_get_clean();
		
		/* Create XML Parser and register tag handler */
		$this->parser = xml_parser_create();
		xml_set_object($this->parser, $this);
        xml_set_element_handler($this->parser, "tag_open", null);

        /* Parse XML into array */
        $this->data = array();
        xml_parse($this->parser,$xml);

        //echo $xml;
        //print_r($this->data);
        
	}
	
	######################################################################################
	# tag_open(...)
	# Purpose: Processes a tag opening
	# @arg     $parser         XML Parser reference
	# @arg     $tag            Name of the tag being processed
	# @arg     $attr           Array of attributes and values found in tag
	######################################################################################
	private function tag_open($parser,$tag,$attr) {
	    
	    /* Process tags by name */
	    switch($tag) {
	        
	        case "GAME":   		// ADD GAME TO DATA
				$this->data["status"]    = $this->calculate_status($attr);
				$this->data["inning"]    = $attr["INNING"];
				$this->data["away_runs"] = $attr["AWAY_TEAM_RUNS"];
				$this->data["away_hits"] = $attr["AWAY_TEAM_HITS"];
				$this->data["away_errs"] = $attr["AWAY_TEAM_ERRORS"];
				$this->data["home_runs"] = $attr["HOME_TEAM_RUNS"];
				$this->data["home_hits"] = $attr["HOME_TEAM_RUNS"];
				$this->data["home_errs"] = $attr["HOME_TEAM_RUNS"];
												
	        	break;
	        	
	        case "LINESCORE":	// BOX SCORE DATA
	        	$this->data["innings"][ $attr["INNING"] ] = 
	        		array(
	        			"away_score" => $attr["AWAY_INNING_RUNS"],
	        			"home_score" => $attr["HOME_INNING_RUNS"]
	        		);
	        	break;
	        
	        default:
                break;
	        
	    }
	    
	}
	
	######################################################################################
	# calculate_status(...)
	# Purpose: Returns the string for status for pre, during, and post game
	# @arg     &$attr      Reference to a tags attributes
	######################################################################################
	private function calculate_status(&$attr) {
	    
	    $ending = array(
	       "0" => "th",
	       "1" => "st",
	       "2" => "nd",
	       "3" => "rd",
	       "4" => "th",
	       "5" => "th",
	       "6" => "th",
	       "7" => "th",
	       "8" => "th",
	       "9" => "th",
	    );
	    
	    $status = $attr["STATUS"];
	    $top    = (isset($attr["TOP_INNING"]) ? $attr["TOP_INNING"] : "" );
	    $inning = (isset($attr["INNING"])     ? $attr["INNING"] : "" );
	    $time   = $attr["TIME"].$attr["AMPM"]." (".$attr["TIME_ZONE"].")";
	    
	    if($status == "Final") {
	        return "Final";
	    } elseif($status != "Preview") {
	        return ($top == "Y" ? "Top of the " : "Bot of the ") . $inning . 
	        	$ending[ $inning % 10 ];
	    } else {
	        return $time;
	    }
	    
	}

	######################################################################################
	# get_update(...)
	# Purpose: Returns array representing sidebar updated data
	######################################################################################
	public function get_update() {
	    
		return $this->data;
	    
	}

	
}

?>