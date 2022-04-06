<?php
if(count($_GET)>0){
	if ((array_key_exists ("n", $_GET)) && (array_key_exists ($_GET["n"], $affiliateInfo))){
		$n = $_GET["n"]; // select the affiliate network specified by the "n=xx" parameter
	} else {
		$n = $affiliateNetworkDefault; // use the default affiliate network
	}
	$trackingTag = $affiliateInfo[$n]["trackingTag"]; // get proper tracking tag for the affiliate network
	$agreeLink   = $affiliateInfo[$n]["link"]; // ...and start off the Agree link with the proper affiliate network link
	$parts = preg_split("/\?/", $agreeLink);
	$agreeLink = $parts[0];
	if (count($parts)>1) {
		$urlString = "&".$parts[1];
	} else {
		$urlString = "";
	}
	foreach ($_GET as $key => $value) {
		if (($n == "cb") && (($key == "tid") || ($key == "t"))) {
			$value = preg_replace("/-/", "_", $value); // change "-" to "_" for Clickbank tid values
			$adTrackingText = $value; // Save for autoresponder ad tracking!
		}
		if (($key != "n") && ($key != "")) { // skip "n" and null keys
			if ($key != "t") {
				$urlString.="&".$key."=".$value;
				if ($key == $trackingTag) {
					$urlString.="_TRKSFX";
					$adTrackingText = $value; // Save for autoresponder ad tracking!
				}
			} else {
				$urlString.="&".$trackingTag."=".$value."_TRKSFX"; // substitute proper tracking tag for "t" shorthand tag
				$adTrackingText = $value; // Save for autoresponder ad tracking!
			}
		}
	}
	if (strlen($urlString)>0) {
		$urlString="?".substr($urlString,1);
		$agreeLink.=$urlString;
	}
} else {
	$agreeLink = $affiliateInfo[$affiliateNetworkDefault]["link"]; // if no params, use default link with no params
	$adTrackingText = ""; // Set for autoresponder ad tracking!
}
?>
