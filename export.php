<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
sec_session_start();

// open raw memory as file so no temp files needed, might run out of memory though if really really large journal
$f = fopen('php://memory', 'w'); 

// obtain entries
$sql = "SELECT * FROM ".strtolower($_SESSION['username'])." ORDER BY number ASC" ; 
$query = mysqli_query($mysqli, $sql);
if (!$query) {
	die ('SQL Error: ' . mysqli_error($mysqli));
};
// loop over entries, decrypt and write
while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)):
	$line[0] = $row['number'];
	$line[1] = $row['datee'];
	$line[2] = openssl_decrypt($row['entry'], "aes-256-gcm", $_SESSION['encryptstring'], $options=0, hex2bin($row['IV']), hex2bin($row['tag']));
	fputcsv($f, $line, ","); 
endwhile;

// reset the file pointer to the start of the file
rewind($f);
// tell the browser it's going to be a csv file
header('Content-Type: application/csv');
// tell the browser we want to save it instead of displaying it
header('Content-Disposition: attachment; filename="'.$_SESSION['username'].'_Journal_Export.csv";');
// make php send the generated csv lines to the browser
fpassthru($f);
?>
<script language="JavaScript">
window.close();
</script>