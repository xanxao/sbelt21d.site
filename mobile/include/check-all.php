<?php
function parseHeredoc($sHeredoc) {
	preg_match_all('/\{\{ *(A1?[0-9]V[1-9]) *\}\}/mi', $sHeredoc, $matches); // find all variables
	if ($matches[1] != NULL) {
		// var_dump($matches[1]);
		for ($o = 0; $o < count($matches[1]); $o++) {
			$matches[1][$o] = strtolower($matches[1][$o]);
		}
		// var_dump($matches[1]);
		$aVariables = $matches[1];
		$sCleanedHeredoc = preg_replace_callback('/\{\{ *(A1?[0-9]V[1-9]) *\}\}/mi',
                                         function ($matches) { return "<span class='".strtolower($matches[1])."'></span>"; },
										 $sHeredoc); // replace variables with proper HTML
	} else {
		$aVariables = array();
		$sCleanedHeredoc = $sHeredoc;
	}
	$sCleanedHeredoc = preg_replace('/\r+/','', trim($sCleanedHeredoc)); // trim front and back, and get rid of CRs
	$sCleanedHeredoc = preg_replace('/\n{2}/','</p><p>', $sCleanedHeredoc, -1, $iParagraphCount); // split paragraphs
	$sCleanedHeredoc = preg_replace('/\n+/',' ', $sCleanedHeredoc); // get rid of LFs
	$sCleanedHeredoc = preg_replace('/\s+/',' ', $sCleanedHeredoc); // squeeze long whitespace to a single space
	if ($iParagraphCount) {
		$sCleanedHeredoc = "<p>" . $sCleanedHeredoc . "</p>\n"; // wrap it up!
		$sCleanedHeredoc = preg_replace('/<\/p><p>/',"</p>\n<p>", $sCleanedHeredoc); // put back LFs between paragraphs
	}
	return array($sCleanedHeredoc, strlen($sCleanedHeredoc), $iParagraphCount, $aVariables);
}
function parseAnswersHeredoc($sHeredoc) {
	$sCleanedHeredoc = preg_replace('/\r+/','', trim($sHeredoc)); // trim front and back, and get rid of CRs
	$sCleanedHeredoc = preg_replace('/\n/','<linemarker>', $sCleanedHeredoc, -1); // place marker between lines
	$sCleanedHeredoc = preg_replace('/(<linemarker>)+/','<linemarker>', $sCleanedHeredoc); // get rid of extra line markers
	$sCleanedHeredoc = preg_replace('/\s+/',' ', $sCleanedHeredoc); // squeeze long whitespace to a single space
	$aAnswersAll = preg_split("/<linemarker>/", $sCleanedHeredoc);
	// var_dump($aAnswersAll);
	$n = count($aAnswersAll);
	$aAnswerVariations = array();
	$aAnswers = array();
	for ($o = 0; $o<$n; $o++) {
		$aAnswerVariations[$o] = preg_split("/\|/", $aAnswersAll[$o]);
		$aAnswers[$o] = $aAnswerVariations[$o][0];
	}
	return array($aAnswers, $n, $aAnswerVariations);
}
$iError = 0; // Count errors
# Ad Image 
list($image, $iLength, $iParagraphs, $aVars) = parseHeredoc($image);
if (!$iLength || ($iParagraphs) || count($aVars)) {
	echo "Bad Ad Image specification: $image.<br /><br />";
	$iError++;
} else {
	// echo "Ad Image: $image<br /><br />";
}

# Headline
list($headline, $iLength, $iParagraphs, $aVars) = parseHeredoc($headline);
if (!$iLength || ($iParagraphs) || count($aVars)) {
	echo "Bad headline specification: $headline<br /><br />";
	$iError++;
} else {
	// echo "Headline: $headline<br /><br />";
}

# Ad Text
list($adText, $iLength, $iParagraphs, $aVars) = parseHeredoc($adText);
if ((!$iLength) || count($aVars)) {
	echo "No or bad Ad Text Specified.<br /><br />";
	$iError++;
} else {
	if (!$iParagraphs) {
		$adText = "<p>" . $adText . "</p>\n"; // just single paragraph...wrap it up
	}
	// echo "AdText:$adText";
}

$iQ = 0; // Count of questions supplied
$sQuestionHTML = "";
$sAnswerVariablesJavascript = "\n<script>\nvar answers = [];\n";
$iCount = count($question);
for ($i=1; $i<=$iCount; $i++) {
	list($question[$i], $iLength, $iParagraphs, $aVariables) = parseHeredoc($question[$i]);
	// if ($iParagraphs) {
		// echo "Bad Question $i specification: $question[$i]<br /><br />";
		// $iError++;
	// } else {
		if ($iLength) {
			if (($answer_type[$i] != "Buttons") && ($answer_type[$i] != "Checkboxes")) {
				echo "Bad Answer Type Specified for Question $i; $answer_type[$i]<br /><br />";
				$iError++;
				continue;
			}
			list($aAnswers, $iAnswerCount, $aAnswerVariations) = parseAnswersHeredoc($answers[$i]);
			if ($iAnswerCount < 2) {
				echo "Only $iAnswerCount answers specified for question $i, 2 is the minimum.<br /><br />";
				$iError++;
				continue;
			}
			// if (($iAnswerCount > 2) && ($answer_type[$i] == "Buttons")) {
				// echo "$iAnswerCount answers specified for Buttons question $i, only 2 are allowed.<br /><br />";
				// $iError++;
				// continue;
			// }
			$iQ++; // We have a valid new question!
			// Store away answer data for variable substitution purposes, and compute longest on-screen answer length
			$iVarError = 0;
			$iVarCount = count($aAnswerVariations[0]); // get the count of answer variations for the 1st answer
			$sAnswerVariablesJavascript .= "answers[".$iQ."] = [];\n"; // create question's answers array
			$iLongestAnswer = 0;
			for ($j=1; $j<=$iAnswerCount; $j++) {
				$sAnswerVariablesJavascript .= "answers[".$iQ."][".$j."] = [];\n"; // create each answer's version array
				if (strlen($aAnswers[$j]) > $iLongestAnswer) {
					$iLongestAnswer = strlen($aAnswers[$j]);
				}
			}
			for ($j=0; $j<$iAnswerCount; $j++) {
				$iA = $j+1;
				$iVCount = count($aAnswerVariations[$j]);
				if ($iVCount != $iVarCount) { // does the answer variation count match all other answers?
					$iVarError++; // no, we have a problem!
				}
				for ($k=0; $k<$iVCount; $k++) {
					$iV = $k+1;
					$ans = str_replace("'", "\'", $aAnswerVariations[$j][$k]);
					$sAnswerVariablesJavascript .= "answers[".$iQ."][".$iA."][".$iV."] = '".$ans."';\n";
				}
			}
			if ($iVarError) {
				echo "Inconsistent answer variations specified for question $i; all answers MUST have the same number of variations.<br /><br />";
				$iError++;
				continue;
			}
			if ($answer_type[$i] == "Buttons") {
				if ($iLongestAnswer > 20) {
					$sWordyButton = "wordyButton ";
				} else {
					$sWordyButton = "";
				}
				$sQuestionHTML .= "\t\t\t".'<article id="step'.$iQ.'" class="questionStep clearfix" data-step="'.$iQ.'">'."\n";
				$sQuestionHTML .= "\t\t\t\t<header>QUESTION ".$iQ." of ". $total_num_questions .":</header>\n";
				$sQuestionHTML .= "\t\t\t\t<h6>".$question[$iQ]."</h6>\n";
				for ($j=0; $j<$iAnswerCount; $j++) {
					$iA = $j+1;
					if (!(j%2)) {
						$sQuestionHTML .= "\t\t\t\t".'<button class="stepButton '.$sWordyButton.'yesBtn s'.$iQ.'" data-step="'.$iQ.'" id="q'.$iQ.'a'.$iA.'">'.$aAnswers[$j].'</button>'."\n";
					} else {
						$sQuestionHTML .= "\t\t\t\t".'<button class="stepButton '.$sWordyButton.'noBtn s'.$iQ.'" data-step="'.$iQ.'" id="q'.$iQ.'a'.$iA.'" href="">'.$aAnswers[$j].'</button>'."\n";
					}
				}
				$sQuestionHTML .= "\t\t\t</article>\n";
			} else {
				$sQuestionHTML .= "\t\t\t".'<article id="step'.$iQ.'" class="questionStep clearfix" data-step="'.$iQ.'">'."\n";
				$sQuestionHTML .= "\t\t\t\t<header>QUESTION ".$iQ.":</header>\n";
				$sQuestionHTML .= "\t\t\t\t<h6>".$question[$iQ]."</h6>\n";
				$sQuestionHTML .= "\t\t\t\t<div class=".'"radio-group"'.">\n";
				for ($j=0; $j<$iAnswerCount; $j++) {
					$iA = $j+1;
					$sQuestionHTML .= "\t\t\t\t\t<div class=".'"radio-button"'.">\n";
					$sQuestionHTML .= "\t\t\t\t\t\t<input class=".'"stepButton choiceBtn s'.$iQ.'" data-step="'.$iQ.'" id="q'.$iQ.'a'.$iA.'" name="q'.$iQ.'" type="radio"><label for="q'.$iQ.'a'.$iA.'"><span></span>'.$aAnswers[$j].'</label>'."\n";
					$sQuestionHTML .= "\t\t\t\t\t</div>\n";
				}
				$sQuestionHTML .= "\t\t\t\t</div>\n";
				$sQuestionHTML .= "\t\t\t</article>\n";
			}
		}
	// }
}
$sAnswerVariablesJavascript .= "</script>\n";
if (!$iQ) {
	echo "No quiz questions specified, please add questions to your quiz lander!\n<br /><br />";
	$iError++;
}

$iSMS = 0; // Count of Smart Match System Evaluation Titles
$sSMSTitleHTML = "";
$sSMSTextHTML = "";
$iCount = count($smartMatchTitle);
for ($i=0; $i<$iCount; $i++) {
	list($smartMatchTitle[$i], $iLength, $iParagraphs, $aVars) = parseHeredoc($smartMatchTitle[$i]);
	list($smartMatchText[$i], $iLength2, $iParagraphs2, $aVars2) = parseHeredoc($smartMatchText[$i]);
	if ($iParagraphs || $iParagraphs2) {
		if ($iParagraphs) {
			echo "Bad Smart Match System Title $i specification: $smartMatchTitle[$i]<br /><br />";
			$iError++;
		}
		if ($iParagraphs2) {
			echo "Bad Smart Match System Text $i specification: $smartMatchText[$i]<br /><br />";
			$iError++;
		}
	} else {
		if ($iLength || $iLength2) {
			$iSMS++;
			$sSMSTitleHTML .= '<h3 id="pss'.$iSMS.'-title" class="evalTitle" data-step="'.$iSMS.'">'.$smartMatchTitle[$i]."</h3>\n";
			$sSMSTextHTML .= '<aside id="pss'.$iSMS.'-text" class="evalText" data-step="'.$iSMS.'">'.$smartMatchText[$i]."</aside>\n";
		}
	}
}
if (!$iSMS) {
	echo "No Smart Match System titles/text specified, please add them to your quiz lander!\n<br /><br />";
	$iError++;
}

# Congratulations Statement 
list($congratulationsStatement, $iLength, $iParagraphs, $aVars) = parseHeredoc($congratulationsStatement);
if (!$iLength || ($iParagraphs)) {
	echo "Bad Congratulations Statement specification: $congratulationsStatement<br /><br />";
	$iError++;
} else {
	// echo "Congratulations Statement: $congratulationsStatement<br /><br />";
}

# Rule 1
list($rule[1], $iLength, $iParagraphs, $aVars) = parseHeredoc($rule[1]);
if (!$iLength || ($iParagraphs)) {
	echo "Bad Rule 1 specification: $rule[1]<br /><br />";
	$iError++;
} else {
	// echo "Rule 1: $rule[1]<br /><br />";
}

# 
# Rule 2
list($rule[2], $iLength, $iParagraphs, $aVars) = parseHeredoc($rule[2]);
if (!$iLength || ($iParagraphs)) {
	echo "Bad Rule 2 specification: $rule[2]<br /><br />";
	$iError++;
} else {
	// echo "Rule 2: $rule[2]<br /><br />";
}

# Rule 3
list($rule[3], $iLength, $iParagraphs, $aVars) = parseHeredoc($rule[3]);
if (!$iLength || ($iParagraphs)) {
	echo "Bad Rule 3 specification: $rule[3]<br /><br />";
	$iError++;
} else {
	// echo "Rule 3: $rule[3]<br /><br />";
}

# Disclaimer
list($disclaimer, $iLength, $iParagraphs, $aVars) = parseHeredoc($disclaimer);
if ($iLength) {
	if (!$iParagraphs) {
		$disclaimer = "<p>" . $disclaimer . "</p>\n"; // just single paragraph...wrap it up
		$disclaimer = preg_replace("/<p>/", '<p style="text-align: center;"><span style="font-size: small;">', $disclaimer);
		$disclaimer = preg_replace("/<\/p>/", '</span></p>', $disclaimer);
	}
	// echo "Disclaimer: $disclaimer<br /><br />";
}

# Universal Tracking
$affiliateNetworkDefault = "";
$iAffiliateLinksSpecified = 0;
$affiliateInfo = array();
foreach ($affInfo as $network => $parameter) {
	list($parameter["link"], $iLength, $iParagraphs, $aVars) = parseHeredoc($parameter["link"]);
	list($parameter["trackingTag"], $iLength2, $iParagraphs2, $aVars) = parseHeredoc($parameter["trackingTag"]);
	if ($iLength && !$iParagraphs && $iLength2 && !$iParagraphs2) {
		if (!$iAffiliateLinksSpecified) {
			$affiliateNetworkDefault = $network; // the first non-blank URL found is the default link/network
		}
		$iAffiliateLinksSpecified++; // we have at least 1
		$affiliateInfo[$network]["link"] = $parameter["link"]; 
		$affiliateInfo[$network]["trackingTag"] = $parameter["trackingTag"];
		// echo "Affiliate Network '$network' specified with tracking tag '".$parameter["trackingTag"]."' and link:<br />";
		// echo $parameter["link"]."<br /><br />";
	}
}
if (!$iAffiliateLinksSpecified) {
	echo "No Affiliate Link Specified!<br /><br />";
	$iError++;
}

# Facebook Pixel Code
$iPixelTracking = 0; // Assume no FB Pixel Tracking
list($fbPixelCheck, $iLength, $iParagraphs, $aVars) = parseHeredoc($facebookPixelCode);	
$pos = strpos($fbPixelCheck, "fbq");
if ($iLength) {
	if ($pos === false) {
		echo "Bad Facebook Pixel Code Specified<br /><br />";
		$iError++;
	} else {
		$pos2 = strpos($fbPixelCheck, "[FB_PIXEL_ID]");
		if ($pos2 === false) {
			// echo "Facebook Pixel Code Specified - Pixel Tracking Activated.<br /><br />";
			$iPixelTracking = 1;
		} else {
			echo "Bad Facebook Pixel Code Specified - You must replace '[FB_PIXEL_ID]' in the template provided with your Facebook Pixel ID,<br />replace the Facebook Pixel Template provided with your Facebook Pixel code, or simply remove the Facebook Pixel Template provided.<br /><br />";
			$iError++;
		}
	}
} else {
	// echo "No Facebook Pixel Code Specified - Pixel Tracking NOT Activated.<br /><br />";
}

list($facebookLeadEventName, $iLength, $iParagraphs, $aVars) = parseHeredoc($facebookLeadEventName);
if (!$iLength) {
	$facebookLeadEventName = "Lead"; // use default FB Lead event if none specified
}
if ($iParagraphs) {
	echo "Improper Facebook Lead Event Name specified: $facebookLeadEventName.<br /><br />";
	$iError++;
} else {
	// echo "Facebook Lead Event Name: $facebookLeadEventName.<br /><br />";
}

list($imageClickTrackingSuffix, $iLength, $iParagraphs, $aVars) = parseHeredoc($imageClickTrackingSuffix);
if ($iParagraphs) {
	echo "Bad Image Click Tracking Suffix specification: $imageClickTrackingSuffix<br /><br />";
	$iError++;
} else {
	// echo "Image Click Tracking Suffix: $imageClickTrackingSuffix<br /><br />";
}

list($headlineClickTrackingSuffix, $iLength, $iParagraphs, $aVars) = parseHeredoc($headlineClickTrackingSuffix);
if ($iParagraphs) {
	echo "Bad Headline Click Tracking Suffix specification: $headlineClickTrackingSuffix<br /><br />";
	$iError++;
} else {
	// echo "Headline Click Tracking Suffix: $headlineClickTrackingSuffix<br /><br />";
}

list($buttonClickTrackingSuffix, $iLength, $iParagraphs, $aVars) = parseHeredoc($buttonClickTrackingSuffix);
if ($iParagraphs) {
	echo "Bad Button Click Tracking Suffix specification: $buttonClickTrackingSuffix<br /><br />";
	$iError++;
} else {
	// echo "Button Click Tracking Suffix: $buttonClickTrackingSuffix<br /><br />";
}

# URL Passphrase 
list($urlPassphrase, $iLength, $iParagraphs, $aVars) = parseHeredoc($urlPassphrase);
if (!$iLength || ($iParagraphs)) {
	$urlPassphrase = "A man, a plan, a canal, Panama"; // use the default
} else {
	// echo "URL Passphrase: $urlPassphrase<br /><br />";
}

if ($iPixelTracking) {
	$onclick = "fbq('track', '" . $facebookLeadEventName . "'); iAgree(this);";
} else {
	$onclick = "iAgree(this);";
}
if ($iError) {
	die("$iError errors encountered, make corrections and try again.<br />");
}
?>
