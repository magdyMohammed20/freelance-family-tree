<?php

	// Resume Session
	session_start();
	
	// Unset Session
	session_unset();

	// Destroy Session
	session_destroy();

	// Direct User To Login Page
	header("Location: index.php")
?>