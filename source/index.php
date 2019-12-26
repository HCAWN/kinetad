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
		<title><?php echo ucfirst($_SESSION['username']); ?>'s Journal</title>

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
		<script type="text/JavaScript" src="js/clientEncrypt.js"></script> 
		<link href="https://fonts.googleapis.com/css?family=Cormorant+SC" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Lora" rel="stylesheet">
		<meta charset="UTF-8">
	</head>
	<body>
		<?php
		//Set locked status
		if (!isset($_SESSION['lockstatus'])) {
			$_SESSION['lockstatus'] = ucfirst($_SESSION['username']).'\'s Journal';
			header('Location: '.$_SERVER['REQUEST_URI']);
			exit;
		};
		if (isset($_POST['lockstatus'])) {
			$_SESSION['lockstatus'] = $_POST['lockstatus'];
			header('Location: '.$_SERVER['REQUEST_URI']);
			exit;
		};
		//Pagination
		$defaultPag = 5;
		$defaultPagMax = 2000;
		if (isset($_GET['limit'])) {
			$limit = min(filter_input(INPUT_GET, 'limit', FILTER_SANITIZE_NUMBER_FLOAT),$defaultPagMax);
			if (!$limit) {
				$limit = $defaultPag;
			};
		} else {
			$limit = $defaultPag;
		};
		//Import Journal entries
		$sql = "SELECT * FROM ".strtolower($_SESSION['username'])." ORDER BY number DESC LIMIT ".$limit ; 
		$query = mysqli_query($mysqli, $sql);
		if (!$query) {
			die ('SQL Error: ' . mysqli_error($mysqli));
		};
		?>
		<div id="background"></div>
		<div id="header">
			<h1>
				<form action="" name="lockstatus" method="post">
					<input style="text-decoration: none;" type="submit" class="button <?php if ($_SESSION['lockstatus'] == ucfirst($_SESSION['username']).'\'s Journal') { echo ' headerLocked';}; ?>" id="title" name="lockstatus" value="<?php echo ucfirst($_SESSION['username']); ?>'s Journal<?php if ($_SESSION['lockstatus'] == ucfirst($_SESSION['username']).'\'s Journal') { echo ' (Locked)';}; ?>" />
				</form>
			</h1>
			<div class="previousd topbutton">Previous Day</div>
			<a class="logout topbutton" href="includes/logout">Log out</a>
			<div class="nextd topbutton">Next Day</div>
		</div>
		<div id=main>
			<a name="#a"></a>
			<form class="form" action="" method="post" enctype="multipart/form-data" autocomplete="off">
				<input id="entrycipher" type="text" placeholder="Cipher" name="entrycipher" class="entrybox enterable" required />
				<textarea id="entryraw" type="text" style="overflow:hidden; height: 20px;" placeholder="Thought" name="entry" class="entrybox enterable" autofocus="autofocus" onkeyup="textAreaAdjust(this)" required /></textarea>
				<input id="addbutton" type="button" value="Add" name="Done" class="submitbox" onclick="textEntry(this.form, this.form.entry, this.form.entrycipher);" />
					<input id="hiddenAddFile" type="file" accept="image/*" style="display: none;" >
				<input id="imageUploadButton" type="button" class="submitbox" value="Preview image" onclick="document.getElementById('hiddenAddFile').click()" >
				<input id="addimagebutton" style="display: none; left: 80%;" type="button" value="Add image" name="Done" class="submitbox" onclick="imageEntry(this.form,document.getElementById('imgToUpload').src,this.form.entrycipher)" />
				<input id="discardimage" style="display: none; left: 20%;" type="button" value="Discard image" name="Done" class="submitbox" onclick="abort()" />
			</form>		
			
			<h2 class="todaytitle" style="position: relative;">Today</h2>
			<img id="imgToUpload">
			<form action="" method="post" >
				<div class="entries download" style="position: relative;">
					<?php
					$current_date = strtotime('today midnight');
					$FourAM = $current_date + 14400;
					$word_count = 0;
					while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) : ?>
						<div class="entry">
							<?php
							$entry_date = strtotime($row['datee']);
							$emptydate = true;
							while ($emptydate == true) {
								if ($entry_date < $FourAM) {
									$FourAM -= 86400;
									if ($entry_date < $FourAM) {
										echo "<h2><a name=\"#a\">".date('Y-m-d', $FourAM)."</a></h2>";
									}
									else {
										echo "<h2><a class=\"col datehead\" name=\"#a\">".date('Y-m-d', $FourAM)."</a></h2>";
									};
									
								}
								else {
									$emptydate = false;
								};
							};
							?>
							<h3 class="col time" ><?php echo date('H:i:s', $entry_date);?></h3>
							<h3 class="col number" ><?php echo $row['number']; ?></h3>
							<label class="submitbox remove" >
								<input style="display: none;" type="submit" value="<?php echo $row['number']; ?>" name="delete"/>
								<span>X</span>
							</label>
							<?php
							if ($row['entry'] != '') {
								if ($_SESSION['lockstatus'] == ucfirst($_SESSION['username']).'\'s Journal (Locked)') {
									$toecho = openssl_decrypt($row['entry'], "aes-256-gcm", $_SESSION['encryptstring'], $options=0, hex2bin($row['IV']), hex2bin($row['tag']));
								}
								else {
									$toecho = $row['entry'];
								};
								echo '<p class="encrypted" style="display: none;">'.$toecho.'</p>';
								$word_count += str_word_count($toecho);
								echo '<p class="col decrypted sentence" >'.$toecho.'</p>';
							}
							else {
								if ($_SESSION['lockstatus'] == ucfirst($_SESSION['username']).'\'s Journal (Locked)') {
									$toecho = openssl_decrypt($row['imgBlob'], "aes-256-gcm", $_SESSION['encryptstring'], $options=0, hex2bin($row['IV']), hex2bin($row['tag']));
								}
								else {
									$toecho = $row['imgBlob'];
								};	
								echo '<img class="encrypted" style="display: none;" src="'.$toecho.'" >';
								echo '<img class="col decrypted image" src="graphics/default.jpg" >';							
							}
							?>
						</div>
					<?php
					endwhile
					?>
				</div>
			</form>
			<h2 class="toptitle" id="wordcount">Total words: <?php echo number_format($word_count); ?></h2>
		</div>
		<?php
		///legacy upload///
		if (isset($_POST['e'])) {
			$datee = date('Y-m-d H:i:s');
			//////////////////AES-256-GCM encryption//////////////////
			$plaintext = $_POST['e'];
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
		/// delet entry ///
		if (isset($_POST['delete'])) {
			$number = $_POST['delete'];
			$sql = "DELETE FROM ".strtolower($_SESSION['username'])." WHERE number=".$number." ;";
			if ( ! $query = mysqli_query($mysqli, $sql) ) {
				echo mysqli_error($mysqli);
				die;
			};
			header('Location: https://'.$_SERVER['SERVER_NAME']);
			exit;
		};
		/// new text upload ///
		if (isset($_POST['textdata'])) {
			$datee = date('Y-m-d H:i:s');
			//////////////////AES-256-GCM encryption//////////////////
			$plaintext = $_POST['textdata'];
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
		/// new image upload ///
		if (isset($_POST['imagedata'])) {
			$datee = date('Y-m-d H:i:s');
			//////////////////AES-256-GCM encryption//////////////////
			$plaintext = $_POST['imagedata'];
			$cipher = "aes-256-gcm";
			$key = $_SESSION['encryptstring'];
			$tag = '10001';
			$ivlen = openssl_cipher_iv_length($cipher);
			$iv = openssl_random_pseudo_bytes($ivlen);
			$imgBlob = openssl_encrypt($plaintext, $cipher, $key, $options=0, $iv, $tag);
			//////////////////AES-256-GCM encryption//////////////////
			$sql = "INSERT INTO ".strtolower($_SESSION['username'])." (datee,imgBlob,IV,tag) VALUES ('".$datee."','".$imgBlob."','".bin2hex($iv)."','".bin2hex($tag)."');" ;
			if ( ! $query = mysqli_query($mysqli, $sql) ) {
				echo mysqli_error($mysqli);
				die;
			};
			header('Location: https://'.$_SERVER['SERVER_NAME']);
			exit;
		};
		?>
		<a style="position: fixed; bottom: 0; left: 0; cursor: pointer; color: inherit; text-decoration: none;" href="/changepassword">Change Password</a>
		<a style="position: fixed; bottom: 0; right: 0; cursor: pointer; color: inherit; text-decoration: none;" href="" download="Kinetad_Export.csv">Export</a>
		<script type="text/javascript">
			//BOTTOM OF PAGE PAGINATION
			$(window).on('load', function() {
				var url = new URL(window.location.href);
				var height = url.searchParams.get("height");
				$(window).scrollTop(height);
			});
			$(window).scroll(function() {
				if($(window).scrollTop() + $(window).height() == $(document).height()) {
					console.log('hit bottom');
					window.location = "<?php echo parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH)."?limit=".($limit+$defaultPag)."&height=" ?>"+($(window).scrollTop()-1);
				};
			});
			//PREVIOUS AND NEXT DAYS//
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
			//As submit button is no longer a "real" button://
			$(".enterable").keyup(function enterkeyup(event){
				if (event.keyCode === 13) {
					$("#addbutton").click();
				}
			});
			//ON THE FLY DECRYPTION//
			$("#entrycipher").keyup(function decryptkeyup(){
				$(".decrypted").each(function(){
					if ($(this).hasClass("sentence")) {
						var savethis = $(this);
						decrypt($(this).prev().text(),$("#entrycipher").val()).then(function(result) {
							$(savethis).text(result);
						});
					}
					if ($(this).hasClass("image")) {
						var savethis = $(this);
						decrypt($(this).prev().attr('src'),$("#entrycipher").val()).then(function(result) {
							$(savethis).attr('src',result) ;
						});
					}

				});
			});
			//Submit download//
			$("a[download]").click(function(){
			    $("div.download").toCSV(this);
			});
			//Compress and preview image//
			document.getElementById("hiddenAddFile").addEventListener("change", function (event) {
				compress(event);
			});
			function compress(e){
			    const width = 350;
			    const fileName = e.target.files[0].name;
			    const reader = new FileReader();
			    reader.readAsDataURL(e.target.files[0]);
			    reader.onload = event => {
			        const img = new Image();
			        img.src = event.target.result;
			        img.onload = () => {
			                const elem = document.createElement('canvas');
			                const height = img.height * width / img.width 
			                elem.width = width;
			                elem.height = height;
			                const ctx = elem.getContext('2d');
			                ctx.drawImage(img, 0, 0, width, height);
			                const data = ctx.canvas.toDataURL('image/jpeg', 0.8);
			                console.log(data)
			                document.getElementById("imgToUpload").src = data;
			                $('#addbutton').hide()
			                $('#addimagebutton').show()
			                $('#discardimage').show()              
			            },
			            reader.onerror = error => console.log(error);
			    };
			}
			//Abort image upload//
			function abort(){
				document.getElementById("imgToUpload").src = "";
				$('#addbutton').show()
				$('#addimagebutton').hide()
				$('#discardimage').hide()  				
			}
			//Change text area size//
			function textAreaAdjust(o) {
				o.style.height = "1px";
				o.style.height = (0+o.scrollHeight)+"px";
				$('.todaytitle').css('top', (o.scrollHeight-20)+"px");
				$('#wordcount').css('top', (40+o.scrollHeight)+"px");
				$('.entries').css('top', (o.scrollHeight-20)+"px");
			}
			//encode webpage using js
			jQuery.fn.toCSV = function(link) {
			  var $link = $(link);
			  var data = $(this).first(); //Only one table
			  var csvData = [];
			  var tmpArr = [];
			  var tmpStr = '';
			  var currentdate = "Today";
			  data.find(".entry").each(function() {
					tmpArr = [];
					var dateNotUsed = true;
					$(this).find(".col").each(function() {
						// if entry has new date
						if($(this).hasClass("datehead")) {
							currentdate = $(this).text();
						}
						// if col is date
						if(dateNotUsed) {
							tmpStr = currentdate.replace(/"/g, '""');
							tmpArr.push('"' + tmpStr + '"');
							dateNotUsed = false;
						}
						// if col is number
						if($(this).text().match(/^-{0,1}\d*\.{0,1}\d+$/)) {
							tmpArr.push(parseFloat($(this).text()));
						}
						// if col is sentence
						if($(this).hasClass("sentence")) {
							tmpStr = $(this).text().replace(/"/g, '""');
							console.log(tmpStr);
							tmpArr.push('"' + tmpStr + '"');
						}
						// if col is image
						if($(this).hasClass("image")) {
							tmpArr.push('"Image file not exported"');
						}
						// if col is time
						if($(this).hasClass("time")) {
							tmpStr = $(this).text().replace(/"/g, '""');
							tmpArr.push('"' + tmpStr + '"');
						}
					});
					csvData.push(tmpArr.join(','));
			  });
			  var output = csvData.join('\n');
			  var uri = 'data:application/csv;charset=UTF-8,' + encodeURIComponent(output);
			  $link.attr("href", uri);
			}
			if (readCookie('cipher')) {
				$("#entrycipher").val(readCookie('cipher'));
				$('#entrycipher').keyup();
			};
		</script>
		<?php
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
				<title>Kinetad - Login</title>
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
			<div id="pixabay"></div>
			<form action="includes/process_login.php" method="post" name="login_form">
				<h2 style="text-align: center;" >Login</h2>        
				<input type="text" placeholder="Username" name="username" class="textinp"/>
				<input type="password" placeholder="Password" name="password" id="password" class="textinp"/>
				<input type="button" value="Login" id="submt" class="buttonclk" onclick="formhash(this.form, this.form.password);" /> 
			</form>
			<a style="position: fixed; bottom: 0; left: 0; cursor: pointer; color: inherit; text-decoration: none;" href="/register">Register</a>
			<script type="text/javascript">
				//As submit button is no longer a "real" button://
				$(document).keyup(function enterkeyup(event){
					console.log(event.keyCode);
					if (event.keyCode === 13) {
						$("#submt").click();
					}
				});
			</script>
			<?php
		endif;
		?>
	</body>
</html>