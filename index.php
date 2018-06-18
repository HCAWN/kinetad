<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
sec_session_start();
?>
<!DOCTYPE html>
<html>
	<?php if (login_check($mysqli) == true) : ?>
	<head>
		<!--[if IE]>
			<script>alert("You are using an outdated browser that does not support the formatting used on this website. Please consider upgrading your browser to improve your web experience.");</script>
		<![endif]-->
		<meta content="minimal-ui, width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no" name="viewport" />
		<meta content="yes" name="apple-mobile-web-app-capable" />
		<title><?php echo ucfirst($_SESSION['username']); ?>'s Diary</title>

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

		<link href="styles/main.css" rel="stylesheet" type="text/css" />
		<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
		<link href="https://fonts.googleapis.com/css?family=Cormorant+SC" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Lora" rel="stylesheet">
		<meta charset="UTF-8">
	</head>
	<body>
		<?php
		//Set locked status
		if (!isset($_SESSION['lockstatus'])) {
			$_SESSION['lockstatus'] = ucfirst($_SESSION['username']).'\'s Diary';
			header('Location: https://'.$_SERVER['SERVER_NAME']);
			exit;
		};
		if (isset($_POST['lockstatus'])) {
			$_SESSION['lockstatus'] = $_POST['lockstatus'];
			header('Location: https://'.$_SERVER['SERVER_NAME']);
			exit;
		};
		//Import diary entries
		$sql = "SELECT * FROM ".strtolower($_SESSION['username'])." ORDER BY number DESC" ; 
		$query = mysqli_query($mysqli, $sql);
		if (!$query) {
			die ('SQL Error: ' . mysqli_error($mysqli));
		};
		?>
		<div id="background"></div>
		<div id="header">
			<h1>
				<form action="" name="lockstatus" method="post">
					<input style="text-decoration: none;" type="submit" class="button" id="title" name="lockstatus" value="<?php echo ucfirst($_SESSION['username']); ?>'s Diary<?php if ($_SESSION['lockstatus'] == ucfirst($_SESSION['username']).'\'s Diary') { echo ' (Locked)';}; ?>" />
				</form>
			</h1>
			<div class="previousd topbutton">Previous Day</div>
			<a class="logout topbutton" href="includes/logout">log out</a>
			<div class="nextd topbutton">Next Day</div>
		</div>
		<div id=main>
			<a name="#a"></a>
			<form class="form" action="" method="post" enctype="multipart/form-data" autocomplete="off">
				<input type="text" placeholder="Thought" name="entry" class="entrybox" autofocus="autofocus" required />
				<input type="submit" value="Add" name="Done" class="submitbox" />
			</form>
			<h2>Today</h2>
			<?php
			$current_date = strtotime('today midnight');
			$FourAM = $current_date + 14400;
			$word_count = 0;
			while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) : ?>
				<div id="entry">
					<?php
					$entry_date = strtotime($row['datee']);
					$emptydate = true;
					while ($emptydate == true) {
						if ($entry_date < $FourAM) {
							$FourAM -= 86400;
							echo "<h2><a name=\"#a\">".date('Y-m-d', $FourAM)."</a></h2>";
						}
						else {
							$emptydate = false;
						};
					};
					?>
					<h3 id="time"><?php echo date('H:i:s', $entry_date);?></h3>
					<h3 id="number"><?php echo $row['number']; ?></h3>
					<p>
						<?php
						if ($_SESSION['lockstatus'] == ucfirst($_SESSION['username']).'\'s Diary (Locked)') {
							$toecho = openssl_decrypt($row['entry'], "aes-256-gcm", $_SESSION['encryptstring'], $options=0, hex2bin($row['IV']), hex2bin($row['tag']));
						}
						else {
							$toecho = $row['entry'];
						};
						echo $toecho;
						$word_count += str_word_count($toecho);
						?>
					</p>
				</div>
			<?php
			endwhile
			?>
			<h2 id="wordcount">Total words: <?php echo number_format($word_count); ?></h2>
		</div>
		<?php
		if (isset($_POST['entry'])) {
			$datee = date('Y-m-d H:i:s');
			//////////////////AES-256-GCM encryption//////////////////
			$plaintext = $_POST['entry'];
			$cipher = "aes-256-gcm";
			$key = $_SESSION['encryptstring'];
			$tag = '10001';
			$ivlen = openssl_cipher_iv_length($cipher);
			$iv = openssl_random_pseudo_bytes($ivlen);
			$entry = openssl_encrypt($plaintext, $cipher, $key, $options=0, $iv, $tag);
			//////////////////AES-256-GCM encryption//////////////////
			$sql = "INSERT INTO ".strtolower($_SESSION['username'])." (datee,entry,IV,tag) VALUES ('".$datee."','".$entry."','".bin2hex($iv)."','".bin2hex($tag)."');" ;
			if ( ! $query = mysqli_query($mysqli, $sql) ) {
				echo mysqli_error($mysqli);
				die;
			};
			header('Location: https://'.$_SERVER['SERVER_NAME']);
			exit;
		};
		?>
		<script type="text/javascript">
			var index = -1;
			$('.previousd').click(function() {
			   index++;
			   $(window).scrollTop($('a').eq(index).position().top);

			});
			$('.nextd').click(function() {
			   index--;
			   if(index < 0) { index = 0;}

			   $(window).scrollTop($('a').eq(index).position().top);
			});
		</script>
		<?php
		if ($_SESSION['lockstatus'] == ucfirst($_SESSION['username']).'\'s Diary (Locked)') {
		?>
		<script type="text/javascript">
			var timeout = null;
			$(document).on('mousemove', function() {
				clearTimeout(timeout);
				timeout = setTimeout(function() {
					console.log('30 sec idle, locking');
					$('#title').trigger("click")
				}, 30000);
			});
		</script>
		<a style="position: fixed; bottom: 0; left: 0; cursor: pointer; color: inherit; text-decoration: none;" href="/import">Import</a>
		<a style="position: fixed; bottom: 0; right: 0; cursor: pointer; color: inherit; text-decoration: none;" href="/export">Export</a>
		<?php
		};
		else :
			// display login portal
		?>
			<head>
				<!--[if IE]>
					<script>alert("You are using an outdated browser that does not support the formatting used on this website. Please consider upgrading your browser to improve your web experience.");</script>
				<![endif]-->
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
				<title>Log In</title>
				<link rel="stylesheet" href="styles/base.css" />
				<script type="text/JavaScript" src="js/sha512.js"></script> 
				<script type="text/JavaScript" src="js/forms.js"></script> 
				<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
			</head>
			<body>
		<?php
			if (isset($_GET['error'])) {
				echo '<p class="error">Error Logging In!</p>';
			};
			?> 
			<img id="pixabay" src="graphics/background.jpg">
			<form action="includes/process_login.php" method="post" name="login_form">          
				<input type="text" placeholder="Username" name="username" class="textinp"/>
				<input type="password" placeholder="Password" name="password" id="password" class="textinp"/>
				<input type="button" value="Login" id="submt" class="buttonclk" onclick="formhash(this.form, this.form.password);" /> 
			</form>
			<?php
		endif;
		?>
	</body>
</html>