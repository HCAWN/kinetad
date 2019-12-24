<?php
include_once 'db_connect.php';
include_once 'functions.php';
sec_session_start();

if (isset($_POST['username'], $_POST['p'])) {
    $username = strtolower(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
    $password = $_POST['p']; // The hashed password.
    
    if (login($username, $password, $mysqli) == true) {
        // Login success 
        $_SESSION['encryptstring'] = $_POST['p'];
        //exec("git pull");
        header('Location: https://'.$_SERVER['SERVER_NAME']);
        exit();
    } else {
        // Login failed 
        header('Location: ../index.php?error=1');
        exit();
    }
} else {
    // The correct POST variables were not sent to this page. 
    header('Location: ../error.php?err=Could not process login');
    exit();
}