<?php

	ob_start();
	session_start(); 

	if(!isset($_SESSION['id_acces'])) header("Location:login.php");
	else header("Location:admin.php");

?>