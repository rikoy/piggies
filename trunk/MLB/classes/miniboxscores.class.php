<?php

##########################################################################################
# MiniBoxScores
# Purpose: Extracts information from XML and initializes Mini Boxscore UI or sends
#	JSON for update.
##########################################################################################

class miniBoxScores {

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
	public function __construct($_date) {
	
		/* Disassemble Date */
		list($m,$d,$y) = explode("-",$_date);
	
		/* Construct XML Path */
		$xmlPath = $this->basePath . "year_{$y}/month_{$m}/day_{$d}/miniscoreboard.xml";
		
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
	        
	        case "GAME":   // ADD GAME TO DATA
	        
	            $this->data[] =    
                    array(
                        "[[game_id]]"   => $attr["GAME_PK"],
                        "[[link]]"      => $attr["GAMEDAY_LINK"],
                        "[[home_id]]"   => $attr["HOME_TEAM_ID"],
                        "[[home_name]]" => $attr["HOME_TEAM_NAME"],
                        "[[home_runs]]" => isset($attr["HOME_TEAM_RUNS"]) ? intval($attr["HOME_TEAM_RUNS"]) : 0,
                        "[[away_id]]"   => $attr["AWAY_TEAM_ID"],
                        "[[away_name]]" => $attr["AWAY_TEAM_NAME"],
                        "[[away_runs]]" => isset($attr["AWAY_TEAM_RUNS"]) ? intval($attr["AWAY_TEAM_RUNS"]) : 0,
                        "[[status]]"    => $this->calculate_status($attr)
                    );break;
	        
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
	        return ($top == "Y" ? "Top of the " : "Bot of the ") . $inning . $ending[ $inning % 10 ];
	    } else {
	        return $time;
	    }
	    
	    
	    
	}
	
    ######################################################################################
	# create_interface(...)
	# Purpose: Returns the string code for sidebar box scores element
	######################################################################################
	public function create_interface() {
	    
	    $html = "";                                        // REPRESENTS TOTAL HTML
	    $elem = file_get_contents("ui/ui.minibox.htm");    // REPRESENTS UI ELEMENT
	    
	    foreach($this->data as $game) {
            
            $html .=    str_replace(
                            array_keys($game),
                            array_values($game),
                            $elem
                        );

	    }
	    
	    return $html;
	    
	}
	
	######################################################################################
	# get_update(...)
	# Purpose: Returns array representing sidebar updated data
	######################################################################################
	public function get_update() {
	    
	    $json = array();
	    
	    foreach($this->data as $game) {
            
            $json[$game["[[game_id]]"]] = 
                array(
                    "status"    => $game["[[status]]"],
                    "away_runs" => $game["[[away_runs]]"],
                    "home_runs" => $game["[[home_runs]]"],
                );

	    }
	    
	    return $json;
	    
	}

}

?>