<?php
	// permet de virer les phpsessid dans l'url
	ini_set('session.use_cookies', '1');
	ini_set('session.use_trans_sid', '0');
	ini_set('session.use_only_cookies', '1');
	ini_set('url_rewriter.tags', '');
	ob_start();
	session_start();

	include("../outils/fonctions.php");
	include("../outils/affichages.php");
	$indexation = "<meta name=\"robots\" content=\"index,follow\">";
	$connexion = connexion();
	$zone_actus = "actus.html";
	$zone_principale = "page.html";
/*************************
	récupération des paramètres du site
***********************/
	$requete = "SELECT p.*, t.*, c.*, f.* FROM parametres p 
	INNER JOIN templates t 
	INNER JOIN css c 
	INNER JOIN fonts f             
	ON p.id_template=t.id_template 
	AND t.id_template=c.id_template 
	AND p.id_font=f.id_font";
	$sql = $connexion->query($requete);
	$sql->setFetchMode(PDO::FETCH_OBJ);
	$i = 0;
	$_SESSION['style'] = "";
	
	while($ligne = $sql->fetch())
	{
		if($i == 0)
		{
			define("MAIL_RETOUR", $ligne->mail_retour);
			define("MAIL_REPONSE", $ligne->mail_reponse);
			$_SESSION['favicon'] = "<link rel=\"shortcut icon\" href=\"" . $ligne->favicon . "\" type=\"image/x-icon\" />\n<link rel=\"icon\" href=\"" . $ligne->favicon . "\" type=\"image/x-icon\" />\n";      
			$_SESSION['titre_flux'] = $ligne->titre_flux;
			$_SESSION['titre_site'] = $ligne->titre_site;
			$_SESSION['description_flux'] = $ligne->description_flux;             
			$_SESSION['galerie_photos'] = $ligne->galerie_photos;       
			$_SESSION['form_contact'] = $ligne->form_contact;
			$_SESSION['form_recherche'] = $ligne->form_recherche; 
			$_SESSION['nom_font'] = $ligne->nom_font;
			$_SESSION['taille_font'] = $ligne->taille_font;
			$_SESSION['slider'] = $ligne->slider;

			if($ligne->lien_font != "") $_SESSION['lien_font'] = $ligne->lien_font . "\n"; 
			if($ligne->id_page != 0) $page_accueil = "?action=page&id_page=" . $ligne->id_page; 
			else $contenu = "<div id=\"notification_page_accueil\"><a href=\"../admin/admin.php?action=parametres&amp;cat=1\">Désignez dans les paramètres la page d'accueil à afficher</a></div>\n";

			if($ligne->logo != "")
			{
				$image_flux = explode("/",$ligne->logo);
				$_SESSION['image_flux'] = $image_flux[1] . "/" . $image_flux[2];
				$_SESSION['logo_bo'] = "<a href=\"../pages/global.php?action=apercu\" id=\"logo\"><img src=\"" . $ligne->logo . "\" alt=\"\" /></a>\n";
				$_SESSION['logo_fo'] = "<a href=\"../pages/global.php\" id=\"logo\"><img src=\"" . $ligne->logo . "\" alt=\"" . $ligne->titre_site . "\" /></a>\n";
			}
			else
			{
				$_SESSION['logo_fo'] = "<a href=\"../admin/admin.php?action=parametres&cat=1\" id=\"logo\"><span>Insérez le logo<br />dans les paramètres</span></a>\n";
				$_SESSION['logo_bo'] = "<a href=\"../pages/global.php?action=apercu\" id=\"logo\"><span>BERTHA</span><br /><span>C'est du lourd</span></a>\n";
			} 
			if($ligne->calendrier == "oui")
			{
				$calendrier = "calendrier.php";
				$_SESSION['style'] .= "<link rel=\"stylesheet\" type=\"text/css\"  href=\"../css/calendrier.css\"/>\n";
			}
			if($ligne->rss == "oui") $rss = "<a href=\"rss.php\" id=\"rss\" title=\"Flux RSS\" target=\"_blank\"><img src=\"../img/icones/rss.png\" /></a>\n";
			if($ligne->favicon != "")
			{
				$favicon="<link rel=\"shortcut icon\" href=\"" . $ligne->favicon . "\" type=\"image/x-icon\" />\n";
				$favicon.="<link rel=\"icon\" href=\"" . $ligne->favicon . "\" type=\"image/x-icon\" />\n";
			}  

			if($ligne->reseaux == "oui")
			{
				$bloc_reseaux = "<div id=\"bloc_reseaux\">\n";
				$tab_reseaux = array("facebook", "twitter", "googleplus", "linkedin", "viadeo", "pinterest", "flickr");
				$tab_replace = array("Facebook", "Twitter", "Google +", "Linkedin", "Viadeo", "Pinterest", "Flickr");
				$reseaux = json_decode($ligne->liste_reseaux, true);
				for($i = 0; $i < count($reseaux); $i++)
				{
					if($reseaux[$i][1] == "oui")
					{
						$bloc_reseaux .= "<a href=\"" . $reseaux[$i][2] . "\" title=\"" . str_replace( $tab_reseaux, $tab_replace, $tab_reseaux[$i]) . "\" target=\"_blank\"><img src=\"../img/icones/" . $tab_reseaux[$i] . "f.png\" alt=\"compte_" . $tab_reseaux[$i] . "\" /></a>\n";
					}
				}
				$bloc_reseaux .= "</div>\n";
			} 

			if($ligne->syndication == "oui") $syndication = "syndication.php";
		}
		$_SESSION['style'] .= "<link rel=\"stylesheet\" type=\"text/css\"  href=\"" . $ligne->lien_css . "\"/>\n";
		$_SESSION['style_admin'] = "<link rel=\"stylesheet\" type=\"text/css\" href=\"../css/admin" . $ligne->id_theme . ".css\" />\n";      
		$i++;
	}       
/*************************
	construction du menu des langues (uniquement s'il y a plus de 2 langues)
***********************/
	$requete = "SELECT count(*) FROM langues";
	$sql = $connexion->prepare($requete);
	$sql->execute();
	$nb = $sql->fetchColumn();
	if($nb > 1)
	{
		$requete = "SELECT l.*, r.* FROM langues l INNER JOIN rubriques r ON l.id_langue=r.id_langue GROUP BY r.id_langue ORDER BY l.symbole DESC";
		$sql = $connexion->query($requete);
		$sql->setFetchMode(PDO::FETCH_OBJ);
		$menu_symbole = "<div id=\"langue\">\n";  
		$menu_symbole .= "<ul>\n";
		while($ligne = $sql->fetch())
		{
			$menu_symbole .= "<li><a href=\"global.php?symbole=" .$ligne->id_langue."\" title=\"" . $ligne->pays . "\">" . $ligne->symbole. "</a></li>\n";
		} 
		$menu_symbole .= "</ul>\n";
		$menu_symbole .= "</div>\n";  
	}

	if(!isset($_SESSION['langue']))
	{
		$requete = "SELECT * FROM langues WHERE pays='france' OR symbole='FR'";
		$sql = $connexion->query($requete);
		$sql->setFetchMode(PDO::FETCH_OBJ);
		$ligne = $sql->fetch();  
		$_SESSION['langue'] = $ligne->id_langue;
	}
	else
	{
		if(isset($_GET['symbole'])) $_SESSION['langue']=$_GET['symbole'];
	}
/*************************
	construction du menu haut
***********************/
	$etat_sous_menu = "class=\"slide element_slide\"";

	$requete = "SELECT * FROM rubriques WHERE id_langue='" . $_SESSION['langue'] . "' ORDER BY rang";
	$sql = $connexion->query($requete);
	$sql->setFetchMode(PDO::FETCH_OBJ);
	$menu = "<ul id=\"menu\">\n";
	while($ligne = $sql->fetch())
	{
		$menu .= "<li class=\"element_slide\">\n";
		$menu .= "<a href=\"javascript:void(0)\">" . $ligne->rubrique . "</a>\n";

		$requete = "SELECT count(*) FROM pages WHERE id_rubrique='" . $ligne->id_rubrique . "' AND visible = 'oui'";
		$sql_count = $connexion->prepare($requete);
		$sql_count->execute();
		$nb = $sql_count->fetchColumn();
		if($nb > 0)
		{
			$requete = "SELECT * FROM pages WHERE id_rubrique='" . $ligne->id_rubrique . "'  AND visible = 'oui' ORDER BY rang";
			$sql2 = $connexion->query($requete);
			$sql2->setFetchMode(PDO::FETCH_OBJ);
			$menu .= "<ul " . $etat_sous_menu . ">\n";
			while($ligne2 = $sql2->fetch())
			{
				$menu .= "<li>\n";
				$menu .= "<a href=\"global.php?action=page&amp;id_page=" . $ligne2->id_page . "\">" . $ligne2->titre_page . "</a>\n";
				$menu .= "</li>\n";            
			}
			$menu .= "</ul>\n";     
		}
		$menu .= "</li>\n";
	}

	if(isset($_SESSION['form_recherche']) && $_SESSION['form_recherche'] == "oui") $form_recherche = "form_recherche.html";
	if(isset($_SESSION['galerie_photos']) && $_SESSION['galerie_photos'] == "oui") $menu .= "<li><a href=\"global.php?action=galerie_photos\">Galerie photos</a></li>\n";         
	if(isset($_SESSION['form_contact']) && $_SESSION['form_contact'] == "oui") $menu .= "<li><a href=\"global.php?action=contact\">Contact</a></li>\n";  

	$menu .= "</ul>\n";//fermeture du menu général
	$menu .= "<hr />\n"; //pour rendre détectable la hauteur du menu déroulé
	$menu .= "<a href=\"javascript:void(0)\">
	<img " . $etat_sous_menu . " id=\"fermer\" src=\"../img/icones/fermer.png\" alt=\"fermer\" />
	</a>\n";
/*************************
	construction du slider d'intro
***********************/
	if($_SESSION['slider'] == 'oui')
	{
		$requete = "SELECT count(*) FROM medias WHERE alt_media !='' AND slide='oui'";
		$sql = $connexion->prepare($requete);
		$sql->execute();
		$nb = $sql->fetchColumn();
		
		if($nb > 0)
		{
			$_SESSION['style'] .= "<link rel=\"stylesheet\" type=\"text/css\"  href=\"../css/mini_slide.css\"/>\n";
			$requete = "SELECT * FROM medias WHERE alt_media !='' AND slide='oui'";
			$sql = $connexion->query($requete);
			$sql->setFetchMode(PDO::FETCH_OBJ);
			$slider = "<ul id=\"mini_slide\">\n";
			while($ligne = $sql->fetch())
			{
				$slider .= "<li><a href=\"javascript:void(0)\"><img src=\"../img/medias/" . $ligne->titre_media . "." . $ligne->fichier_media . "\" /><p>" . $ligne->alt_media . "</p></a></li>\n";
			}
			$slider .= "</ul>\n";
		}
	}
/*************************
	construction de la liste des actus	
	
	1. on stocke les actus valides dans un tableau
***********************/
	$sql = $connexion->query("SELECT * FROM actus");
	$sql->setFetchMode(PDO::FETCH_OBJ);
	$i = 0;
	while($ligne = $sql->fetch())
	{
		if(($ligne->date_debut_actu == "0000-00-00" && $ligne->date_fin_actu == "0000-00-00") || 
		($ligne->date_debut_actu == "0000-00-00" && $ligne->date_fin_actu >= date_default_timezone_set("Y-m-d")) || 
		($ligne->date_debut_actu <= @date("Y-m-d") && $ligne->date_fin_actu == "0000-00-00") ||
		($ligne->date_debut_actu <= @date("Y-m-d") && $ligne->date_fin_actu >= @date("Y-m-d")))
		{
			$tab_actus[$i] = $ligne->id_actu;
			$i++;
		}
	}
/*************************
	2. on affiche pour chaque id_actu trouvée
	2.1 requete
***********************/
	if(isset($tab_actus))
	{
		$requete_test = "SELECT count(*) FROM actus WHERE id_actu IN ";
		$requete = "SELECT * FROM actus WHERE id_actu IN ";
		for($i = 0; $i < sizeof($tab_actus); $i++)
		{
			if(isset($tab_actus[$i]))
			{
				if($i == 0)
				{
					$requete_test .= "('" . $tab_actus[$i] . "'";
					$requete .= "('" . $tab_actus[$i] . "'";
				}
				else
				{
					$requete_test .= ",'" . $tab_actus[$i] . "'";
					$requete .= ",'" . $tab_actus[$i] . "'";
				}
			}
		}
		$requete_test .= ") AND id_langue='" . $_SESSION['langue'] . "'";  
		$requete .= ") AND id_langue='" . $_SESSION['langue'] . "' ORDER BY date_creation_actu DESC";
		$sql_test = $connexion->prepare($requete_test);
		$sql_test->execute();
		$nb = $sql_test->fetchColumn();
/*************************
	2.2 construction du tableau contenant les actus
***********************/
		if($nb > 0)
		{
			$sql = $connexion->query($requete);
			$sql->setFetchMode(PDO::FETCH_OBJ);
			
			$actus = "<div id=\"actus\">\n";
			$i = 1;
			$regex = '/\[([^\]]*)\]/';// expression régulière permettant de récupérer tout ce qui se trouve entre crochets    
		
			if(sizeof($tab_actus) > 1)
			{
				while($ligne = $sql->fetch())
				{
					$actus .= "<div id=\"bloc_" . $i . "\" class=\"bloc\">\n";
					$actus .= "<h3>" . $ligne->titre_actu . "</h3>\n"; 
					$actus .= "<p class=\"crea\">" . time_ago($ligne->date_creation_actu) . "</p>\n";           

					preg_match_all($regex, $ligne->contenu_actu, $tab_medias);// on recherche toutes les expressions entre crochets
					if(sizeof($tab_medias[1]) > 0)
					{
	/*************************
		2.3 pour chaque media retrouvé entre les crochets
		// à optimiser
	***********************/
						for($j = 0; $j < sizeof($tab_medias[1]); $j++) //[1] pour le résultat sans le séparateur [] et [0] avec séparateur
						{
							$requete = "SELECT * FROM medias WHERE id_media='" . $tab_medias[1][$j] . "'";
							$sql2 = $connexion->query($requete);
							$sql2->setFetchMode(PDO::FETCH_OBJ);
							$ligne_media = $sql2->fetch();
							if($ligne_media->fichier_media == "pdf")
							{
								$new_media[$j] = "<p id=\"pdf" . $ligne_media->id_media . "\"><a href=\"../img/medias/" . $ligne_media->titre_media . "." . $ligne_media->fichier_media . "\" target=\"_blank\">" . $ligne->titre_actu . "</a></p>\n";
							}
							if($ligne_media->fichier_media != "pdf")
							{
								$new_media[$j] = "<img id=\"img" . $ligne_media->id_media . "\" src=\"../img/medias/" . $ligne_media->titre_media . "." . $ligne_media->fichier_media . "\" alt=\"" . $ligne_media->alt_media . "\" />\n";
							}              
							if($ligne_media->lien_media != "") $new_media[$j] = $ligne_media->lien_media;
						}
	/*************************
		2.3.1 pour chaque média trouvé entre crochet on remplace le raccourci par le code html du média
	***********************/
						$tab_zone_remplace = str_replace($tab_medias[0],$new_media,$ligne->contenu_actu);       
						$actus .= "<p>" . $tab_zone_remplace . "</p>\n";                          
					}
					else $actus.="<p>" . $ligne->contenu_actu . "</p>\n";

					$actus .= "<hr />\n";          
					$actus .= "</div>\n";
					$i++;
				}
			}
			else
			{
				$ligne = $sql->fetch();
				
				$actus .= "<div id=\"bloc_1\" class=\"bloc\">\n";
				$actus .= "<h3>" . $ligne->titre_actu . "</h3>\n";
				$actus .= "<p class=\"crea\">" . time_ago($ligne->date_creation_actu) . "</p>\n";           

				preg_match_all($regex, $ligne->contenu_actu, $tab_medias);// on recherche toutes les expressions entre crochets
				if(sizeof($tab_medias[1]) > 0)
				{
	/*************************
	2.3 pour chaque media retrouvé entre les crochets
	***********************/
					for($j = 0; $j < sizeof($tab_medias[1]); $j++) //[1] pour le résultat sans le séparateur [] et [0] avec séparateur
					{
						$requete = "SELECT * FROM medias WHERE id_media='" . $tab_medias[1][$j] . "'";
						$sql = $connexion->query($requete);
						$sql->setFetchMode(PDO::FETCH_OBJ);
						$ligne_media = $sql->fetch();
						if($ligne_media->fichier_media == "pdf")
						{
							$new_media[$j] = "<p id=\"pdf" . $ligne_media->id_media . "\"><a href=\"../img/medias/" . $ligne_media->titre_media . "." . $ligne_media->fichier_media . "\" target=\"_blank\">" . $ligne->titre_actu . "</a></p>\n";
						}
						if($ligne_media->fichier_media != "pdf")
						{
							$new_media[$j] = "<img id=\"img" . $ligne_media->id_media . "\" src=\"../img/medias/" . $ligne_media->titre_media . "." . $ligne_media->fichier_media . "\" alt=\"" . $ligne_media->alt_media . "\" />\n";
						}              
						if($ligne_media->lien_media != "") $new_media[$j] = $ligne_media->lien_media;
					}
	/*************************
	2.3.1 pour chaque média trouvé entre crochet on remplace le raccourci par le code html du média
	***********************/
					$tab_zone_remplace = str_replace($tab_medias[0],$new_media,$ligne->contenu_actu);       
					$actus .= "<p>" . $tab_zone_remplace . "</p>\n";                          
				}
				else $actus.="<p>" . $ligne->contenu_actu . "</p>\n";

				$actus .= "<hr />\n";          
				$actus .= "</div>\n";
			}
			$actus .= "</div>\n";
		}
	}
/************************************************



	SWITCH
	
	
	
**********************************************/
	if(isset($_GET['action']))
	{
		switch($_GET['action'])
		{
			case "apercu":
			
				$_SESSION['retour_bo'] = "<div id=\"retour_bo\"><a href=\"../admin/admin.php\">BACK OFFICE</a></div>\n";
				
				if(isset($page_accueil)) header("Location:global.php" . $page_accueil . "");

			break;

			case "calendrier":
				
				if(isset($_GET['id_evenement']))
				{
					$requete = "SELECT * FROM evenements WHERE id_evenement='" . $_GET['id_evenement'] . "'";
					$sql = $connexion->query($requete);
					$sql->setFetchMode(PDO::FETCH_OBJ);
					$ligne = $sql->fetch();
					
					$contenu = "<div id=\"gabarit1\">\n";
					
					if($ligne->date_debut_evenement != $ligne->date_fin_evenement)
					{
						$contenu.="<h1 id=\"titre_evenement\">Du " . $ligne->date_debut_evenement . " au " . $ligne->date_debut_evenement . "</h1>\n";
					}
					else $contenu .= "<h1>Le " . $ligne->date_debut_evenement . "</h1>\n";

					$contenu .= "<h2>" . $ligne->titre_evenement . "</h2>\n";  
					$contenu .= $ligne->contenu_evenement;
					$contenu .= "</div>\n";       
				}   

			break;

			case "recherche":
/*************************
	1 on créé une requete qui rassemble tous les champs sur lesquels on souhaite rechercher
***********************/
				$requete = "SELECT r.*, p.* FROM rubriques r INNER JOIN pages p ON r.id_rubrique=p.id_rubrique AND r.id_langue='" . $_SESSION['langue'] . "'";
				$sql = $connexion->query($requete);
				$sql->setFetchMode(PDO::FETCH_OBJ);
				while($ligne = $sql->fetch())
				{
					$requete = "UPDATE pages SET recherche='"
					. addslashes(strip_tags($ligne->rubrique . " "
					. $ligne->titre_page . " "                                     
					. $ligne->titre_google . " " 
					. $ligne->zone1 . " " 
					. $ligne->zone2 . " " 
					. $ligne->zone3 . " "
					. $ligne->liste_mots)) . "'  
					WHERE id_page='" . $ligne->id_page . "'";
					$sql2 = $connexion->exec($requete);           
				}
/*************************
	2  si on envoie la recherche
***********************/
				if(isset($_POST['submit']))
				{
					$_SESSION['recherche'] = $_POST['recherche'];

/*************************
	2.1  on vérifie si il y a un ou plusieurs mots 
***********************/
					$tab_mots = explode(" ",$_POST['recherche']); 
/*************************
	2.1.1  si il y a plusieurs mots on fait une recherche par mots (mot1 OU mot2 etc.)   
***********************/
					if(sizeof($tab_mots)>1)
					{
						$requete = "SELECT r.*, p.* FROM rubriques r 
						INNER JOIN pages p 
						ON 
						r.id_rubrique=p.id_rubrique 
						WHERE ";      
						for($i = 0; $i < sizeof($tab_mots); $i++)
						{
							if($i != 0) $requete.=" OR ";

							$requete .= "p.recherche LIKE '%" . $tab_mots[$i] . "%'"; 
						}
						$requete .= " ORDER BY date_page DESC"; 
					}
/*************************
	2.1.2  si il y a un + entre les mots (mot1 ET mot2)
***********************/
					else
					{
						$recherche = str_replace("+"," ",$_POST['recherche']);
						$requete = "SELECT r.*, p.* FROM rubriques r 
						INNER JOIN pages p 
						ON 
						r.id_rubrique=p.id_rubrique 
						WHERE p.recherche LIKE '%" . $recherche . "%'";   
					}
					$contenu = "<h1>Résultat(s) de votre recherche...</h1>\n";
					$contenu .= afficher_resultats_recherche($requete, $connexion);  
				}

			break;    

			case "galerie_photos":
				
				$zone_actus = "vide.html";
				$contenu = "<h1>Galerie photos</h1>\n";    
				$fancybox = "<link rel=\"stylesheet\" type=\"text/css\" href=\"../css/jquery.fancybox-1.3.4.css\" />\n";
				$fancybox .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"../css/fancybox.css\" />\n";      
				$fancybox .= "<script type=\"text/javascript\" src=\"../js/jquery.fancybox-1.3.4.pack.js\"></script>\n";
				$fancybox .= "<script type=\"text/javascript\">\$(document).ready(function() {\$(\"a#a_fancy\").fancybox();});</script>\n";      

				$requete = "SELECT * FROM medias WHERE alt_media!='' AND press_book='oui'";
				$sql = $connexion->query($requete);
				$sql->setFetchMode(PDO::FETCH_OBJ);
				$contenu .= "<div id=\"galerie_photos\">\n";
				while($ligne = $sql->fetch())
				{
					if(strlen($ligne->alt_media)>30) $legende = substr($ligne->alt_media,0,26) . "...";
					else $legende = $ligne->alt_media;

					$image = "../img/medias/" . $ligne->titre_media . "." . $ligne->fichier_media;
					$contenu .= "<div style=\"background-image:url(../img/medias/" . $ligne->titre_media . "." . $ligne->fichier_media . ")\">\n<a id=\"a_fancy\" rel=\"group\" href=\"" . $image . "\" title=\"" . $legende . "\">" . $legende . "\n</a>\n</div>\n";
				}
				$contenu .= "</div>\n";

				$requete = "SELECT r.*, m.*, a.* 
				FROM ranger_medias r 
				JOIN medias m
				ON m.id_media = r.id_media
				JOIN albums a 
				ON r.id_album = a.id_album 
				ORDER BY a.id_album";
				$sql = $connexion->query($requete);
				$sql->setFetchMode(PDO::FETCH_OBJ);
				$i = 0;
				while($ligne = $sql->fetch())
				{
					if($i == 0)
					{
						$contenu .= "<div id=\"albums\">\n
						<h2 class=\"deroul\">Photos classées dans l'album " . $ligne->titre_album . "</h2>\n
						<div class=\"album ouvert\" style=\"background-color:rgb(240,240,240);\">";
					}
					if($i != 0 && ($id_album != $ligne->id_album))
					{
						$contenu .= "</div>\n
						<br/>\n
						<h2 class=\"deroul\">Photos classées dans l'album " . $ligne->titre_album . "</h2>\n
						<div class=\"album ferme\" style=\"height:10px; background-color:rgb(200,200,200);\">\n";
					}
					if(strlen($ligne->alt_media)>30) $legende = substr($ligne->alt_media,0,26) . "...";
					else $legende = $ligne->alt_media;

					$image = "../img/medias/" . $ligne->titre_media . "." . $ligne->fichier_media;
					$contenu .= "<div style=\"background-image:url(../img/medias/" . $ligne->titre_media . "." . $ligne->fichier_media . ")\">\n<a id=\"a_fancy\" rel=\"group\" href=\"" . $image . "\" title=\"" . $legende . "\">" . $legende . "\n</a>\n</div>\n";

					$id_album = $ligne->id_album;
					$i++;
				}
				if($i > 0) {$contenu .= "</div>\n</div>";}
			
			break;

			case "contact":
				
				$zone_principale = "contact.html"; 
				$zone_actus = "vide.html";
				
				if(isset($_POST['submit']))
				{
					if(empty($_POST['prenom_contact']))
					{
						$avertissement = "<label id=\"avertissement\">Veuillez entrer votre prénom</label>\n";
						$color_champ['prenom_contact'] = " id=\"color_champ\"";
					}
					elseif(empty($_POST['nom_contact']))
					{
						$avertissement = "<label id=\"avertissement\">Veuillez entrer votre nom</label>\n";
						$color_champ['nom_contact'] = " id=\"color_champ\"";
					}
					elseif(empty($_POST['mel_contact']))
					{
						$avertissement = "<label id=\"avertissement\">Veuillez entrer votre email</label>\n";
						$color_champ['mel_contact'] = " id=\"color_champ\"";
					}
					elseif(empty($_POST['objet']))
					{
						$avertissement = "<label id=\"avertissement\">Veuillez entrer un objet</label>\n";
						$color_champ['objet'] = " id=\"color_champ\"";
					}          
					elseif(empty($_POST['message']))
					{
						$avertissement = "<label id=\"avertissement\">Veuillez entrer votre message</label>\n";
						$color_champ['message'] = " id=\"color_champ\"";
					} 
					elseif(empty($_POST['captcha']))
					{
						$avertissement = "<label id=\"avertissement\">Recopiez le code de sécurité</label>\n";
						$color_champ['captcha'] = " id=\"color_champ\"";
					}          
					elseif($_SESSION['captcha'] != $_POST['captcha'])
					{
						$avertissement = "<label id=\"avertissement\">Le code saisi n'est pas conforme</label>\n";
						$color_champ['captcha'] = " id=\"color_champ\"";          
					}                                        
					else
					{
						$requete = "INSERT INTO contacts SET prenom_contact='" . $_POST['prenom_contact'] . "',
						nom_contact='" . $_POST['nom_contact'] . "',
						mel_contact='" . $_POST['mel_contact'] . "',
						entreprise='" . $_POST['entreprise'] . "',
						objet='" . $_POST['objet'] . "',
						message='" . $_POST['message'] . "',
						date_contact='" . date_default_timezone_set("Y-m-d H:i:s") . "'";                                             
						$sql = $connexion->query($requete);
						$sql->setFetchMode(PDO::FETCH_OBJ);
/*************************
	envoi des confirmations par mail
***********************/
						$message_recu = str_replace("javascript:","",$_POST['message']); //pour lutter contre la faille xss 
						$type = "html";
						envoi_mel($_POST['mel_contact'], "OBJET_MAIL", "MESSAGE_MAIL","From:" . MAIL_REPONSE . "\r\n", $type);          
						envoi_mel("MAIL_RETOUR", "Contact Internet : " . $_POST['objet'] . "\n","SOCIETE : " . $_POST['entreprise'] . "\nCONTACT : " . $_POST['prenom_contact'] . " " . $_POST['nom_contact'] . "\nMESSAGE : " . $message_recu,"From: " . $_POST['mel_contact'] . "\r\n", $type);

						header("Location:global.php?page=merci_contact");                                
					}         
				}

			break;

			case "page":

				if(isset($_GET['id_page']))
				{
					$zone_principale = "page.html";  
					$requete = "SELECT * FROM pages WHERE id_page='" . $_GET['id_page'] . "'";
					$sql = $connexion->query($requete);
					$sql->setFetchMode(PDO::FETCH_OBJ);
					$ligne = $sql->fetch();
					$meta_description = "<meta name=\"description\" content=\"" . $ligne->meta . "\" />\n";
					$titre_google = $ligne->titre_google;
					if($ligne->keyword == "oui") $meta_keywords="<meta name=\"keywords\" content=\"" . $ligne->liste_mots . "\" />\n"; 
					if($ligne->indexation=='non') $indexation="<meta name=\"robots\" content=\"noindex,follow\">";

					$contenu = "<div id=\"gabarit" . $ligne->id_gabarit . "\">\n";
					$contenu .= "<h1>" . $ligne->titre_page . "</h1>\n";
/*************************
	on créé un tableau qui stocke le nb de zones remplies
***********************/
					$tab_zone = array($ligne->zone1,$ligne->zone2,$ligne->zone3);

					$j = 1;	// compteur des zones
					for($i = 0; $i <sizeof($tab_zone); $i++)
					{
					$contenu .= "<div id=\"zone" . $j . "\">\n";
					$contenu .= $tab_zone[$i] . "<hr />\n";
					$contenu .= "</div>\n";            
					$j++;     
					}               
					$contenu.="<hr />\n</div>\n";
				}   

			break;    
			
			case 'login':
			
				if(isset($_POST['login']))
				{
					if(verifUser($_POST['login'], $_POST['password']) == true)
					{
						header("location:../admin/admin.php");
					}
					else
					{
						echo false;
					}
				}
				else
				{
				header("location:../index.php");
				}

			break;

		}
	}
/************************************************



	FIN SWITCH
	
	
	
**********************************************/
	else
	{
		header("Location:global.php" . $page_accueil . "");
		break;
	}  
	$connexion = null;
	include("global.html");
?>