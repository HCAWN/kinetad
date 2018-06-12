<?php

include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();

if (login_check($mysqli) == true) {
    $logged = 'in';
} else {
    $logged = 'out';
}
?>
<!DOCTYPE html>
<html>
    <head>
        <!--[if IE]>
            <script>alert("You are using an outdated browser that does not support the formatting used on this website. Please consider upgrading your browser to improve your web experience.");</script>
        <![endif]-->
        <meta content="minimal-ui, width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no" name="viewport" />
        <meta content="yes" name="apple-mobile-web-app-capable" />

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
        }
        ?> 
        <img id="pixabay" src="graphics/background.jpg">
        <form action="includes/process_login.php" method="post" name="login_form">          
            <input type="text" placeholder="Email" name="email" class="textinp"/>
            <input type="password" placeholder="Password" name="password" id="password" class="textinp"/>
            <input type="button" value="Login" id="submt" class="buttonclk" onclick="formhash(this.form, this.form.password);" /> 
            <iframe style="width:100%; height:200px;" src="enter.php" frameborder="0"></iframe>
        </form>
    </body>
</html>
