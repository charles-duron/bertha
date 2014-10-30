<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
		<title><?php if(isset($titre)) echo $titre; else echo "Création de sites internet sur Rennes, l'Ille et Vilaine et la Bretagne"; ?></title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<!--meta qui permettent de g&eacute;rer l'affichage adaptatif sur tous les types d'appareils nomades-->
		<meta name="HandheldFriendly" content="True" />
		<meta name="MobileOptimized" content="480" />
		<meta name="viewport" content="width=480">
		<meta name="description" content="Sites vitrine ou e-commerce, design d'interface, developpement d'interfaces de gestion, referencement. Je me deplace sur Rennes, l'Ille et Vilaine et la Bretagne" />
		<meta name="keywords" content="internet, web, site internet, site web, website, webdesigner, webdesign, développement, développeur, intégration, int&eacute;grateur, Rennes, Saint-Malo, Ille-et-Vilaine, Bretagne, Mayenne, UX, UI, SEO, r&eacutef&eacuterencement, front-end, back-end, back-office, CMS, Wordpress, Prestashop" />
		<link rel="icon" type="image/png" href="../favicon.png" />
		<!--[if IE]><link rel="shortcut icon" type="image/x-icon" href="../favicon.ico" /><![endif]-->
		<link rel="stylesheet" media="screen" type="text/css" title="feuille de style site" href="../css/beew.css" />
		<script type="text/javascript" src="../js/jquery-1.8.3.min.js"></script>
		<script type="text/javascript" src="../js/jquery.easing.1.3.js"></script>
		<script type="text/javascript" src="../js/modernizr.2.5.3.min.js"></script>
		<script type="text/javascript" src="../js/front.js"></script>
	</head>
	<body>
		<form id="form_login" action="../pages/global.php?action=login" method="post" >
			<a href="../pages/global.php" id="sortie_login" /><img src="../img/icones/supprimer.png" /></a>
			<h2>Connexion à votre interface de gestion</h2>
			<label for="login_acces_back_office" >Nom d'utilisateur : </label><br>
			<input tabindex="1" type="text" name="login" id="login" value="<?php if(isset($_SESSION['login'])){echo$_SESSION['login'];}?>" /><br>
			<label for="pass_acces_back_office" >Mot de passe : </label><br>
			<input tabindex="2" type="password" name="password" id="password" value="" /><br>
			<input type="submit" value="Connexion" />
		</form>
	</body>
</html>