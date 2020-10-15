<?php
	$dsn = 'mysql:host=localhost;dbname=shop';
	$user = 'root';
	$pass = '';

	$options = Array(
		PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
	);

	try{
		$conn = new PDO($dsn,$user,$pass,$options);
		$conn->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
		

	}catch(PDOException $e){
		
	}
?>