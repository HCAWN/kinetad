<?php
$error = filter_input(INPUT_GET, 'err', $filter = FILTER_SANITIZE_STRING);
if (! $error) {
	$error = 'Oops! An unknown error happened.';
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta content="minimal-ui, width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no" name="viewport" />
		<meta content="yes" name="apple-mobile-web-app-capable" />

		<link rel="apple-touch-icon" sizes="180x180" href="https://neilsonengineering.com/favicons/apple-touch-icon.png?v=BGBR3AKGdq">
		<link rel="icon" type="image/png" href="https://neilsonengineering.com/favicons/favicon-32x32.png?v=BGBR3AKGdq" sizes="32x32">
		<link rel="icon" type="image/png" href="https://neilsonengineering.com/favicons/favicon-16x16.png?v=BGBR3AKGdq" sizes="16x16">
		<link rel="manifest" href="https://neilsonengineering.com/favicons/manifest.json?v=BGBR3AKGdq">
		<link rel="mask-icon" href="https://neilsonengineering.com/favicons/safari-pinned-tab.svg?v=BGBR3AKGdq" color="#000aff">
		<link rel="shortcut icon" href="https://neilsonengineering.com/favicons/favicon.ico?v=BGBR3AKGdq">
		<meta name="apple-mobile-web-app-title" content="Neilson Engineering">
		<meta name="application-name" content="Neilson Engineering">
		<meta name="msapplication-config" content="https://neilsonengineering.com/favicons/browserconfig.xml?v=BGBR3AKGdq">
		<meta name="theme-color" content="#ffffff">
		<title>Kinetad - Error</title>
		<link rel="stylesheet" href="styles/base.css" />
		<script type="text/JavaScript" src="js/sha512.js"></script> 
		<script type="text/JavaScript" src="js/forms.js"></script> 
		<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
	</head>
	<body>
		<h1>There was a problem:</h1>
		<p class="error"><?php echo $error; ?></p>  
	</body>
</html>
