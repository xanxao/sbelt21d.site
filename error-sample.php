<?php
# Ad Image 
$image = <<< IMAGE_END
IMAGE_END;

# Headline
$headline = <<< HEADLINE_END
HEADLINE_END;

# Ad Text - leave a blank line between paragraphs
$adText = <<< ADTEXT_END
ADTEXT_END;

# Question 1 
$question[1] = <<< QUESTION1_END
QUESTION1_END;

# Question 2 
$question[2] = <<< QUESTION2_END
QUESTION2_END;

# Question 3 
$question[3] = <<< QUESTION3_END
QUESTION3_END;

# Question 4 
$question[4] = <<< QUESTION4_END
QUESTION4_END;

# Congratulations Statement 
$congratulationsStatement = <<< CONGRATS_END
CONGRATS_END;

# Rule 1
$rule[1] = <<< RULE1_END
RULE1_END;

# Rule 2
$rule[2] = <<< RULE2_END
RULE2_END;

# Rule 3
$rule[3] = <<< RULE3_END
RULE3_END;

// Affiliate Links - Non-empty "link" settings will be activated. If multiple links are
//                   activated (non-empty) below, then the 1st one encountered will be
//                   considered the default link, meaning it will be used if no "n=xx"
//                   tracking parameter is set in your Facebook Ad "URL Parameters".

# Clickbank Affiliate Link
$affInfo["cb"]["link"] = <<< AFFILIATELINK_END
AFFILIATELINK_END;

# Clickbank Tracking Tag
$affInfo["cb"]["trackingTag"] = <<< TRACKINGTAG_END
tid
TRACKINGTAG_END;

# Software Projects Affiliate Link
$affInfo["sp"]["link"] = <<< AFFILIATELINK_END

AFFILIATELINK_END;

# Software Projects Tracking Tag
$affInfo["sp"]["trackingTag"] = <<< TRACKINGTAG_END
subid
TRACKINGTAG_END;

// SOME FUTURE AFFILIATE NETWORK (With Tracking Tag "trk" and URL Parameter: n=fan for example)
# Future Affiliate Network Affiliate Link
# $affInfo["fan"]["link"] = <<< AFFILIATELINK_END
# http://futureaffnetwork.com/?aid=xxx
# AFFILIATELINK_END;
#
# Future Affiliate Network Tracking Tag
# $affInfo["fan"]["trackingTag"] = <<< TRACKINGTAG_END
# trk
# TRACKINGTAG_END;

// Facebook ad "URL Parameters" examples:
//   To have this page use your Clickbank affiliate link.......: n=cb&t=my_tracking_info
//   To have this page use your Software Projects affilate link: n=sp&t=my_tracking_info
//   To use your default affiliate link........................: t=my_tracking_info 

// Facebook Pixel Code - Below, you must either:
//      1. Remove the lines from "<!-- Facebook Pixel Code -->" thru 
//         "<!-- End Facebook Pixel Code -->" below if you aren't ready 
//         to use Facebook Pixel tracking yet, or
//      2. Paste over the lines from "<!-- Facebook Pixel Code -->" thru
//         "<!-- End Facebook Pixel Code -->" to replace it with your own
//         Facebook Pixel (including the PageView event), or
//      3. Replace [FB_PIXEL_ID] in 2 places below with your own Facebook Pixel ID

# Facebook Pixel Code
$facebookPixelCode = <<< FACEBOOKPIXELCODE_END
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '[FB_PIXEL_ID]'); // Insert your pixel ID here.
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=[FB_PIXEL_ID]&ev=PageView&noscript=1"
/></noscript>
<!-- DO NOT MODIFY -->
<!-- End Facebook Pixel Code -->
FACEBOOKPIXELCODE_END;

// You can change the following Facebook Lead Event Name if you'd like to use
// a custom lead event rather than the default "Lead" event.

# Facebook Lead Event Name
$facebookLeadEventName = <<< LEADEVENTNAME_END
LEADEVENTNAME_END;

// Click location tracking - values appended to tracking id
// to let you know where the click-thru to the VSL occured
// (Image, Headline, or Button).

# Image Click Tracking Suffix
$imageClickTrackingSuffix = <<< IMAGECLICK_END
IMAGECLICK_END;

# Headline Click Tracking Suffix
$headlineClickTrackingSuffix = <<< HEADLINECLICK_END
HEADLINECLICK_END;

# Button Click Tracking Suffix
$buttonClickTrackingSuffix = <<< BUTTONCLICK_END

BUTTONCLICK_END;

// URL Passphrase - any phrase will do, used to
// encode/decode your affiliate link URLs.

# URL Passphrase
$urlPassphrase = <<< PASSPHRASE_END
PASSPHRASE_END;

################################################
### DO NOT MAKE ANY CHANGES BELOW THIS LINE! ###
################################################
require_once("mobile/include/check-all.php");
require_once("mobile/include/universal-tracking.php");
require_once("mobile/include/aeslib.php");
$agreeLink = cryptoJsAesEncrypt($urlPassphrase, $agreeLink);
?>
<!DOCTYPE html>
<!--[if lt IE 7]>     <html class="no-js lt-ie9 lt-ie8 lt-ie7" xmlns:fb="http://ogp.me/ns/fb#"><![endif]-->
<!--[if IE 7]>        <html class="no-js lt-ie9 lt-ie8" xmlns:fb="http://ogp.me/ns/fb#"><![endif]-->
<!--[if IE 8]>        <html class="no-js lt-ie9" xmlns:fb="http://ogp.me/ns/fb#"><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js" xmlns:fb="http://ogp.me/ns/fb#"><!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php echo $headline; ?></title>
    <meta name="description" content="<?php echo $headline; ?>">
	<meta name="keywords" content="<?php echo $headline; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="mobile/css/normalize.min.css" type='text/css'>
	<link rel="stylesheet" href="mobile/css/presell.min.css" type='text/css'>
    <link rel="stylesheet" href="mobile/css/mobile.css" type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Shadows+Into+Light+Two' rel='stylesheet' type='text/css'>        

    <script src="mobile/js/modernizr-2.6.2-respond-1.1.0.min.js"></script>
	<?php echo $facebookPixelCode; ?>
	<script src="mobile/js/aes.js" type="text/javascript"></script>
	<script src="mobile/js/aeslib.js" type="text/javascript"></script>
	<?php
	require_once("mobile/include/iagree.php");
	?>
</head>
<body>
<!--[if lt IE 7]>
<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a>
or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
<![endif]-->
<div class="wrapper main clearfix">
	<div id="headerBox" class="clearfix">
		<aside id="imageBox">
			<a href="#" id="image" data-tracking-suffix="<?php echo $imageClickTrackingSuffix; ?>" onclick="<?php echo $onclick; ?>"><img style="border:0px solid #222;margin:16px 0px 17px;0px;" src="<?php echo $image; ?>" id="q1-image" class="headerImage" data-step="1"></a>
		</aside>
		<section id="headerContent">
			<header id="headerTitle">
				<h2><a href="#" id="headline" data-tracking-suffix="<?php echo $headlineClickTrackingSuffix; ?>" onclick="<?php echo $onclick; ?>"><?php echo $headline; ?></a></h2>
			</header>
			<section id="headerText">
				<?php echo $adText; ?>
			</section>
		</section>
	</div>
	<div id="surveyBox" class="clearfix">
		<div id="questionBox" class="clearfix">
			<article id="step1" class="questionStep clearfix" data-step="1">
				<header>QUESTÃO 1:</header>
				<h6><?php echo $question[1]; ?></h6>
				<button class="stepButton yesBtn s1" data-step="1">Yes</button>
				<button class="stepButton noBtn s1" data-step="1" href="">No</button>
			</article>
			<article id="step2" class="questionStep clearfix" data-step="2">
				<header>QUESTÃO 2:</header>
				<h6><?php echo $question[2]; ?></h6>
				<button class="stepButton yesBtn s2" data-step="2">Yes</button>
				<button class="stepButton noBtn s2" data-step="2" href="">No</button>
			</article>
			<article id="step3" class="questionStep clearfix" data-step="3">
				<header>QUESTÃO 3:</header>
				<h6><?php echo $question[3]; ?></h6>
				<button class="stepButton yesBtn s3" data-step="3">Yes</button>
				<button class="stepButton noBtn s3" data-step="3" href="">No</button>
			</article>
			<article id="step4" class="questionStep clearfix" data-step="4">
				<header>QUESTÃO 4:</header>
				<h6><?php echo $question[4]; ?></h6>
				<button class="stepButton yesBtn s4" data-step="4">Yes</button>
				<button class="stepButton noBtn s4" data-step="4" href="">No</button>
			</article>
		</div>
		<div id="surveyEval" class="clearfix">
			<h3 id="pss1-title" class="evalTitle" data-step="1">Checking against our proprietary Smart Match System™</h3>
			<h3 id="pss2-title" class="evalTitle" data-step="2">Questão 1: Valid</h3>
			<h3 id="pss3-title" class="evalTitle" data-step="3">Questão 2: Valid</h3>
			<h3 id="pss4-title" class="evalTitle" data-step="4">Questão 3: Valid</h3>
			<h3 id="pss5-title" class="evalTitle" data-step="5">Questão 4: Valid</h3>
			<img src="mobile/img/loading.gif" alt="loading" id="loadingImage" class="loading"><aside id="pss1-text" class="evalText" data-step="1">Thank you. We are evaluating your answers.</aside>
			<aside id="pss2-text" class="evalText" data-step="2">Checking our proprietary Smart Match System™</aside>
			<aside id="pss3-text" class="evalText" data-step="3">Checking...</aside>
			<aside id="pss4-text" class="evalText" data-step="4">You are a match! <span style="color:#021f38;">You are approved to view the presentation.</span></aside>
			<aside id="pss5-text" class="evalText" data-step="5">Please read our 3 rules now!</aside>
		</div>
		<div id="congratsBox" class="clearfix">
			<article id="surveyEnd">
				<header>
					<h4><?php echo $congratulationsStatement; ?></h4>
				</header>
				<section>
					<div style="color:#FFFFFF;font-size:16px;text-align:left;">
						<span><p><img src="1.png" style="vertical-align:middle;padding-left:5px;padding-right:5px;"><?php echo $rule[1]; ?></p></span>
						<span><p><img src="2.png" style="vertical-align:middle;padding-left:5px;padding-right:5px;"><?php echo $rule[2]; ?></p></span>
						<span><p><img src="3.png" style="vertical-align:middle;padding-left:5px;padding-right:5px;"><?php echo $rule[3]; ?></p></span>
						<p style="padding-left:60px;color:#000000;">If you agree to all the above, click the "I Agree" button below to proceed to the following private presentation.</p>
					</div>
				</section>
				<button id="surveyAgree" data-tracking-suffix="<?php echo $buttonClickTrackingSuffix; ?>" class="stepButton yesBtn" onclick="<?php echo $onclick; ?>">I AGREE!</button>
			</article>
			</div>
		</div>
	</div>
	<div id="footer">&copy; <script type="text/javascript">var cur = 2014; var year = new Date(); if(cur == year.getFullYear()) year = year.getFullYear(); else year = cur + ' - ' + year.getFullYear(); document.write(year);</script>
		<p style="text-align: center;"><span style="font-size: x-small;"><a href="privacy-policy.html">Privacy</a> | <a href="terms-of-service.html">Terms</a> | <a href="disclosure-agreement.html">Earnings</a> | <a href="contact.php">Contact</a></span></p>
	</div>
	<script>
		var noTimeLeft  = "a few seconds";
		var minutesTxt  = " minutes and ";
		var secondsTxt  = " seconds";
		var redirTime   = "28800";
		var trackEvents = "1";
	</script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
	<script src="mobile/js/presell.min.js"></script>
</body>
</html>
