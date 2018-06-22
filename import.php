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
		<img id="pixabay" src="graphics/background.jpg">
		<form enctype="multipart/form-data" action="" method="POST">
			<p>Upload your journal csv</p>
			<input type="file" name="csv"></input><br>
			<input type="submit" value="Upload"></input>
			<p>CSV files in the format of:</p>
			<p>1,"YYYY-MM-DD HH:MM:SS","content of entry #1"</p>
			<p>2,"YYYY-MM-DD HH:MM:SS","content of entry #2"</p>
			<p>...</p>
		</form>
	</body>
</html>

<?php
	include_once 'includes/db_connect.php';
	include_once 'includes/functions.php';
	sec_session_start();
	$mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
	$max_size = 5000000; //5mb ~ 5million characters
	function validateDate($date, $format = 'Y-m-d H:i:s') {
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) === $date;
	};
	if(!empty($_FILES['csv'])) {
		if($_FILES['csv']['type'] < $max_size) {
			if(in_array($_FILES['csv']['type'],$mimes)) {
				$csv = array_map('str_getcsv', file($_FILES['csv']['tmp_name']));
				if(count($csv[0] !== 3)) {
					$x=0;
					foreach($csv as $row) {
						$row[0] = $row[0] * 1;
						if(!is_int($row[0])) {
							die("Sorry, first column must contain integers (Row: ".$x." = ".$row[0].").");
						};
						$number[] = $row[0];
						if(!validateDate($row[1])) {
							die("Sorry, second column must valid date format YYYY-MM-DD HH:MM:SS (Row: ".$x." = ".$row[1].").");
						}
						$x++;
					};
					if(count($number) !== count(array_unique($number))) {
						die("Sorry, first column contains duplicate integers.");
					};
					//Looks good to process into thier new empty journal
					$sql = "INSERT INTO ".strtolower($_SESSION['username'])." (datee,entry,IV,tag) VALUES";
					foreach($csv as $row) {
						//////////////////AES-256-GCM encryption//////////////////
						$plaintext = $row[2];
						$cipher = "aes-256-gcm";
						$key = $_SESSION['encryptstring'];
						$tag = '10001';
						$ivlen = openssl_cipher_iv_length($cipher);
						$iv = openssl_random_pseudo_bytes($ivlen);
						$entry = openssl_encrypt($plaintext, $cipher, $key, $options=0, $iv, $tag);
						//////////////////AES-256-GCM encryption//////////////////
						$sql .= " ('".$row[1]."','".$entry."','".bin2hex($iv)."','".bin2hex($tag)."')," ;
					};
					$sql = substr($sql, 0, -1).";";		
					if ( ! $query = mysqli_query($mysqli, $sql) ) {
						echo mysqli_error($mysqli);
						die;
					};
					header('Location: https://'.$_SERVER['SERVER_NAME']);
				}
				else {
					die("Sorry, wrong number columns provided.");
				};
			}
			else {
				die("Sorry, mime type not allowed");
			};
		}
		else {
			die("Sorry, file too large (over 5mb)");
		};
	};
?>