<?php

include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<!--[if IE]>
			<script>alert("You are using an outdated browser that does not support the formatting used on this website. Please consider upgrading your browser to improve your web experience.");</script>
		<![endif]-->
		<meta content="minimal-ui, width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no" name="viewport" />
		<meta content="yes" name="apple-mobile-web-app-capable" />

		<title>Diary</title>

		<link rel="apple-touch-icon" sizes="180x180" href="http://neilsonengineering.com/favicons/apple-touch-icon.png?v=BGBR3AKGdq">
		<link rel="icon" type="image/png" href="http://neilsonengineering.com/favicons/favicon-32x32.png?v=BGBR3AKGdq" sizes="32x32">
		<link rel="icon" type="image/png" href="http://neilsonengineering.com/favicons/favicon-16x16.png?v=BGBR3AKGdq" sizes="16x16">
		<link rel="manifest" href="http://neilsonengineering.com/favicons/manifest.json?v=BGBR3AKGdq">
		<link rel="mask-icon" href="http://neilsonengineering.com/favicons/safari-pinned-tab.svg?v=BGBR3AKGdq" color="#000aff">
		<link rel="shortcut icon" href="http://neilsonengineering.com/favicons/favicon.ico?v=BGBR3AKGdq">
		<meta name="apple-mobile-web-app-title" content="Neilson Engineering">
		<meta name="application-name" content="Neilson Engineering">
		<meta name="msapplication-config" content="http://neilsonengineering.com/favicons/browserconfig.xml?v=BGBR3AKGdq">
		<meta name="theme-color" content="#ffffff">

		<link href="styles/main.css" rel="stylesheet" type="text/css" />
		<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
		<link href="https://fonts.googleapis.com/css?family=Cormorant+SC" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Lora" rel="stylesheet">
        <meta charset="UTF-8">
    </head>
    <body>
    	<?php if (login_check($mysqli) == true) : ?>
    	<div id="background"></div>
    	<div id="header">
        	<h1><?php echo ucfirst(htmlentities($_SESSION['username'])); ?>'s Diary!</h1>
			<div class="previousd topbutton">Previous Day</div>
			<a class="logout topbutton" href="includes/logout">log out</a>
			<div class="nextd topbutton">Next Day</div>
		</div>
		<!--          Retrieving entries          -->
		<?php
		    $sql = "SELECT * FROM Entry ORDER BY number DESC" ; 
			$query = mysqli_query($mysqli, $sql);
			if (!$query) {
				die ('SQL Error: ' . mysqli_error($mysqli));
			}
		?>
		<!--          Retrieving entries          -->
		<!--          EACH ENTRY          -->
		<div id=main>
			<a name="#a"></a>
			<form class="form" action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data" autocomplete="off">
				<div class="alert alert-error"></div>
				<input type="text" placeholder="Thought" name="entry" class="entrybox" autofocus="autofocus" required />
				<input type="submit" value="Add" name="Done" class="submitbox" />
			</form>
			<h2>Today</h2>

			<?php
			$current_date = strtotime('today midnight');
			$FourAM = $current_date + 14400;
			$word_count = 0;
				while($row = mysqli_fetch_array($query)) : ?>
					<div id="entry">
						<?php
							$word_count = $word_count + str_word_count($row['entry']);

							$entry_date = strtotime($row['datee']);

							$P1_day = $FourAM - 86400;
							$P2_day = $P1_day - 86400;
							$P3_day = $P2_day - 86400;
							$P4_day = $P3_day - 86400;
							$P5_day = $P4_day - 86400;
							$P6_day = $P5_day - 86400;
							$P7_day = $P6_day - 86400;
							$P8_day = $P7_day - 86400;
							$P9_day = $P8_day - 86400;
							$P10_day = $P9_day - 86400;
							$P11_day = $P10_day - 86400;
							$P12_day = $P11_day - 86400;
							$P13_day = $P12_day - 86400;
							$P14_day = $P13_day - 86400;
							$P15_day = $P14_day - 86400;
							$P16_day = $P15_day - 86400;
							$P17_day = $P16_day - 86400;
							$P18_day = $P17_day - 86400;
							$P19_day = $P18_day - 86400;
							$P20_day = $P19_day - 86400;
							$P21_day = $P20_day - 86400;
							$P22_day = $P21_day - 86400;
							$P23_day = $P22_day - 86400;
							$P24_day = $P23_day - 86400;
							$P25_day = $P24_day - 86400;
							$P26_day = $P25_day - 86400;
							$P27_day = $P26_day - 86400;
							$P28_day = $P27_day - 86400;
							$P29_day = $P28_day - 86400;
							$P30_day = $P29_day - 86400;
							$P31_day = $P30_day - 86400;

							if ($entry_date < $FourAM) {
								$FourAM = $P1_day;
								echo "<h2><a name=\"#a\">".date('Y-m-d', $FourAM)."</a></h2>";
							};
							if ($entry_date < $P1_day) {
								$FourAM = $P1_day - 86400;
								echo "<h2><a name=\"#a\">".date('Y-m-d', $FourAM)."</a></h2>";
							};	
							if ($entry_date < $P2_day) {
								$FourAM = $P2_day - 86400;
								echo "<h2><a name=\"#a\">".date('Y-m-d', $FourAM)."</a></h2>";
							};	
							if ($entry_date < $P3_day) {
								$FourAM = $P3_day - 86400;
								echo "<h2><a name=\"#a\">".date('Y-m-d', $FourAM)."</a></h2>";
							};	
							if ($entry_date < $P4_day) {
								$FourAM = $P4_day - 86400;
								echo "<h2><a name=\"#a\">".date('Y-m-d', $FourAM)."</a></h2>";
							};	
							if ($entry_date < $P5_day) {
								$FourAM = $P5_day - 86400;
								echo "<h2><a name=\"#a\">".date('Y-m-d', $FourAM)."</a></h2>";
							};	
							if ($entry_date < $P6_day) {
								$FourAM = $P6_day - 86400;
								echo "<h2><a name=\"#a\">".date('Y-m-d', $FourAM)."</a></h2>";
							};	
							if ($entry_date < $P7_day) {
								$FourAM = $P7_day - 86400;
								echo "<h2><a name=\"#a\">".date('Y-m-d', $FourAM)."</a></h2>";
							};	
							if ($entry_date < $P8_day) {
								$FourAM = $P8_day - 86400;
								echo "<h2><a name=\"#a\">".date('Y-m-d', $FourAM)."</a></h2>";
							};	
							if ($entry_date < $P9_day) {
								$FourAM = $P9_day - 86400;
								echo "<h2><a name=\"#a\">".date('Y-m-d', $FourAM)."</a></h2>";
							};	
							if ($entry_date < $P10_day) {
								$FourAM = $P10_day - 86400;
								echo "<h2><a name=\"#a\">".date('Y-m-d', $FourAM)."</a></h2>";
							};	
							if ($entry_date < $P11_day) {
								$FourAM = $P11_day - 86400;
								echo "<h2><a name=\"#a\">".date('Y-m-d', $FourAM)."</a></h2>";
							};	
							if ($entry_date < $P12_day) {
								$FourAM = $P12_day - 86400;
								echo "<h2><a name=\"#a\">".date('Y-m-d', $FourAM)."</a></h2>";
							};	
							if ($entry_date < $P13_day) {
								$FourAM = $P13_day - 86400;
								echo "<h2><a name=\"#a\">".date('Y-m-d', $FourAM)."</a></h2>";
							};	
							if ($entry_date < $P14_day) {
								$FourAM = $P14_day - 86400;
								echo "<h2><a name=\"#a\">".date('Y-m-d', $FourAM)."</a></h2>";
							};					
							if ($entry_date < $P15_day) {
								$FourAM = $P15_day - 86400;
								echo "<h2><a name=\"#a\">".date('Y-m-d', $FourAM)."</a></h2>";
							};
							if ($entry_date < $P16_day) {
								$FourAM = $P16_day - 86400;
								echo "<h2><a name=\"#a\">".date('Y-m-d', $FourAM)."</a></h2>";
							};	
							if ($entry_date < $P17_day) {
								$FourAM = $P17_day - 86400;
								echo "<h2><a name=\"#a\">".date('Y-m-d', $FourAM)."</a></h2>";
							};	
							if ($entry_date < $P18_day) {
								$FourAM = $P18_day - 86400;
								echo "<h2><a name=\"#a\">".date('Y-m-d', $FourAM)."</a></h2>";
							};	
							if ($entry_date < $P19_day) {
								$FourAM = $P19_day - 86400;
								echo "<h2><a name=\"#a\">".date('Y-m-d', $FourAM)."</a></h2>";
							};	
							if ($entry_date < $P20_day) {
								$FourAM = $P20_day - 86400;
								echo "<h2><a name=\"#a\">".date('Y-m-d', $FourAM)."</a></h2>";
							};					
							if ($entry_date < $P21_day) {
								$FourAM = $P21_day - 86400;
								echo "<h2><a name=\"#a\">".date('Y-m-d', $FourAM)."</a></h2>";
							};
							if ($entry_date < $P22_day) {
								$FourAM = $P22_day - 86400;
								echo "<h2><a name=\"#a\">".date('Y-m-d', $FourAM)."</a></h2>";
							};	
							if ($entry_date < $P23_day) {
								$FourAM = $P23_day - 86400;
								echo "<h2><a name=\"#a\">".date('Y-m-d', $FourAM)."</a></h2>";
							};	
							if ($entry_date < $P24_day) {
								$FourAM = $P24_day - 86400;
								echo "<h2><a name=\"#a\">".date('Y-m-d', $FourAM)."</a></h2>";
							};					
							if ($entry_date < $P25_day) {
								$FourAM = $P25_day - 86400;
								echo "<h2><a name=\"#a\">".date('Y-m-d', $FourAM)."</a></h2>";
							};
							if ($entry_date < $P26_day) {
								$FourAM = $P26_day - 86400;
								echo "<h2><a name=\"#a\">".date('Y-m-d', $FourAM)."</a></h2>";
							};	
							if ($entry_date < $P27_day) {
								$FourAM = $P27_day - 86400;
								echo "<h2><a name=\"#a\">".date('Y-m-d', $FourAM)."</a></h2>";
							};	
							if ($entry_date < $P28_day) {
								$FourAM = $P28_day - 86400;
								echo "<h2><a name=\"#a\">".date('Y-m-d', $FourAM)."</a></h2>";
							};	
							if ($entry_date < $P29_day) {
								$FourAM = $P29_day - 86400;
								echo "<h2><a name=\"#a\">".date('Y-m-d', $FourAM)."</a></h2>";
							};	
							if ($entry_date < $P30_day) {
								$FourAM = $P30_day - 86400;
								echo "<h2><a name=\"#a\">".date('Y-m-d', $FourAM)."</a></h2>";
							};					
							if ($entry_date < $P31_day) {
								$FourAM = $P31_day - 86400;
								echo "<h2><a name=\"#a\">".date('Y-m-d', $FourAM)."</a></h2>";
							};

						?>
					<h3 id="time"><?php echo date('H:i:s', $entry_date);?></h3>
					<h3 id="number"><?php echo $row['number']; ?></h3>
					<p><?php echo $row['entry']; ?></p>
				</div>
			<?php   endwhile ?>
			<h2 id="wordcount">Total words: <?php echo number_format($word_count); ?></h2>
		</div>
		<!--          EACH ENTRY          -->
		<!--          Creating new Entry          -->
	    <?php
		    if (isset($_POST['entry'])) {
				$link = mysql_connect("localhost:3306","DiaryEnter","password");
				if (!$link) {
					die('Could not connect: ' . mysql_error());
				}
				$db_selected = mysql_select_db("KinetadDiary", $link);
				if (!$db_selected) {
					die('Can\'t use ' . DB_NAME . ': ' . mysql_error());
				}
			    $datee = date('Y-m-d H:i:s');
			    $entry = addslashes($_POST['entry']);
			    
				$sql = "INSERT INTO Entry (datee,entry) VALUES ('$datee','$entry')" ;
				unset($_POST['entry']);
				unset($entry);
				echo "<script language='javascript'>window.location = 'diary2';</script>"; 

				if (!mysql_query($sql)) {
					die('Error: ' . mysql_error());
					echo "something bad";
				}
			}
	    ?>
	    <!--          Creating new Entry          -->
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

			$('h1').click( function() {
				$("p").toggleClass("stealth");
			});
		</script>
        <?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please <a href="index">login</a>.
            </p>
        <?php endif; ?>
    </body>
</html>
