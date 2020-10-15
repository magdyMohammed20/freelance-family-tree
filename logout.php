<?php

    // Resume Session
    session_start();

    // Remove Cookie For Check For Login
    //unset($_COOKIE['Login']);
    setcookie(str_replace(' ','',$_SESSION['user']) , null , time()-3600 , '/');

    // Unset Session
    session_unset();

    // Destroy Session
    session_destroy();

    // Direct User To Login Page
    header("Location: login.php")
?>
