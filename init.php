<?php

    global $isLogin;

    // Include Database Connect Code
    include 'admin/DBconnect.php';

    // For Check Session In All User Pages
    $sessionUser = '';
    if(isset($_SESSION['user'])){
        $sessionUser = $_SESSION['user'];
    }

    $tmp = 'includes/templates/';
    $functions = 'includes/functions/';
    $tblJs = 'layout/js/';
    $tblCss = 'layout/css/';

    include $functions . 'functions.php';

    include $tmp . 'header.php';

?>
