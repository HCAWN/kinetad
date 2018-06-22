<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
sec_session_start();
if (login_check($mysqli) == true) :
?>
<!DOCTYPE html>
<html>
	<head>
		<!--[if IE]>
			<script>alert("You are using an outdated browser that does not support the formatting used on this website. Please consider upgrading your browser to improve your web experience.");</script>
		<![endif]-->
		<meta content="minimal-ui, width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no" name="viewport" />
		<meta content="yes" name="apple-mobile-web-app-capable" />
		<title>Kinetad <?php echo ucfirst($_SESSION['username']); ?>'s password</title>

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
		<link rel="stylesheet" href="styles/base.css" />
		<script type="text/JavaScript" src="js/sha512.js"></script> 
		<script type="text/JavaScript" src="js/forms.js"></script> 
		<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
	</head>
	<body>
		<img id="pixabay" src="graphics/background.jpg">
		<form action="" method="post" name="login_form">
			<h1>Change <?php echo $_SESSION['username'];?>'s Password</h1>
			<input type="text" placeholder="Old Password" name="oldpassword" class="textinp"/>
			<input type="password" placeholder="New Password" name="newpassword"  class="textinp"/>
			<input type="password" placeholder="Confirm New Password" name="newconfirmpwd" class="textinp"/>
			<input type="button" value="Change Password" id="submt" class="buttonclk" onclick="changeformhash(this.form, this.form.oldpassword, this.form.newpassword, this.form.newconfirmpwd);" /> 
		</form>
	</body>
</html>

<?php
	if (isset($_POST['pold'], $_POST['pnew'])) {
		$username = $_SESSION['username'];
		$oldpassword = $_POST['pold']; // The hashed password.
		$password = $_POST['pnew'];
		if (login($username, $oldpassword, $mysqli) == true) {
			//////////////////CHANGE PASSWORD//////////////////
			// Create a random salt
			$random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
			// Create salted password 
			$password = hash('sha512', $password . $random_salt);
			//Insert the new user password info into the database 
			if ($update_stmt = $mysqli->prepare("UPDATE members SET password=?, salt=? WHERE username=?")) {
				$update_stmt->bind_param('sss', $password, $random_salt, $username);
				// Execute the prepared query.
				if (! $update_stmt->execute()) {
					header('Location: ../error.php?err=Registration failure: UPDATE');
					exit();
				}
			};
			//////////////////CHANGE PASSWORD//////////////////
			//////////////////RE-ENCRYPT ENTRIES//////////////////
			// obtain entries
			$sql = "SELECT * FROM ".$_SESSION['username']." ORDER BY number ASC" ; 
			$query = mysqli_query($mysqli, $sql);
			if (!$query) {
				die ('SQL Error: ' . mysqli_error($mysqli));
			};
			$insertsql = "TRUNCATE ".$_SESSION['username'].";";
			$insertsql .= "INSERT INTO ".$_SESSION['username']." (datee,entry,IV,tag) VALUES";
			// loop over entries, decrypt then recrypt
			while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)):
				$rawentry = openssl_decrypt($row['entry'], "aes-256-gcm", $oldpassword, $options=0, hex2bin($row['IV']), hex2bin($row['tag']));
				//////////////////AES-256-GCM encryption//////////////////
				$plaintext = $rawentry;
				$cipher = "aes-256-gcm";
				$key = $_POST['pnew'];
				$tag = '10001';
				$ivlen = openssl_cipher_iv_length($cipher);
				$iv = openssl_random_pseudo_bytes($ivlen);
				$entry = openssl_encrypt($plaintext, $cipher, $key, $options=0, $iv, $tag);
				//////////////////AES-256-GCM encryption//////////////////
				$insertsql .= " ('".$row['datee']."','".$entry."','".bin2hex($iv)."','".bin2hex($tag)."')," ;
			endwhile;
			$insertsql = substr($insertsql, 0, -1).";";		
			if (!mysqli_multi_query($mysqli, $insertsql)) {
				echo mysqli_error($mysqli);
				die;				
			};
			while(mysqli_more_results($mysqli)) {
				mysqli_next_result($mysqli);
			};
			header('Location: https://'.$_SERVER['SERVER_NAME']);
			//////////////////RE-ENCRYPT ENTRIES//////////////////
		}
		else {
			header('Location: ../error.php?err=Incorrect current password');
			exit();
		};
	};
else :
	header('Location: https://'.$_SERVER['SERVER_NAME']);
endif;
?>