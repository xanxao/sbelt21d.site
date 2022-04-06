<?php 
$contactEmail=""; //type default email
if(empty($contactEmail)){ $contactEmail="contact@".$_SERVER['HTTP_HOST'];}

?>

<html><head><meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"></head><body>Contact: <a href="mailto:<?php echo $contactEmail;?>"><?php echo $contactEmail;?></a></body></html>