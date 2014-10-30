<?php 
	
session_start();
include("../outils/fonctions.php");	

if(isset($_POST['login']))
	{
	 if(verifUser($_POST['login'], $_POST['password']) == true)
		{
		echo "../admin/admin.php";
		}
	else
		{
		echo false;
		}
	}
else
	{
	header("Location:../pages/global.php");
	}

?>