<?php

##########################################################################################
# Class: Captcha
# Modified:
#   - [07.30.2007 :: Keith]  Created.
#    - [08.07.2007 :: Keith]     Created file key system to prevent bots generated 
#        CAPTCHA puzzle once, solving, and just posting with hidden vars set.
#    - [09.28.2007 :: Keith]  Created ASCII CAPTCHA.
#    - [09.28.2007 :: Keith]  Created a generic verifyCAPTCHA(...) method that, but 
#        maintained the individual verify methods so i CAN make a specific verify.
# Purpose: Contains static methods used for generating / verify Captcha sequences.
##########################################################################################

class Captcha extends UtilityObj {
    
    ######################################################################################
    # Method: generateColorCaptcha(...)
    # Arguments: <none>
    # Purpose: Generates a "sufficiently" difficult Captcha using colors.  The form
    #    generated has 10 different hidden values (called the solution hash) with 9 decoy
    #    solutions and a hash-generated index (with a daily rotation) that points to 
    #    the true solution.  The select-box contains the seeds used to generate the
    #    true solution hash.  When the form is submitted, we hash the provided solution
    #    seed provided and then compare it to the true solution.  There are a few holes in
    #    this Captcha technique: (1) a computer could grab the hex color, match it to my
    #    color "names" and the program a system to choose the correct color.
    ######################################################################################
    public static function generateColorCaptcha() {
        
        /* Define array of colors */
        $colors = array(
            array("name" => "red",    "hex" => "#FF0000", "seed" => "543896661168669"),
            array("name" => "green",  "hex" => "#00FF00", "seed" => "198348651316874"),
            array("name" => "orange", "hex" => "#FF9900", "seed" => "194553216813841"),
            array("name" => "purple", "hex" => "#990099", "seed" => "185639251616566"),
            array("name" => "black",  "hex" => "#000000", "seed" => "551738965116844"),
            array("name" => "pink",   "hex" => "#FF9999", "seed" => "188963348744213"),
            array("name" => "yellow", "hex" => "#FFFF00", "seed" => "654646873557369"),
            array("name" => "blue",   "hex" => "#0000FF", "seed" => "284699964357833"),
            array("name" => "grey",   "hex" => "#A9A9A9", "seed" => "831384569831368"),
            array("name" => "brown",  "hex" => "#DEB887", "seed" => "846516463468436")
        );
        
        /* Choose a solution */
        $solutionIndex = rand(0, count($colors) - 1);
        
        /* Generate solution HASH (sufficiently complicated) */
        $solutionHash  = self::doHash($colors[$solutionIndex]["seed"]);
                         
        /* Create HTML for puzzle with decoy hashes */
        $html = "";
        
        for($i=0;$i<50;$i++) {
            if((self::solutionIndex()) == $i) {
                $html .= "<input type=\"hidden\" name=\"cHash[]\" 
                            value=\"$solutionHash\" />";
            } else {
                $decoyHash = self::doHash($colors[$solutionIndex]["seed"] . rand(2,100));
                $html .= "<input type=\"hidden\" name=\"cHash[]\" 
                    value=\"$decoyHash\" />";
            }
        }
        
        /* Create file key (this protects against hitting back and resubmitting 
            same answer) */
        $fileKey = self::doHash(date("gsiiggsis"));
        $fK      = fopen("/usr/share/packages/DCIG_CAPTCHA/keys/" . $fileKey . 
                    ".key", "w");
        if(!$fK) {
            /* If we couldn't create the key, skip output */
            
        } else {
            $html   .= "<input type=\"hidden\" name=\"cFile\" value=\"$fileKey\" />";
        }
        fclose($fK);
        
        $html .= "<table class = \"formTable\" id = \"captcha\">";
        $html .= "<tr><th colspan = \"3\">Verification</th></tr>";
        $html .= "<tr><td>Some online forms occasionally come under spam attack. 
                    We ask that you help us reduce these occurrences by selecting the 
                    name of the color in the box to the right from the drop down 
                    selector. By doing this, the form will validate that you are not 
                    sending spam.</td>";
        $html .= "<td><div style = \"width:30px; height:30px; background-color: 
                    {$colors[$solutionIndex]["hex"]};\"></div></td>";
        $html .= "<td>";
        $html .= "<select name=\"cSolution\">";
        $html .= "<option value=\"\">Choose a color...</option>";
        foreach($colors as $colorOpt) {
            $html .= "<option value=\"" . $colorOpt["seed"] . "\">
                {$colorOpt["name"]}</option>";
        }
        $html .= "</select>";
        $html .= "</td>";
        $html .= "</tr></table>";
        
        /* Return puzzle */
        return $html;
        
    }
    
    ######################################################################################
    # Method: verifyColorCaptcha(...)
    # Arguments: <none>
    # Purpose: Verifies the answer to a "sufficiently" difficult Captcha.
    ######################################################################################
    public static function verifyColorCaptcha() {
        return self::verifyCaptcha();    
    }
    
    ######################################################################################
    # Method: generateAsciiCaptcha(...)
    # Arguments: <none>
    # Purpose: 
    ######################################################################################
    public static function generateAsciiCaptcha($length=5) {
        
        /* Configure */
        $candidates      = "12345789ABCDEFHIJKLNPQRTUXYZ";
        $possibilities   = strlen($candidates);
        $challengeLength = $length;
        
        /* Generate solution */
        $solution = "";
        for($i=0;$i<$challengeLength;$i++) {
            $solution .= substr($candidates,rand(0,$possibilities-1),1);
        }

        $im = imagecreatetruecolor(26, 8);
        $bg = imagecolorallocate($im, 255, 255, 255);
        $textcolor = imagecolorallocate($im, 0, 0, 0);
        imagefill($im,0,0,$bg);
        imagestring($im,0,1,0,$solution,$textcolor);
    
        $lastcolor=0;
        $puzzle = "<span style=\"font-size: 0.5em;line-height: 3px;letter-spacing: 1px;\">";
        for($y=0;$y<imagesy($im);$y++) {
              for($x=0;$x<imagesx($im);$x++) {
                $pixel = ImageColorAt($im, $x, $y);
                if($lastcolor != $pixel) {
                      $puzzle .= sprintf('</font><font color="#%06x">', $pixel);
                      $lastcolor = $pixel;
                }
                $puzzle .= "*";
              }
              $puzzle .= "<br />";
        }
        $puzzle .= "</span>";
        imagedestroy($im);
            
        /* Generate solution HASH (sufficiently complicated) */
        $solutionHash  = self::doHash($solution);
        
        /* Create HTML for puzzle with decoy hashes */
        $html = "";
        
        for($i=0;$i<50;$i++) {
            if((self::solutionIndex()) == $i) {
                $html .= "<input type=\"hidden\" name=\"cHash[]\" 
                    value=\"$solutionHash\" />";
            } else {
                $decoyHash = self::doHash($solutionHash . rand(2,100));
                $html .= "<input type=\"hidden\" name=\"cHash[]\" 
                    value=\"$decoyHash\" />";
            }
        }
        
        /* Create file key (this protects against hitting back and resubmitting 
            same answer) */
        $fileKey = self::doHash(date("gsiiggsis"));
        $fK      = fopen("/usr/share/packages/DCIG_CAPTCHA/keys/" . $fileKey . 
                    ".key", "w");
        if(!$fK) {
            /* If we couldn't create the key, skip output */
        } else {
            $html   .= "<input type=\"hidden\" name=\"cFile\" value=\"$fileKey\" />";
        }
        fclose($fK);
        
        $html .= "<table class = \"formTable\" id = \"captcha\">";
        $html .= "<tr><th colspan = \"2\">Verification</th></tr>";
        $html .= "<tr><td colspan = \"2\">Some online forms occasionally come under spam attack. 
                    We ask that you help us reduce these occurrences by typing the 
                    characters shown below. By doing this, the form will validate that  
                    you are not sending spam.</td></tr>";
        $html .= "<tr><td>$puzzle</td>";
        $html .= "<td>";
        $html .= "<input type=\"text\" name=\"cSolution\" value=\"\" />";
        $html .= "</td>";
        $html .= "</tr></table>";
        
        /* Return puzzle */
        return $html;
        
    }

    ######################################################################################
    # Method: verifyAsciiCAPTCHA(...)
    # Arguments: <none>
    # Purpose: 
    ######################################################################################
    public static function verifyAsciiCAPTCHA() { 
        return self::verifyCaptcha();
    }
    
    ######################################################################################
    # Method: verifyCaptcha(...)
    # Arguments: <none>
    # Purpose: Verifies the answer to a "sufficiently" difficult Captcha using the 
    #    generic method.
    ######################################################################################
    public static function verifyCaptcha() {
        
        /* Translator for $_GET */
        $_POST['cHash'] = ( isset($_GET['cHash']) ? $_GET['cHash'] : $_POST['cHash'] );
        $_POST['cFile'] = ( isset($_GET['cFile']) ? $_GET['cFile'] : $_POST['cFile'] );
        $_POST['cSolution'] = ( isset($_GET['cSolution']) ? $_GET['cSolution'] : $_POST['cSolution'] );
        
        /* Clean up old files (removing anything created last hour) */
        @$dir = opendir("/usr/share/packages/DCIG_CAPTCHA/keys/");
        if($dir) {
            while($file = readdir($dir)) {
                if($file!="." && $file!="..") {
                    $fileTime = filemtime("/usr/share/packages/DCIG_CAPTCHA/keys/" . 
                        $file);
                    if(abs(intval(date("h")) - intval(date("h",$fileTime))) > 0) {
                        unlink("/usr/share/packages/DCIG_CAPTCHA/keys/" . $file);
                    }
                }
            }
        }
        
        /* If we never set the $_POST CAPTCHA variables, return true */
        if((!isset($_POST["cHash"]) && !isset($_POST["cSolution"]) && !isset($_POST["cFile"]))) {
            return false;
        } elseif($_POST["cSolution"] == "") {
            return false;
        }
        
        $fileKey = "/usr/share/packages/DCIG_CAPTCHA/keys/" . $_POST["cFile"];
        if(!file_exists($fileKey . ".key")) {
            return false;
        } else {
            unlink($fileKey . ".key");
        }    
        
        /* Do verification */        
        if( $_POST["cHash"][self::solutionIndex()] == self::doHash($_POST["cSolution"])) {
            return true;
        } else {
            return false;
        }
        
        
    }    
    
    ######################################################################################
    # Method: doHash(...)
    # Arguments:
    #    - $seed (string) :: MD5 seed
    # Purpose: Creates solution hash.  Flaw, this hash changes with the day, so a 
    #    person loading the page at 11:59pm and submitting it at 12:01am would fail to
    #    correctly choose a color. 
    ######################################################################################    
    public static function doHash($seed) {
        return MD5($seed . date("jz"));
    }
    
    ######################################################################################
    # Method: solutionIndex(...)
    # Arguments: <none>
    # Purpose: Returns todays solution index
    ######################################################################################    
    public static function solutionIndex() {
        return ((date("zj") . (date("jz"))) % 50);
    }
    
}

?>
