<?php
	// Include Database Connect Code
	include 'DBconnect.php';

	$tmp = 'includes/templates/';
	$functions = 'includes/functions/';
	$tblJs = 'layout/js/';
	$tblCss = 'layout/css/';

	include $functions . 'functions.php';
	// If Page Not Has [noNav] Variable I Will Include header,footer,navbar
	if(!isset($noNav)){
		include $tmp . 'header.php';
		include $tmp . 'navbar.php';
		include $tmp . 'footer.php';
	}
?>