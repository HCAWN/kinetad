<?php
include_once 'includes/register.inc.php';
include_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<script type="text/JavaScript" src="js/sha512.js"></script> 
		<script type="text/JavaScript" src="js/forms.js"></script>
	</head>
	<body>
		<!-- Registration form to be output if the POST variables are not
		set or if the registration script caused an error. -->
		<h1>Create your login information:</h1>
		<?php
		if (!empty($error_msg)) {
			echo $error_msg;
		}
		?>
		<form method="post" name="registration_form" action="<?php //echo esc_url($_SERVER['PHP_SELF']); ?>">
			Username: <input type='text' name='username' id='username' /><br>
			Password: <input type="password"
							 name="password" 
							 id="password"/><br>
			Confirm password: <input type="password" 
									 name="confirmpwd" 
									 id="confirmpwd" /><br>
			<input type="button" 
				   value="Register" 
				   onclick="return regformhash(this.form,
								   this.form.username,
								   this.form.password,
								   this.form.confirmpwd);" /> 
		</form>
	</body>
</html>