<?php
include_once 'includes/register.inc.php';
include_once 'includes/functions.php';
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
		<title>Kinetad - Register</title>
		<link rel="stylesheet" href="styles/base.css" />
		<script type="text/JavaScript" src="js/sha512.js"></script> 
		<script type="text/JavaScript" src="js/forms.js"></script> 
		<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
	</head>
	<body>
		<?php
		if (!empty($error_msg)) {
			echo $error_msg;
		}
		?>
		<img id="pixabay" src="graphics/background.jpg">
		<form method="post" name="registration_form" autocomplete="off" action="">
			<h2 style="text-align: center;" >Create an account</h2>
			<input type='text' placeholder='Username' name='username' id='username' class="textinp" />
			<input type="password" placeholder="password" name="password" id="password" class="textinp" />
			<input type="password" placeholder="Confirm password" name="confirmpwd" id="confirmpwd" class="textinp" />
			<input type="button" value="Register" class="buttonclk" onclick="return regformhash(this.form, this.form.username, this.form.password, this.form.confirmpwd);" /> 
		</form>
	</body>
</html>