	<script type="text/javascript">
	function iAgree(field) {
		var url = JSON.parse(CryptoJS.AES.decrypt('<?php echo $agreeLink; ?>', '<?php echo $urlPassphrase; ?>', {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8));
		if ((field.hasAttribute("data-tracking-suffix")) && ($("#"+field.id).attr("data-tracking-suffix").length > 0)) {
			var trackingsuffix = "_"+$("#"+field.id).attr("data-tracking-suffix");
		} else {
			var trackingsuffix = "";
		}
		url = url.replace(/_TRKSFX/g, trackingsuffix);
		$("#"+field.id).attr("href",url);
	}
	</script>
