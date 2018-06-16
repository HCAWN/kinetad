<?php
include_once 'db_connect.php';
include_once 'psl-config.php';
$error_msg = "";
print_r($_POST);
if (isset($_POST['username'], $_POST['p'])) {
	// Sanitize and validate the data passed in
	$username = strtolower(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
	if(preg_match("/^[a-zA-Z0-9_]+$/", $username) == 0) {
		$error_msg .= '<p class="error">Username contains invalid characters.</p>';
	};
	$password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
	if (strlen($password) != 128) {
		$error_msg .= '<p class="error">Invalid password configuration.</p>';
	};
	$prep_stmt = "SELECT id FROM members WHERE username = ? LIMIT 1";
	$stmt = $mysqli->prepare($prep_stmt);
	if ($stmt) {
		$stmt->bind_param('s', $username);
		$stmt->execute();
		$stmt->store_result();
		
		if ($stmt->num_rows == 1) {
			// A user with this username address already exists
			$error_msg .= '<p class="error">This username has been already taken.</p>';
		}
	} else {
		$error_msg .= '<p class="error">Database error</p>';
	}
	
	if (empty($error_msg)) {
		// Create a random salt
		$random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
		// Create salted password 
		$password = hash('sha512', $password . $random_salt);
		// Insert the new user into the database 
		if ($insert_stmt = $mysqli->prepare("INSERT INTO members (username, password, salt) VALUES (?, ?, ?)")) {
			$insert_stmt->bind_param('sss', $username, $password, $random_salt);
			// Execute the prepared query.
			if (! $insert_stmt->execute()) {
				header('Location: ../error.php?err=Registration failure: INSERT');
				exit();
			}
		};
		$insertsql = "
		CREATE TABLE IF NOT EXISTS `".$username."` (
		  `number` int(16) NOT NULL AUTO_INCREMENT,
		  `datee` datetime NOT NULL,
		  `entry` text NOT NULL,
		  `IV` varchar(255) NOT NULL,
		  `tag` varchar(255) NOT NULL,
		  PRIMARY KEY (`number`)
		)
		";
		if (!mysqli_multi_query($mysqli,$insertsql)) {
			die('Error: ' . mysqli_error($mysqli));
			echo "something bad";
		};
		while(mysqli_more_results($mysqli)) {
			mysqli_next_result($mysqli);
		};
		//header('Location: ./register_success.php');
		exit();
	}
	else {
		echo $error_msg;
	}
}