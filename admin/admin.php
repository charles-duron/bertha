<?php
/************************************************************************************************************************







	FONCTIONNEMENT DE L'INTERFACE D'ADMINISTRATION
	
	
	
	
	
	
	
***********************************************************************************************************************/
	ob_start();	//pour faire fonctionner les redirections header
	session_start();	// permet l'usage des variables de session dont la durée de vie n'est pas éphémère(24mn alors que GET et POST = qq secondes)
	
	if(!isset($_SESSION['id_acces']))	//si la personne qui tente d'accéder au back n'est pas identifiée, on la renvoie au front
	{
		header("Location:../pages/global.php");
	}
	else
	{
		include("../outils/fonctions.php");
		include("../outils/affichages.php");  
		include("../outils/a_voir.php");	//fonctionnalités dont l'utilité reste à vérifier
		$connexion = connexion();
		
		$personne_connectee = "<span id=\"connecte\">" . $_SESSION['prenom'] . " " . $_SESSION['nom'] . " [" . $_SESSION['statut'] . "]</span>";	//nom du visiteur
		$titre = "Bonjour " . $_SESSION['prenom'] . "<br />\n<span>En forme pour piloter ton site ?</span>\n";
/*************************

	debug
	
***********************/
		$toto = '';
		if(isset($_SESSION['toto']))
		{
			$toto = $_SESSION['toto'];
			unset($_SESSION['toto']);
		}
/*************************

	camembert accueil backoff (a_voir)
	
***********************/
		$requete = "SHOW TABLE STATUS";
//		$sql = camembert_accueil($connexion->query("SHOW TABLE STATUS"));
//		$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
		$contenu = "intro.html";

		$sql = $connexion->prepare("SELECT count(id_contact) FROM contacts WHERE rep='non'");	//on vérifie la présence ou non de contact non répondu
		$sql->execute();
		$nb_contact = $sql->fetchColumn();
		if($nb_contact > 0) $notification = "<span id=\"notification\">" . $nb_contact . "</span>"; 
		else $notification = "";  

		$affichage_aide = "aide.html";	//on propose en zone centre_amovible une aide sur les fonctionnalités de la grosse Bertha

		if(isset($_GET['action']))
		{
			if(isset($_SESSION['avertissement']))	// on gère l'affichage des messages d'avertissement en session ou pas 
			{         
				$avertissement = $_SESSION['avertissement']; 
				unset($_SESSION['avertissement']);     
			}
			
			$affichage_aide = "vide.html";	// on masque le fichier d'aide qui ne s'affiche qu'en intro
			$actif[$_GET['action']] = " id=\"actif\"";	//on calcule une variable qui gardera le lien actif (donc coloré) en évidence
/***************************************************************************





	DEBUT SWITCH
	
	
	
	
	
***************************************************************************/
			switch($_GET['action'])
			{
			
				case "deconnecter":
				
					$_SESSION = array();
					session_destroy();
					header("Location:../"); 

				break;

				case "intro":
				
					$contenu = "intro.html";
					$affichage_aide = "aide.html";	// on propose en zone centre_amovible une aide sur les fonctionnalités de la grosse Bertha
					$titre = "la grosse Bertha, la CMS de votre vie<br />\n<span>Version 2.0</span>\n";

				break;
/*************************

	cas parametres
	
***********************/
				case "parametres":
				
					$contenu = "form_parametres.html";
					$titre = "Gestion des paramètres";
					$bouton_form = "Enregistrer";
					$action_form = "admin.php?action=parametres";
					$tab_taille_font = array("","0.8vw","0.9vw","1vw","1.1vw");
					
					if(isset($_GET['cat']))
					{
						$cat[$_GET['cat']]=" id=\"affiche_fieldset\"";
						$action_form="admin.php?action=parametres&amp;cat=" . $_GET['cat'];
					}					
/*************************
	on initialise les valeurs des options à zéro
***********************/
					$checked1['non'] = " checked=\"checked\"";	//contact              
					$checked2['non'] = " checked=\"checked\"";	//galerie
					$checked3['non'] = " checked=\"checked\"";	//moteur de recherche  
					$checked4['non'] = " checked=\"checked\"";	//flux rss
					$checked5['non'] = " checked=\"checked\"";	//reseaux sociaux
					$checked6['non'] = " checked=\"checked\"";	//syndication
					$checked7['non'] = " checked=\"checked\"";	//calendrier      
					$checked8['non'] = " checked=\"checked\"";	//slider      
					$tab_reseaux = array("facebook", "twitter", "googleplus", "linkedin", "viadeo", "pinterest", "flickr");      
					
					if(isset($_POST['submit']))
					{
						$liste_reseaux = array();
						for($i = 0; $i < count($tab_reseaux); $i++)
						{
							$tab = array();
							array_push($tab, ($i + 1));
							if(isset($_POST[$tab_reseaux[$i]]))
							{             
								array_push($tab, "oui");               
							}
							else
							{
								array_push($tab, "non");
							}
							array_push($tab, $_POST['url_' . $tab_reseaux[$i]]);  
							array_push($liste_reseaux, $tab);             
						}                 

						$tab_champ = array("id_font"=>"Choix de la police","taille_font"=>"Taille de la police", "mail_retour"=>"Mail de réception", "mail_reponse"=>"Mail de réponse", "objet_mail"=>"Objet du mail de confirmation", "message_mail"=>"Contenu du mail de confirmation");
						$tab_vide = array();
						$checked1[$_POST['form_contact']] = " checked=\"checked\"";
						$checked2[$_POST['galerie_photos']] = " checked=\"checked\"";
						$checked3[$_POST['form_recherche']] = " checked=\"checked\"";
						$checked4[$_POST['rss']] = " checked=\"checked\"";
						$checked5[$_POST['reseaux']] = " checked=\"checked\"";
						$checked6[$_POST['syndication']] = " checked=\"checked\"";
						$checked7[$_POST['calendrier']] = " checked=\"checked\"";
						$checked8[$_POST['slider']] = " checked=\"checked\"";
						
						while(list($key, $value) = each($tab_champ)) 
						{
							if(empty($_POST[$key]))
							{
								$color_champ[$key] = " class=\"color_champ\"";
								array_push($tab_vide, $value);
							}
						}
						if(!empty($tab_vide))
						{
							$avertissement = "<label id=\"avertissement\">" . champs(count($tab_vide), "debut") . implode(", ", $tab_vide) . champs(count($tab_vide), "fin") ."</label>\n";              
						}         
						else
						{
							$_SESSION['style_admin'] = "<link id=\"link_theme\" href=\"../css/admin" . $_POST['id_theme'] . ".css\" rel=\"stylesheet\" type=\"text/css\" />\n";      

							if($_FILES['logo']['name'] != "")	// si un logo a été choisi avec le formulaire (parcourir)
							{
								if(fichier_type($_FILES['logo']['name']) == "jpg" || fichier_type($_FILES['logo']['name']) == "png" || fichier_type($_FILES['logo']['name']) == "gif")
								{
									$extension=fichier_type($_FILES['logo']['name']);
									$chemin_logo="../img/logo." . $extension;
									
									if(is_uploaded_file($_FILES['logo']['tmp_name']))	// tmp_name correspond au nom temporaire donné au fichier lors de sa copie sur le serveur
									{
										if(copy($_FILES['logo']['tmp_name'], $chemin_logo))
										{                       
											$requete_logo = "UPDATE parametres SET logo='" . $chemin_logo . "'";
											$resultat_logo = $connexion->exec($requete_logo);
										}
									}
								}              
							}   
							if($_FILES['favicon']['name'] != "")
							{
								if(fichier_type($_FILES['favicon']['name']) == "ico" || fichier_type($_FILES['favicon']['name']) == "png") 
								{
									$extension=fichier_type($_FILES['favicon']['name']);
									$chemin_favicon="../img/icones/favicon." . $extension;
									
									if(is_uploaded_file($_FILES['favicon']['tmp_name']))
									{
										if(copy($_FILES['favicon']['tmp_name'], $chemin_favicon))
										{                     
											$resultat_favicon = $connexion->exec("UPDATE parametres SET favicon='" . $chemin_favicon . "'");
										}
									}
								}              
							}
/*************************
	on modifie la table avec les nouvelles valeurs
***********************/
							$requete_parametres="UPDATE parametres SET id_template = '" . $_POST['id_template'] . "', 
							id_font = '" . $_POST['id_font'] . "',
							taille_font = '" . $tab_taille_font[$_POST['taille_font']] . "',                    
							id_theme = '" . $_POST['id_theme'] . "',
							titre_site = '" . $_POST['titre_site'] . "',           
							mail_retour = '" . $_POST['mail_retour'] . "', 
							mail_reponse = '" . $_POST['mail_reponse'] . "', 
							objet_mail = '" . $_POST['objet_mail'] . "', 
							message_mail = '" . $_POST['message_mail'] . "',         
							titre_flux = '" . addslashes($_POST['titre_flux']) . "', 
							description_flux = '" . addslashes($_POST['description_flux']) . "',         
							form_contact = '" . $_POST['form_contact'] . "', 
							galerie_photos = '" . $_POST['galerie_photos'] . "', 
							form_recherche = '" . $_POST['form_recherche'] . "',
							calendrier = '" . $_POST['calendrier'] . "',
							rss = '" . $_POST['rss'] . "',
							reseaux = '" . $_POST['reseaux'] . "',
							liste_reseaux = '" . json_encode($liste_reseaux) . "', 
							id_page = '" . $_POST['id_page'] . "',              
							syndication = '" . $_POST['syndication'] . "',              
							slider = '" . $_POST['slider'] . "'";                    
							$resultat_parametres = $connexion->exec($requete_parametres);
							$avertissement = "<label id=\"ok\">Paramètres enregistrés</label>\n";          
						}        
					}
/*************************
	on recharge les champs du formulaire depuis la table parametres
***********************/
					$sql = $connexion->query("SELECT * FROM parametres");					
					$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);				
					$ligne_parametres = $sql->fetch();
					$_POST['mail_retour'] = $ligne_parametres->mail_retour;
					if($ligne_parametres->mail_reponse != "")
					{
						$_POST['mail_reponse'] = $ligne_parametres->mail_reponse; 
					}
					else
					{
						$_POST['mail_reponse'] = $ligne_parametres->mail_retour; 
					}     
					$_POST['objet_mail'] = $ligne_parametres->objet_mail;
					$_POST['message_mail'] = $ligne_parametres->message_mail;  
					$_POST['logo'] = "<div class=\"fichier_img\" style=\"background:url(" . $ligne_parametres->logo . ") no-repeat center left white;background-size:cover\"><a href=\"" . $ligne_parametres->logo . "\" target=\"_blank\"></a></div>\n";
					$_POST['titre_site'] = $ligne_parametres->titre_site;      
					$_POST['favicon'] = "<div class=\"fichier_img\" style=\"background:url(" . $ligne_parametres->favicon . ") no-repeat center left white\"><a href=\"" . $ligne_parametres->favicon . "\" target=\"_blank\"></a></div>\n";          
					$_POST['titre_flux'] = $ligne_parametres->titre_flux;
					$_POST['description_flux'] = $ligne_parametres->description_flux;                        
					$checked1[$ligne_parametres->form_contact] = " checked=\"checked\""; 
					$checked2[$ligne_parametres->galerie_photos] = " checked=\"checked\"";       
					$checked3[$ligne_parametres->form_recherche] = " checked=\"checked\""; 
					$checked4[$ligne_parametres->rss] = " checked=\"checked\"";       
					$checked5[$ligne_parametres->reseaux] = " checked=\"checked\""; 
					$checked6[$ligne_parametres->syndication] = " checked=\"checked\"";
					$checked7[$ligne_parametres->calendrier] = " checked=\"checked\"";
					$checked8[$ligne_parametres->slider] = " checked=\"checked\"";
					if(!empty($ligne_parametres->liste_reseaux))
					{
						$tab = json_decode($ligne_parametres->liste_reseaux, true);
						for($i = 0; $i < count($tab); $i++)
						{                              
							if($tab[$i][1]  ==  "oui")
							{
								$checked[$tab_reseaux[$tab[$i][0] - 1]] =  "checked=\"checked\"";
							} 
							$_POST['url_' . $tab_reseaux[$tab[$i][0] - 1]] = $tab[$i][2];  
						}
					}      

					if($ligne_parametres->id_template != "") $selected[$ligne_parametres->id_template] = " selected=\"selected\"";  
					if($ligne_parametres->id_theme != "") $selected2[$ligne_parametres->id_theme] = " selected=\"selected\""; 
					if($ligne_parametres->id_page != "") $selected3[$ligne_parametres->id_page] = " selected=\"selected\""; 
					if($ligne_parametres->id_font != "") $selected4[$ligne_parametres->id_font] = " selected=\"selected\""; 
					if($ligne_parametres->taille_font != "")
					{
						$rang_font = array_search($ligne_parametres->taille_font, $tab_taille_font);
						$selected5[$rang_font] = " selected=\"selected\""; 
					}
/*************************
	on créé la liste déroulante des templates
***********************/
					$sql = $connexion->query("SELECT * FROM templates ORDER BY nom_template");					
					$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
					
					$ld_templates = "<option value=\"\">Sélectionner...</option>\n";
					
					while($ligne_templates = $sql->fetch())
					{
						if($ligne_templates->id_template == $ligne_parametres->id_template)
						{
							$select = $selected[$ligne_templates->id_template];
						}
						else
						{
							$select ="";
						}            
						$ld_templates .= "<option value=\"" . $ligne_templates->id_template . "\"" . $select . ">" . $ligne_templates->nom_template . "</option>\n";               
					}
/*************************
	on créé la liste déroulante des pages
***********************/
					$sql = $connexion->query("SELECT * FROM pages ORDER BY titre_page");					
					$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
					
					$ld_pages = "<option value=\"\">Sélectionner...</option>\n";
					
					while($ligne_pages = $sql->fetch())
					{
						if($ligne_pages->id_page == $ligne_parametres->id_page)
						{
							$select = $selected3[$ligne_pages->id_page];
						}
						else
						{
							$select = "";
						}            
						$ld_pages .= "<option value=\"" . $ligne_pages->id_page . "\"" . $select . ">" . $ligne_pages->titre_page . " (page " . $ligne_pages->id_page . ")</option>\n";               
					}
/*************************
	on créé la liste déroulante des polices
***********************/
					$sql = $connexion->query("SELECT * FROM fonts ORDER BY nom_font");					
					$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
					
					$ld_fonts = "<option value=\"\">Sélectionner...</option>\n";
					
					while($ligne_fonts = $sql->fetch())
					{
						if($ligne_fonts->id_font == $ligne_parametres->id_font)
						{
							$select = $selected4[$ligne_fonts->id_font];
						}
						else
						{
							$select = "";
						}            
						$ld_fonts .= "<option value=\"" . $ligne_fonts->id_font . "\"" . $select . ">" . $ligne_fonts->nom_font . "</option>\n";               
					}

				break;       
/*************************

	cas comptes
	
***********************/
				case "comptes":
				$contenu = "form_comptes.html";
				$titre = "Gestion des comptes";
				$confirm_suppression = "vide.html"; 
				$action_form = "comptes";
				$bouton_form = "CREER"; 
			
				if(isset($_SESSION['id_compte'])) unset($_SESSION['id_compte']);	//on supprimer la variable de session permettant la coloration de la ligne du compte modifié ou supprimé 

				if(isset($_POST['submit']))
				{
/*************************
	on vérifie si tous les champs sont remplis
***********************/
					$tab_champ = array("statut"=>"Statut", "nom"=>"Nom", "prenom"=>"Prénom", "login"=>"Identifiant", "pass"=>"Mot de passe");
					$tab_vide = array();  
					while(list($key, $value) = each($tab_champ)) 
					{            
						if(empty($_POST[$key]))
						{
							$color_champ[$key] = " class=\"color_champ\"";
							array_push($tab_vide, $value);
						}
					}        

					if(!empty($tab_vide)) $avertissement="<label id=\"avertissement\">" . champs(count($tab_vide), "debut") . implode(", ", $tab_vide) . champs(count($tab_vide), "fin") ."</label>\n";              
					else
					{
/*************************
	on teste si le login existe deja
***********************/
						$sql = $connexion->prepare("SELECT count(*) FROM comptes WHERE login='" . $_POST['login'] . "'");					
						$sql->execute();
						$nb_lignes = $sql->fetchColumn();
						
						if($nb_lignes != 0) $avertissement = "<label id=\"avertissement\">L'identifiant <strong>'" . $_POST['login'] . "'</strong> existe déjà</label>\n";						
						else	//si l'identifiant est libre
						{
/*************************
	1 : on n'a pas posté d'image de profil
***********************/
							if(empty($_FILES['fichier_compte']['name']))
							{
								$requete = "INSERT INTO comptes SET statut='" . $_POST['statut'] . "', 
								nom='" . $_POST['nom'] . "',
								prenom='" . $_POST['prenom'] . "',
								login='" . $_POST['login'] . "',
								pass=PASSWORD('" . $_POST['pass'] . "')";  // PASSWORD (en majuscules) est une focntion SQL qui permet de crypter le mot de passe
								$sql = $connexion->exec($requete);	//insertion
								$numero_id_compte_cree = $connexion->lastInsertId();
/*************************
	1.1 : insertion des droits de l'utilisateur
***********************/
								$sql = $connexion->query("SELECT id_module, autorisation FROM modules");
								$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
								$tab_droit = "";
								
								while($ligne = $sql->fetch())
								{
									if(($_POST['statut']  ==  "user" && $ligne->autorisation == 'user') || $_POST['statut']  !=  "user")
									{
										$tab_droit[$ligne->id_module] = 1;
									}
									else
									{
										$tab_droit[$ligne->id_module] = 0;
									}
								}

								$requete = "INSERT INTO droits (id_compte, id_module, valeur) VALUES ";
								$tab_requete = array();
								while(list($key, $value) = each($tab_droit)) 
								{ 
									$tab_requete[] .= "('" . $numero_id_compte_cree . "','" . $key . "','" . $value . "')";  
								}
								$requete .= implode(", ", $tab_requete);
								$sql = $connexion->exec($requete);	//insertion
								
								$avertissement="<label id=\"ok\">Compte créé</label>\n";
								header("Location:admin.php?action=comptes");
							}
/*************************
	2 : une image de profil a été postée : on vérifie si tout est ok pour l'upload
***********************/
							else
							{
								$allowedExts = array("gif", "jpeg", "jpg", "png");
								$temp = explode(".", $_FILES["fichier_compte"]["name"]);
								$extension = end($temp);

								if((($_FILES["fichier_compte"]["type"] == "image/gif")
									|| ($_FILES["fichier_compte"]["type"] == "image/jpeg")
									|| ($_FILES["fichier_compte"]["type"] == "image/jpg")
									|| ($_FILES["fichier_compte"]["type"] == "image/pjpeg")
									|| ($_FILES["fichier_compte"]["type"] == "image/x-png")
									|| ($_FILES["fichier_compte"]["type"] == "image/png"))
									&& in_array($extension, $allowedExts))
								{
									$content_dir = '../img/medias/'; // dossier où sera déplacé le fichier
									$tmp_file = $_FILES['fichier_compte']['tmp_name'];
									$name_file = $_FILES['fichier_compte']['name'];
									$type_file = $_FILES['fichier_compte']['type'];	// on vérifie maintenant l'extension
									
									if(!is_uploaded_file($tmp_file)) exit("Le fichier est introuvable");

									if( !strstr($type_file, 'jpg') && !strstr($type_file, 'jpeg') && !strstr($type_file, 'bmp') && !strstr($type_file, 'gif') && !strstr($type_file, 'png'))
									{
										exit("Le fichier n'est pas une image");
									}
									elseif(strstr($type_file, 'jpg')) $extension = 'jpg';
									elseif(strstr($type_file, 'jpeg')) $extension = 'jpg';
									elseif(strstr($type_file, 'bmp')) $extension = 'bmp';
									elseif(strstr($type_file, 'gif')) $extension = 'gif';
									elseif(strstr($type_file, 'png')) $extension = 'png';

									if(preg_match('#[\x00-\x1F\x7F-\x9F/\\\\]#', $name_file)) exit("Nom de fichier non valide");
									if(is_uploaded_file($tmp_file))	//l'upload s'est bien passé
									{
/*************************
	2.1 : on insère les nouvelles valeurs et les nouveaux droits en base
***********************/
										$requete = "INSERT INTO comptes SET statut='" . $_POST['statut'] . "', 
										nom='" . $_POST['nom'] . "',
										prenom='" . $_POST['prenom'] . "',
										login='" . $_POST['login'] . "',
										pass=PASSWORD('" . $_POST['pass'] . "'),
										fichier_compte='" . $extension . "'";
										$requete_insertion_comptes = $connexion->exec($requete);
										$numero_id_compte_cree = $connexion->lastInsertId();
										
										$sql = $connexion->query("SELECT id_module, autorisation FROM modules");
										$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
										$tab_droit = "";
										
										while($ligne = $sql->fetch())
										{
											if(($_POST['statut']  ==  "user" && $ligne->autorisation == 'user') || $_POST['statut']  !=  "user")
											{
												$tab_droit[$ligne->id_module] = 1;
											}
											else
											{
												$tab_droit[$ligne->id_module] = 0;
											}
										}

										$requete = "INSERT INTO droits (id_compte, id_module, valeur) VALUES ";
										$tab_requete = array();
										while(list($key, $value) = each($tab_droit)) 
										{ 
											$tab_requete[] .= "('" . $numero_id_compte_cree . "','" . $key . "','" . $value . "')";  
										}
										$requete .= implode(", ", $tab_requete);
										$sql = $connexion->exec($requete);	//insertion
								
										$chemin_temp = $content_dir . "compte" . $numero_id_compte_cree . "." . $extension;
										$chemin_compte = $content_dir . "profil" . $numero_id_compte_cree . "." . $extension;
										if(copy($tmp_file, $chemin_temp))	//on redimensionne l'image
										{
/*************************
	2.2 : on redimensionne l'image uploadée
***********************/
											$size = GetImageSize($chemin_temp);
											$src_w = $size[0];
											$src_h = $size[1];
											$rapport = $src_w/$src_h;
											$x = 120;
											redimage($chemin_temp,$chemin_compte,$x,$x/$rapport);
											unlink($chemin_temp);

											$_SESSION['avertissement'] = "<label id=\"ok\">Création de compte effectuée.</label>";
											header("Location:admin.php?action=comptes");
										}
									}
								}
								else $avertissement = "<label id=\"avertissement\">Seules les extensions png, gif et jpg sont autorisées</label>\n";
							}
						}                      
					}    
				}
				
				$where = "";
				$order = "statut, ";
				if($_SESSION['statut']  ==  "admin")
				{
					$where = " WHERE statut='user' OR id_compte='" . $_SESSION['id_acces'] . "'";
					$order = "";
				}
				$requete_comptes = "SELECT * FROM comptes" . $where . " ORDER BY " . $order . "nom, prenom";
				$affichage_centre = afficher_comptes($requete_comptes, "centre", $connexion);  
				$affichage = afficher_comptes($requete_comptes, "droite", $connexion); 

				break;
/*************************

	cas supprimer comptes
	
***********************/
				case "supprimer_comptes" :
					
					$action_form = "comptes";
					$bouton_form = "Créer";
					$contenu = "form_comptes.html" ;
					$titre = "Gestion des comptes";
					$confirm_suppression = "confirm_suppression.html";
					$action_suppression_non = "comptes";
					$action_suppression_oui = "supprimer_comptes&amp;cas=2";    //aurait pu se faire en 2 cas, mais plus simple pour le copicol de faire comme ça  
					$invisible = "style=\"display:none\"";

					$sql = $connexion->query("SELECT * FROM comptes WHERE id_compte='" . $_GET['id_compte'] . "'");
					$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);				
					$ligne = $sql->fetch();
					$precision_suppression = "du compte de " . $ligne->prenom . " " . $ligne->nom . " ";

					if(isset($_GET['id_compte'])) $_SESSION['id_compte'] =$_GET['id_compte'];

					if(isset($_GET['cas']) and $_GET['cas'] == 2)
					{
						if($_SESSION['id_compte']  ==  $_SESSION['id_acces']) $_SESSION['avertissement']="<label id=\"avertissement\">Vous êtes actuellement connecté sur ce compte vous ne pouvez pas le supprimer.</label>";
						else
						{
							$requete_suppression_droits_comptes = $connexion->exec("DELETE FROM droits WHERE id_compte='" . $_SESSION['id_compte'] . "'");

							$sql = $connexion->query("SELECT * FROM comptes WHERE id_compte='" . $_SESSION['id_compte'] . "'");
							$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);				
							$ligne_comptes = $sql->fetch();
							if($ligne_comptes->fichier_compte  !=  "") @unlink("../img/medias/profil" . $_SESSION['id_compte'] . "." . $ligne_comptes->fichier_compte); 

							$requete_suppression_compte = $connexion->exec("DELETE FROM comptes WHERE id_compte='" . $_SESSION['id_compte'] . "'");

							$_SESSION['avertissement'] = "<label id=\"ok\">Suppression de compte effectuée</label>";             
						}
						header("Location:admin.php?action=comptes"); 
					}

					$where = "";
					$order = "statut, ";
					if($_SESSION['statut']  ==  "admin")
					{
						$where = " WHERE statut='user' OR id_compte='" . $_SESSION['id_acces'] . "'";
						$order = "";
					} 
					$requete_comptes = "SELECT * FROM comptes" . $where . " ORDER BY " . $order . "nom,prenom";
					$affichage_centre = afficher_comptes($requete_comptes, "centre", $connexion);  
					$affichage = afficher_comptes($requete_comptes, "droite", $connexion);  

				break;
/*************************

	cas modifier comptes
	
***********************/
				case "modifier_comptes" :
				
					$action_form = "modifier_comptes";
					$bouton_form = "Modifier";
					$contenu = "form_comptes.html" ;
					$titre = "Gestion des comptes";
					$confirm_suppression = "vide.html";

					if(isset($_GET['id_compte']))
					{
						$sql = $connexion->query("SELECT * FROM comptes WHERE id_compte='" . $_GET['id_compte'] . "'");  
						$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
						$ligne_comptes = $sql->fetch();
						$_POST['nom'] = $ligne_comptes->nom;
						$_POST['prenom'] = $ligne_comptes->prenom;
						$_POST['login'] = $ligne_comptes->login;
						$selected[$ligne_comptes->statut] = " selected=\"selected\"";
						$_SESSION['id_compte'] = $_GET['id_compte'];
					}

					if(isset($_POST['submit']))
					{
/*************************
	on vérifie si tous les champs sont remplis
***********************/
						$tab_champ = array("statut"=>"Statut", "nom"=>"Nom", "prenom"=>"Prénom", "login"=>"Identifiant");
						$tab_vide = array();  

						while(list($key, $value) = each($tab_champ)) 
						{
							if(empty($_POST[$key]))
							{
								$color_champ[$key] = " class=\"color_champ\"";
								array_push($tab_vide, $value);
							}
						}

						if(!empty($tab_vide)) $avertissement = "<label id=\"avertissement\">" . champs(count($tab_vide), "debut") . implode(", ", $tab_vide) . champs(count($tab_vide), "fin") ."</label>\n";              
						else	//tout est ok
						{
							if(empty($_FILES['fichier_compte']['name']))	//si l'on n'a pas posté d'image de profil
							{
/*************************
	on commence par modifier les droits
***********************/
								$sql = $connexion->exec("DELETE FROM droits WHERE id_compte = " . $_SESSION['id_compte']);	//1 : on supprime les anciens droits
								
								$sql = $connexion->query("SELECT id_module, autorisation FROM modules");	//2 : on en créé de nouveaux
								$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
								$tab_droit = "";
								
								while($ligne = $sql->fetch())
								{
									if(($_POST['statut']  ==  "user" && $ligne->autorisation == 'user') || $_POST['statut']  !=  "user")
									{
										$tab_droit[$ligne->id_module] = 1;
									}
									else
									{
										$tab_droit[$ligne->id_module] = 0;
									}
								}

								$requete = "INSERT INTO droits (id_compte, id_module, valeur) VALUES ";
								$tab_requete = array();
								while(list($key, $value) = each($tab_droit)) 
								{ 
									$tab_requete[] .= "('" . $_SESSION['id_compte'] . "','" . $key . "','" . $value . "')";  
								}
								$requete .= implode(", ", $tab_requete);
								$sql = $connexion->exec($requete);	//insertion
/*************************
	puis on modifie le compte
***********************/
								$requete_maj_compte = "UPDATE comptes
								SET statut='" . $_POST['statut'] . "',          
								nom='" . $_POST['nom'] . "',
								prenom='" . $_POST['prenom'] . "',                       
								login='" . $_POST['login'] . "'";
								
								if(!empty($_POST['pass'])) $requete_maj_compte .= ", pass=PASSWORD('" . $_POST['pass'] . "')";                                 

								$requete_maj_compte .= " WHERE id_compte='" . $_SESSION['id_compte'] . "'";  
								$sql = $connexion->exec($requete_maj_compte);

								$_SESSION['avertissement'] = "<label id=\"ok\">Modification de compte effectuée.</label>";
								header("Location:admin.php?action=comptes");
							}
							else
							{
/*************************
	une image de profil a été postée : on vérifie que tout est ok pour l'upload
***********************/
								$allowedExts = array("gif", "jpeg", "jpg", "png");
								$temp = explode(".", $_FILES["fichier_compte"]["name"]);
								$extension = end($temp);

								if((($_FILES["fichier_compte"]["type"] == "image/gif")
									|| ($_FILES["fichier_compte"]["type"] == "image/jpeg")
									|| ($_FILES["fichier_compte"]["type"] == "image/jpg")
									|| ($_FILES["fichier_compte"]["type"] == "image/pjpeg")
									|| ($_FILES["fichier_compte"]["type"] == "image/x-png")
									|| ($_FILES["fichier_compte"]["type"] == "image/png"))
									&& in_array($extension, $allowedExts))
								{
									$content_dir = '../img/medias/'; // dossier où sera déplacé le fichier
									$chemin_temp = $content_dir . "compte" . $_SESSION['id_compte'] . "." . $extension;
									$chemin_compte = $content_dir . "profil" . $_SESSION['id_compte'] . "." . $extension;
									$tmp_file = $_FILES['fichier_compte']['tmp_name'];
									$name_file = $_FILES['fichier_compte']['name'];
									$type_file = $_FILES['fichier_compte']['type'];	// on vérifie maintenant l'extension
									
									if(!is_uploaded_file($tmp_file)) exit("Le fichier est introuvable");

									if( !strstr($type_file, 'jpg') && !strstr($type_file, 'jpeg') && !strstr($type_file, 'bmp') && !strstr($type_file, 'gif') && !strstr($type_file, 'png'))
									{
										exit("Le fichier n'est pas une image");
									}
									elseif(strstr($type_file, 'jpg')) $extension = 'jpg';
									elseif(strstr($type_file, 'jpeg')) $extension = 'jpg';
									elseif(strstr($type_file, 'bmp')) $extension = 'bmp';
									elseif(strstr($type_file, 'gif')) $extension = 'gif';
									elseif(strstr($type_file, 'png')) $extension = 'png';

									if(preg_match('#[\x00-\x1F\x7F-\x9F/\\\\]#', $name_file)) exit("Nom de fichier non valide");
									if(is_uploaded_file($tmp_file))
									{
										if(copy($tmp_file, $chemin_temp))	//si tout est ok pour l'upload
										{
/*************************
	on commence par redimensionner l'image uploadée
***********************/
											$size = GetImageSize($chemin_temp);
											$src_w = $size[0];
											$src_h = $size[1];
											$rapport = $src_w/$src_h;
											$x = 120;
											redimage($chemin_temp,$chemin_compte,$x,$x/$rapport);
											unlink($chemin_temp);
/*************************
	on modifie les droits
***********************/
											$sql = $connexion->exec("DELETE FROM droits WHERE id_compte = " . $_SESSION['id_compte']);	//1 : on supprime les anciens droits
											
											$sql = $connexion->query("SELECT id_module, autorisation FROM modules");	//2 : on en créé de nouveaux
											$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
											$tab_droit = "";
											
											while($ligne = $sql->fetch())
											{
												if(($_POST['statut']  ==  "user" && $ligne->autorisation == 'user') || $_POST['statut']  !=  "user")
												{
													$tab_droit[$ligne->id_module] = 1;
												}
												else
												{
													$tab_droit[$ligne->id_module] = 0;
												}
											}

											$requete = "INSERT INTO droits (id_compte, id_module, valeur) VALUES ";
											$tab_requete = array();
											while(list($key, $value) = each($tab_droit)) 
											{ 
												$tab_requete[] .= "('" . $_SESSION['id_compte'] . "','" . $key . "','" . $value . "')";  
											}
											$requete .= implode(", ", $tab_requete);
											$sql = $connexion->exec($requete);	//insertion
/*************************
	puis on modifie le compte
***********************/
											$requete_maj_compte = "UPDATE comptes
											SET statut='" . $_POST['statut'] . "',          
											nom='" . $_POST['nom'] . "',
											prenom='" . $_POST['prenom'] . "',                       
											login='" . $_POST['login'] . "',
											fichier_compte='" . $extension . "'
											WHERE id_compte='" . $_SESSION['id_compte'] . "'";
											$sql = $connexion->exec($requete_maj_compte);
											
											$_SESSION['avertissement'] = "<label id=\"ok\">Modification de compte effectuée.</label>";
											header("Location:admin.php?action=comptes");
										}
									}
								}
								else $avertissement = "<label id=\"avertissement\">Seules les extensions png, gif et jpg sont autorisées</label>\n";
							}
						}
					}
					$where = "";
					$order = "statut, ";
					if($_SESSION['statut']  ==  "admin")
					{
						$where = " WHERE statut='user' OR id_compte='" . $_SESSION['id_acces'] . "'";
						$order = "";
					} 
					$requete_comptes = "SELECT * FROM comptes" . $where . " ORDER BY " . $order . "nom,prenom";
					$affichage_centre = afficher_comptes($requete_comptes, "centre", $connexion);  
					$affichage = afficher_comptes($requete_comptes, "droite", $connexion);  

				break;
/*************************

	cas droits
	
***********************/
				case "droits":

					$contenu = "form_droits.html";
					$titre = "Gestion des droits";
					$h3 = "Modification des droits";

					$sql = $connexion->query("SELECT * FROM modules ORDER BY rang");
					$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
					$tab_id_module = array(); 
					while($ligne_modules = $sql->fetch())
					{
						$tab_module[$ligne_modules->id_module] = $ligne_modules->module;
						$tab_id_module[] .= $ligne_modules->id_module;
					}

					$sql = $connexion->query("SELECT * FROM comptes WHERE id_compte=" . $_GET['id_compte'] . "");
					$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
					$tab = array("super_admin"=>"Super Administrateur", "admin"=>"Administrateur", "user"=>"Utilisateur");
					$droits = "";
					
					while($ligne_compte = $sql->fetch())
					{
						$droits .= "<div class=\"droits\">\n";  
						$droits .= "<h2>" . $ligne_compte->nom . " " . $ligne_compte->prenom . "<br /><span style=\"font-size:11px\">( " . $tab[$ligne_compte->statut] . " )</span></h2>\n";
						$droits .= "<form action=\"admin.php?action=droits&id_compte=" . $ligne_compte->id_compte . "\" method=\"POST\">\n";
						$droits .= "<label class=\"onoff\">ON&nbsp;&nbsp;|&nbsp;&nbsp;OFF</label>\n";
						
						for($i = 0; $i < count($tab_id_module); $i++)	//à optimiser
						{
							$sql = $connexion->prepare("SELECT count(*) FROM droits WHERE id_compte='" . $ligne_compte->id_compte . "' AND id_module='" . $tab_id_module[$i] . "'");
							$sql->execute();
							$nb_droits = $sql->fetchColumn();
							if($nb_droits != 0)
							{
								$sql = $connexion->query("SELECT * FROM droits WHERE id_compte='" . $ligne_compte->id_compte . "' AND id_module='" . $tab_id_module[$i] . "'");
								$ligne_droits = $sql->fetch();
								if($ligne_droits['valeur'] == 1)
								{
									$droits .= "<label>" . $tab_module[$tab_id_module[$i]] . "</label>\n<input checked=\"checked\" type=\"radio\" value=\"1\" name=\"" . $tab_id_module[$i] . "\" />\n<input type=\"radio\" value=\"0\" name=\"" . $tab_id_module[$i] . "\" />\n";
								}
								else
								{
									$droits .= "<label>" . $tab_module[$tab_id_module[$i]] . "</label>\n<input type=\"radio\" value=\"1\" name=\"" . $tab_id_module[$i] . "\" />\n<input checked=\"checked\" type=\"radio\" value=\"0\" name=\"" . $tab_id_module[$i] . "\" />\n";
								}            
							}
							else
							{
								$droits .= "<label>" . $tab_module[$tab_id_module[$i]] . "</label>\n<input type=\"radio\" value=\"1\" name=\"" . $tab_id_module[$i] . "\" />\n<input checked=\"checked\" type=\"radio\" value=\"0\" name=\"" . $tab_id_module[$i] . "\" />\n";
							}          
						}
						$droits .= "<hr />\n<input type=\"submit\" name=\"submit\" value=\"VALIDER\" />\n";              
						$droits .= "</form>\n";
						$droits .= "</div>\n";   
					}

					if(isset($_POST['submit']))
					{
						$nouveaux_droits = array();
					
						foreach($tab_id_module as $module)
						{
							$nouveaux_droits[$module] = $_POST[$module];
						}
						
						$resultat_droits = $connexion->exec("DELETE FROM droits WHERE id_compte='" . $_GET['id_compte'] . "'");

						$requete_droit = "INSERT INTO droits (id_compte, id_module, valeur) VALUES ";
						$tab_requete = array();
						while(list($key, $value) = each($nouveaux_droits)) 
						{ 
							$tab_requete[] .= "('" . $_GET['id_compte'] . "','" . $key . "','" . $value . "')";
						}
						$requete_droit .= implode(", ", $tab_requete);
						$sql = $connexion->exec($requete_droit);
						
						$avertissement = "<label id=\"avertissement\">droits modifiés</label>\n";
						header("Location:admin.php?action=comptes");
					}

					$requete_comptes = "SELECT * FROM comptes ORDER BY nom, prenom";
					$affichage_centre = afficher_comptes($requete_comptes, "centre", $connexion); 

				break;
/*************************

	cas rubriques
	
***********************/
				case "rubriques":
				
					$contenu = "form_rubriques.html";
					$titre = "Gestion des rubriques";
					$confirm_suppression = "vide.html";
					$action_form = "rubriques";
					$bouton_form = "CREER";
				
					$sql = $connexion->query("SELECT * FROM langues ORDER BY pays");	// on créé la liste déroulante dynamique des langues
					$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);  
					$ld_langues = "<option value=\"\">Langues</option>\n";
					while($ligne_langues = $sql->fetch())
					{
						$ld_langues .= "<option value=\"" . $ligne_langues->id_langue . "\">" . $ligne_langues->pays . " (" . $ligne_langues->symbole . ")</option>\n";
					}

					unset($_SESSION['id_rubrique']);	//on efface la variable $_SESSION id_rubrique

					if(isset($_POST['submit']))	//si clic sur bouton CREER
					{
						if(empty($_POST['rubrique']))
						{  
							$avertissement = "<label id=\"avertissement\">Saisissez une nouvelle rubrique</label>\n";
							$color_champ['rubrique'] = " class=\"color_champ\"";
						}
						elseif(empty($_POST['id_langue']))
						{  
							$avertissement = "<label id=\"avertissement\">Sélectionnez la langue concernée</label>\n";
							$color_champ['id_langue'] = " class=\"color_champ\"";
						}          
						else
						{
							$sql = $connexion->prepare("SELECT count(rubrique) FROM rubriques WHERE rubrique='" . $_POST['rubrique'] . "' AND id_langue='" . $_POST['id_langue'] . "'");	//on teste si la rubrique existe deja dans cette langue
							$sql->execute();
							$nb_rubriques = $sql->fetchColumn();
							if($nb_rubriques != 0) $avertissement="<label id=\"avertissement\">La rubrique <strong>'" . $_POST['rubrique'] . "'</strong> existe déjà dans cette langue</label>\n";                      
							else
							{
								$sql = $connexion->prepare("SELECT count(*) FROM rubriques WHERE id_langue='" . $_POST['id_langue'] . "'");
								$sql->execute();
								$nb_rang = $sql->fetchColumn();
								$rang = $nb_rang + 1;

								$resultat_insert_rubrique = $connexion->exec("INSERT INTO rubriques SET rubrique='" . addslashes($_POST['rubrique']) . "', id_langue='" . $_POST['id_langue'] . "', rang='" . $rang . "'");
								$_SESSION['avertissement'] = "<label id=\"ok\">Rubrique " . addslashes($_POST['rubrique']) . " créée</label>\n";
								header("Location:admin.php?action=rubriques");
								foreach($_POST as $nom_champ=>$valeur)	// permet de vider l'intégralité des champs du formulaire
								{
									$_POST[$nom_champ]="";
								}
							}      
						}    
					}
				
					$requete_rubrique = "SELECT r.*,l.* FROM rubriques r,langues l WHERE r.id_langue=l.id_langue ORDER BY l.pays, r.rang";	// affichage des rubriques présentes dans la table
					$affichage = afficher_rubriques($requete_rubrique, $connexion);
					$affichage_centre = $affichage;

				break;
/*************************

	cas supprimer rubriques
	
***********************/
				case "supprimer_rubriques":
				
					$contenu = "form_rubriques.html";  //toujours afficher formulaire rubriques 
					$titre = "Gestion des rubriques";
					$confirm_suppression = "confirm_suppression.html";
					$action_suppression_oui = "supprimer_rubriques&amp;cas=2";
					$action_suppression_non = "rubriques";
					$action_form = "rubriques";
					$bouton_form = "CREER";
					$invisible = "style=\"display:none\"";

					$sql = $connexion->query("SELECT * FROM rubriques WHERE id_rubrique='" . $_GET['id_rubrique'] . "'");
					$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);				
					$ligne = $sql->fetch();
					$precision_suppression = "de la rubrique " . $ligne->rubrique . " ";

					$sql = $connexion->query("SELECT * FROM langues ORDER BY pays");	// on créé la liste déroulante dynamique des langues
					$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
					$ld_langues = "<option value=\"\">Langues</option>\n";
					while($ligne_langues = $sql->fetch())
					{
						$ld_langues .= "<option value=\"" . $ligne_langues->id_langue . "\">" . $ligne_langues->pays . " (" . $ligne_langues->symbole . ")</option>\n";
					}

					if(isset($_GET['id_rubrique']))   $_SESSION['id_rubrique'] = $_GET['id_rubrique'];
					if(isset($_GET['cas']) && $_GET['cas'] == 2) // si on a cliqué sur OUI
					{
						$invisible = "";	// permet de réafficher le formulaire
						$resultat_suppr_rubriques = $connexion->exec("DELETE FROM rubriques WHERE id_rubrique='" . $_SESSION['id_rubrique']. "'");
						$confirm_suppression = "vide.html";        

						$sql = $connexion->query("SELECT * FROM rubriques ORDER BY rang");
						$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
						$i = 1;
						/***************
						à améliorer
						***************/
						while($ligne_rubriques = $sql->fetch())
						{
							$requete_maj_rubriques = $connexion->exec("UPDATE rubriques SET rang='" . $i . "' WHERE id_rubrique='" . $ligne_rubriques->id_rubrique . "'");
							$i++;
						}
						$_SESSION['avertissement']="<label id=\"ok\">Rubrique supprimée</label>\n";      
						header("Location:admin.php?action=rubriques");
					}                   
				
					$requete_affichage_rubriques = "SELECT r.*,l.* FROM rubriques r,langues l WHERE r.id_langue=l.id_langue ORDER BY l.pays, r.rang";	// affichage des rubriques présentes dans la table
					$affichage = afficher_rubriques($requete_affichage_rubriques, $connexion);
					$affichage_centre = $affichage; 

				break;
/*************************

	cas modifier rubriques
	
***********************/
				case "modifier_rubriques":
				
					$contenu = "form_rubriques.html";  //toujours afficher formulaire rubriques 
					$titre = "Gestion des rubriques";
					$confirm_suppression = "vide.html";
					$action_form = "modifier_rubriques";
					$bouton_form = "MODIFIER";
					if(isset($_POST['id_langue'])) $selected[$_POST['id_langue']]=" selected=\"selected\"";        

					if(isset($_GET['id_rubrique']))	//on recharge le formulaire avec les valeurs stockées en base
					{
						$sql = $connexion->query("SELECT * FROM rubriques WHERE id_rubrique='" . $_GET['id_rubrique'] . "'");
						$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
						$ligne_rubriques = $sql->fetch();  //pas besoin d'un while car 1 seul resultat
						$_POST['rubrique'] = $ligne_rubriques->rubrique;  
						$_POST['id_langue'] = $ligne_rubriques->id_langue;      
						$selected[$ligne_rubriques->id_langue] = " selected=\"selected\"";
						$_SESSION['id_rubrique'] = $ligne_rubriques->id_rubrique;
					}      
				
					$sql = $connexion->query("SELECT * FROM langues ORDER BY pays");  	// on créé la liste déroulante dynamique des langues
					$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
					$ld_langues = "<option value=\"\"></option>\n";
					while($ligne_langues = $sql->fetch())
					{
						if(isset($_POST['id_langue']) && $ligne_langues->id_langue == $_POST['id_langue']) $select=$selected[$ligne_langues->id_langue];
						else $select="";

						$ld_langues .= "<option value=\"" . $ligne_langues->id_langue . "\"$select>" . $ligne_langues->pays . " (" . $ligne_langues->symbole . ")</option>\n";
					}          

					if(isset($_POST['submit']))	//si qq'1 a appuyé sur le bouton modifier
					{
						if(empty($_POST['rubrique'])) $avertissement = "<label id=\"avertissement\">Vous devez saisir une rubrique</label>\n";
						else
						{
							$resultat_maj_rubriques = $connexion->exec("UPDATE rubriques SET rubrique='" . $_POST['rubrique'] . "', id_langue='" . $_POST['id_langue'] . "' WHERE id_rubrique='" . $_SESSION['id_rubrique'] . "'");
							$_SESSION['avertissement'] = "<label id=\"ok\">Modification effectuée</label>\n";
						} 
						header("Location:admin.php?action=rubriques");  
					}        
				
					$requete_rubriques = "SELECT r.*,l.* FROM rubriques r,langues l WHERE r.id_langue=l.id_langue ORDER BY l.pays, r.rang";	// affichage des rubriques présentes dans la table
					$affichage = afficher_rubriques($requete_rubriques, $connexion);
					$affichage_centre = $affichage; 

				break;
/*************************

	cas pages
	
***********************/
				case "pages":

					$titre = "Gestion des pages";
					$contenu = "form_pages.html";
					$action = "pages";
					$bouton = "CRÉER";
					$abort = $action; 
					$checked = " checked=\"checked\"";
					$script = "<script type=\"text/javascript\" src=\"../editeur/editeur.js\"></script>\n";
					$css = "<link href=\"../editeur/editeur.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
				
					$sql =  $connexion->query("SELECT * FROM langues ORDER BY symbole");	//on construit la liste déroulante des langues
					$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
					$langues = "";
					while($ligne_langues = $sql->fetch())
					{
						if(isset($_POST['id_langue']) && $ligne_langues->id_langue  ==  $_POST['id_langue'])
						{
							$selected[$_POST['id_langue']] = " selected=\"selected\"";
							$selection = $selected[$ligne_langues->id_langue];    
						}                  
						else $selection = "";

						$langues .= "<option value=\"" . $ligne_langues->id_langue . "\"" . $selection . ">" . $ligne_langues->symbole . " - " . $ligne_langues->pays . "</option>\n";          
					}

					if(isset($_SESSION['id_page'])) unset($_SESSION['id_page']);

					if(isset($_POST['submit']))
					{
						$check[$_POST['visible']] = $checked;            
						$check2[$_POST['indexation']] = $checked; 
						$check3[$_POST['keyword']] = $checked;  

						$tab_champ = array("id_langue"=>"Langue", "id_rubrique"=>"Rubrique", "titre_page"=>"Titre de page", "titre_google"=>"Titre Google", "meta"=>"Meta description");
						$tab_vide = array();  

						while(list($key, $value) = each($tab_champ)) 
						{            
							if(empty($_POST[$key]))
							{
								$color_champ[$key] = " class=\"color_champ\"";
								array_push($tab_vide, $value);
							}
						}        

						if(!empty($tab_vide)) $avertissement="<p id=\"avertissement\">" . champs(count($tab_vide), "debut") . implode(", ", $tab_vide) . champs(count($tab_vide), "fin") ."</p>\n";              
						else
						{
							if(empty($_POST['url_rewriting'])) $_POST['url_rewriting']=$_POST['titre_page'];

							$sql = $connexion->prepare("SELECT count(*) FROM pages WHERE url_rewriting='" . $_POST['url_rewriting'] . "'");	//pour éviter d'utiliser la clé primaire de la table page, on teste que le champ url_rewriting n'existe pas déjà
							$sql->execute();
							$nb_pages = $sql->fetchColumn();
							if($nb_pages > 0)
							{
								$avertissement = "<label id=\"avertissement\">L'URL choisie existe déjà</label>\n";
								$color_champ['url_rewriting'] = " class=\"color_champ\"";
							} 
							else
							{
								$tab_zone = array($_POST['zone1'],$_POST['zone2'],$_POST['zone3']);	// on créé un tableau qui stocke le nb de zone remplie
								/******************************
								détection des médias insérés
								******************************/
								$regex = '/\[([^\]]*)\]/';// expression régulière permettant de récupérer tout ce qui se trouve entre les crochets 
								$k = 1;// compteur des zones
								for($i = 0;$i<sizeof($tab_zone);$i++)
								{
									if($tab_zone[$i] != "")
									{
										preg_match_all($regex, $tab_zone[$i], $tab_medias);	// on recherche toutes les expressions entre crochets
										if(sizeof($tab_medias[1]) > 0)	// si il y a des médias présents dans la zone
										{
											for($j = 0;$j<sizeof($tab_medias[1]);$j++) //[1] pour le résultat sans le séparateur [] et [0] avec séparateur
											{
												$sql = $connexion->query("SELECT * FROM medias WHERE id_media='" . $tab_medias[1][$j] . "'");	// pour chaque media retrouvé entre les crochets
												$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
												$ligne_medias = $sql->fetch();
												if($ligne_medias->fichier_media != "") $new_media[$j]="<img id=\"im" . $ligne_medias->id_media . "\" src=\"../img/medias/" . $ligne_medias->titre_media . "." . $ligne_medias->fichier_media . "\" alt=\"" . $ligne_medias->alt_media . "\" />";
												if($ligne_medias->lien_media != "") $new_media[$j]=$ligne_medias->lien_media;
											}
											$tab_zone_remplace = str_replace($tab_medias[0],$new_media,$tab_zone[$i]);	// pour chaque média trouvé entre crochet on remplace le raccourci par le code html du média 
											$zone[$k] = $tab_zone_remplace;                          
										}          
										else $zone[$k]=$tab_zone[$i];
										
										$k++; 
									}
								}            

								$separateur = "-";                            
								$requete_insert_page = "INSERT INTO pages SET 
								titre_page='" . addslashes($_POST['titre_page']) . "',
								titre_google='" . addslashes($_POST['titre_google']) . "',
								meta='" . addslashes($_POST['meta']) . "', 
								id_rubrique='" . $_POST['id_rubrique'] . "',
								visible='" . $_POST['visible'] . "', 
								indexation='" . $_POST['indexation'] . "',
								keyword='" . $_POST['keyword'] . "',
								liste_mots='" . addslashes($_POST['liste_mots']) . "',
								zone1='" . addslashes($zone[1]) . "', 
								zone2='" . addslashes($zone[2]) . "', 
								zone3='" . addslashes($zone[3]) . "', 
								id_compte='" . $_SESSION['id_acces'] . "', 
								date_page='" . date('Y-m-d') . "', 
								id_gabarit='" . $_POST['gabarit'] . "'";
								$resultat_insert_page = $connexion->exec($requete_insert_page);
								$id_page = $connexion->lastInsertId();

								$resultat_maj_pages = $connexion->exec("UPDATE pages SET url_rewriting='" . format_url($_POST['url_rewriting'], $separateur, $id_page) . "' WHERE id_page='" . $id_page . "'");	//on met à jour la table pages avec l'url réécrite

								$_SESSION['avertissement'] = "<p id=\"ok\">Création de page effectuée.</p>\n";
								header("Location:admin.php?action=pages");
							}      
						}
					}
					else
					{
						$check['non'] = $checked;
						$check2['non'] = $checked;
						$check3['non'] = $checked;
					}      

					$requete_pages = "SELECT p.*, r.* FROM pages p LEFT JOIN rubriques r ON p.id_rubrique=r.id_rubrique ORDER BY r.rang, p.rang"; 
					$affichage = afficher_pages($requete_pages, $connexion);

					$requete_medias = "SELECT * FROM medias ORDER BY  type_media, fichier_media, id_media";	// toujours affichage des médias présents dans la table
					$affichage_centre = afficher_medias($requete_medias, "centre", $connexion);          

				break;
/*************************

	cas modifier pages
	
***********************/
				case "modifier_pages":

					$titre = "Gestion des pages";
					$contenu = "form_pages.html";
					$action = "modifier_pages";
					$bouton = "MODIFIER"; 
					$abort = "pages";
					$script = "<script type=\"text/javascript\" src=\"../editeur/editeur.js\"></script>\n";
					$css = "<link href=\"../editeur/editeur.css\" rel=\"stylesheet\" type=\"text/css\" />\n";

					if(isset($_GET['id_page']))
					{
						$sql = $connexion->query("SELECT p.*, r.* FROM pages p LEFT JOIN rubriques r ON p.id_rubrique=r.id_rubrique AND p.id_page='" . $_GET['id_page'] . "'");
						$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
						$ligne_pages = $sql->fetch();
						$selected[$ligne_pages->id_langue] = " selected = \"selected\"";                 
						$_SESSION['id_rubrique'] = $ligne_pages->id_rubrique;
						$_POST['titre_page'] = $ligne_pages->titre_page;
						$_POST['titre_google'] = $ligne_pages->titre_google;
						$_POST['meta'] = $ligne_pages->meta;
						$_POST['zone1'] = $ligne_pages->zone1;
						$_POST['zone2'] = $ligne_pages->zone2;
						$_POST['zone3'] = $ligne_pages->zone3;
						$_POST['gabarit'] = $ligne_pages->id_gabarit;
						$_POST['liste_mots'] = $ligne_pages->liste_mots;
						//on recharge l'URL réécrite sans le id_page 
						$tab_url = explode("-",$ligne_pages->url_rewriting);
						for($i = 0;$i<(sizeof($tab_url)-1);$i++)
						{
							if($i == 0) $_POST['url_rewriting'] = $tab_url[$i] . "-";

							$_POST['url_rewriting'] .= $tab_url[$i];
						}        
						$check[$ligne_pages->visible] = " checked=\"checked\"";
						$check2[$ligne_pages->indexation] = " checked=\"checked\"";
						$check3[$ligne_pages->keyword] = " checked=\"checked\"";        
						$_SESSION['id_page'] = $_GET['id_page'];
					}

					if(isset($_POST['submit']))
					{
						$check[$_POST['visible']] = " checked=\"checked\"";
						$check2[$_POST['indexation']] = " checked=\"checked\"";
						$check3[$_POST['keyword']] = " checked=\"checked\"";

						$tab_champ = array("id_langue"=>"Langue", "id_rubrique"=>"Rubrique", "titre_page"=>"Titre de page", "titre_google"=>"Titre Google", "meta"=>"Meta description");
						$tab_vide = array();  

						while(list($key, $value) = each($tab_champ)) 
						{
							if(empty($_POST[$key]))
							{
								$color_champ[$key] = " class=\"color_champ\"";
								array_push($tab_vide, $value);
							}
						}        

						if(!empty($tab_vide)) $avertissement = "<p id=\"avertissement\">" . champs(count($tab_vide), "debut") . implode(", ", $tab_vide) . champs(count($tab_vide), "fin") ."</p>\n";              
						else
						{
							if(empty($_POST['url_rewriting'])) $_POST['url_rewriting'] = $_POST['titre_page'];

							$tab_zone = array($_POST['zone1'],$_POST['zone2'],$_POST['zone3']);	// on créé un tableau qui stocke le nb de zone remplie
							/******************************
							détection des médias insérés
							******************************/
							$regex =  '/\[([^\]]*)\]/';// expression régulière permettant de récupérer tout ce qui se trouve entre les crochets 
							$k = 1;// compteur des zones
							for($i = 0;$i<sizeof($tab_zone);$i++) // car 3 zones
							{
								if($tab_zone[$i] != "")
								{
									preg_match_all($regex, $tab_zone[$i], $tab_medias);	// on recherche toutes les expressions entre crochets
									if(sizeof($tab_medias[1]) > 0)	// si il y a des médias présents dans la zone
									{
										for($j = 0; $j<sizeof($tab_medias[1]); $j++) //[1] pour le résultat sans le séparateur [] et [0] avec séparateur
										{
											$sql = $connexion->query("SELECT * FROM medias WHERE id_media='" . $tab_medias[1][$j] . "'");	// pour chaque media retrouvé entre les crochets
											$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
											$ligne_medias = $sql->fetch();
											
											if($ligne_medias->fichier_media != "") $new_media[$j] = "<img id=\"im" . $ligne_medias->id_media . "\" src=\"../img/medias/" . $ligne_medias->titre_media . "." . $ligne_medias->fichier_media . "\" alt=\"" . $ligne_medias->alt_media . "\" />";
											if($ligne_medias->lien_media != "") $new_media[$j] = $ligne_medias->lien_media;
										}
										$tab_zone_remplace = str_replace($tab_medias[0],$new_media,$tab_zone[$i]);	// pour chaque média trouvé entre crochet on remplace le raccourci par le code html du média     
										$zone[$k] = $tab_zone_remplace;                          
									}          
									else $zone[$k] = $tab_zone[$i]; 

									$k++;  
								}
							}
							$separateur = "-";

							$requete_maj_pages = "UPDATE pages SET 
							titre_page='" . addslashes($_POST['titre_page']) . "',
							titre_google='" . addslashes($_POST['titre_google']) . "', 
							meta='" . addslashes($_POST['meta']) . "',         
							id_rubrique='" . $_POST['id_rubrique'] . "',
							visible='" . $_POST['visible'] . "', 
							indexation='" . $_POST['indexation'] . "',
							keyword='" . $_POST['keyword'] . "',
							liste_mots='" . addslashes($_POST['liste_mots']) . "',
							zone1='" . addslashes($zone[1]) . "', 
							zone2='" . addslashes($zone[2]) . "', 
							zone3='" . addslashes($zone[3]) . "', 
							id_compte='" . $_SESSION['id_acces'] . "',  
							id_gabarit='" . $_POST['gabarit'] . "', 
							url_rewriting='" . format_url($_POST['url_rewriting'],$separateur, $_SESSION['id_page']) . "' 
							WHERE id_page='" . $_SESSION['id_page'] . "'"; 
							$resultat_maj_pages = $connexion->exec($requete_maj_pages);  
							$_SESSION['avertissement']="<p id=\"ok\">Modification de page effectuée.</p>\n";
							header("Location:admin.php?action=pages");      
						}
					}  

					
					$sql = $connexion->query("SELECT * FROM langues ORDER BY symbole");//on construit la liste déroulante des langues
					$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
					$langues = "";
					while($ligne_langues = $sql->fetch())
					{
						if($ligne_langues->id_langue  ==  $ligne_pages->id_langue) $selection=$selected[$ligne_langues->id_langue];   
						else $selection="";
						
						$langues .= "<option value=\"" . $ligne_langues->id_langue . "\"" . $selection . ">" . $ligne_langues->symbole . " - " . $ligne_langues->pays . "</option>\n";          
					}                     

					$requete_affichage_pages = "SELECT p.*, r.* FROM pages p LEFT JOIN rubriques r ON p.id_rubrique=r.id_rubrique ORDER BY r.rang, p.rang"; 
					$affichage = afficher_pages($requete_affichage_pages, $connexion);

				
					$requete_affichage_medias = "SELECT * FROM medias ORDER BY  type_media, fichier_media, id_media";	// on affiche les médias présents dans la table
					$affichage_centre = afficher_medias($requete_affichage_medias, "centre", $connexion); 

				break;
/*************************

	cas supprimer pages
	
***********************/
				case "supprimer_pages":             

					$contenu = "form_pages.html";  
					$titre = "Gestion des pages";
					$confirm_suppression = "confirm_suppression.html";
					$action_suppression_oui = "supprimer_pages&cas=2";
					$action_suppression_non = "pages";     
					$action = "pages";
					$bouton = "CRÉER";
					$invisible = "style=\"display:none\"";          
					$abort = $action_suppression_non;  

					if(isset($_GET['id_page']))   $_SESSION['id_page'] = $_GET['id_page'];
					
					if(isset($_SESSION['id_page']) && isset($_GET['cas']))
					{   
						$resultat_suppr_pages = $connexion->exec("DELETE FROM pages WHERE id_page='" . $_SESSION['id_page']. "'"); 
						$_SESSION['avertissement'] = "<p id=\"ok\">Suppression de page effectuée.</p>\n";
						header("Location:admin.php?action=pages");             
					}      

					$requete_affichage_pages = "SELECT p.*, r.* FROM  pages p, rubriques r WHERE p.id_rubrique=r.id_rubrique ORDER BY p.id_page"; 
					$affichage = afficher_pages($requete_affichage_pages, $connexion);

					$requete_affichage_medias = "SELECT * FROM medias ORDER BY  type_media, fichier_media, id_media";	// on affiche toujours les medias présents dans la table
					$affichage_centre = afficher_medias($requete_affichage_medias, "centre", $connexion); 

				break;
/*************************

	cas medias
	
***********************/
				case "medias":
					
					$contenu = "form_medias.html";
					$titre = "Gestion des médias";
					$confirm_suppression = "vide.html";
					$action_form = "medias";
					$bouton_form = "CREER";     
					unset($_SESSION['id_media']);	//on efface la variable $_SESSION id_media

					if(isset($_POST['slide']) && $_POST['slide'] != "")	//permet de conserver les cases cochées
					{
						$_POST['slide'] = "oui";
						$checked['slide'] = " checked=\"checked\"";
					}                     
					else $_POST['slide'] = "non";

					if(isset($_POST['press_book']) && $_POST['press_book'] != "")
					{
						$_POST['press_book']="oui";
						$checked['press_book']=" checked=\"checked\"";
					}
					else $_POST['press_book'] = "non";
/*************************
	à la création du media
***********************/
					if(isset($_POST['submit']))
					{
/*************************
	1 : il s'agit d'une url de video
***********************/
							if($_FILES['fichier_media']['name'] == "")
							{
								if($_POST['lien_media'] == "")
								{
									$avertissement = "<label id=\"avertissement\">Veuillez sélectionner un fichier ou entrer une url de video</label>\n";
									header("Location:admin.php?action=medias");
								}
								
								$requete_insert_medias = "INSERT INTO medias SET  
								titre_media='" . remplacer_espaces(addslashes($_POST['titre_media'])) . "',
								alt_media='" . addslashes($_POST['alt_media']) . "',
								lien_media='" . addslashes($_POST['lien_media']) . "',
								type_media='4',
								slide='non',
								press_book='non'";
								$resultat = $connexion->exec($requete_insert_medias);
								$avertissement = "<label id=\"ok\">La video a bien étéenregistrée</label>\n";
								header("Location:admin.php?action=medias");  
							}
/*************************
	2 : il s'agit d'un fichier
***********************/
						else
						{
							$allowedExts = array("jpg", "jpeg", "gif", "png", "pdf", "mp3", "mp4", "wma");
							$fileName = $_FILES["fichier_media"]['name'];
							$extension = substr($fileName, strrpos($fileName, '.') + 1); // getting the info about the image to get its extension
							
							if($extension == "jpg" || $extension == "jpeg" || $extension == "gif" || $extension == "png") $type_media = '1';
							elseif($extension == "pdf") $type_media = '2';
							else $type_media = '3';

							if(in_array($extension, $allowedExts))
							{
								if ($_FILES["fichier_media"]["error"] > 0)
								{
									echo "Return Code: " . $_FILES["fichier_media"]["error"] . "<br />";
								}
								else
								{
									$requete_insert_media = "INSERT INTO medias SET  
									titre_media='" . remplacer_espaces(addslashes($_POST['titre_media'])) . "',
									alt_media='" . addslashes($_POST['alt_media']) . "',
									lien_media='',
									fichier_media='" . $extension . "',
									type_media='" . $type_media . "'";
									if($type_media == '1')
									{
										$requete_insert_media .= ",
										slide='" . $_POST['slide'] . "',
										press_book='" . $_POST['press_book'] . "'";
									}
									else
									{
										$requete_insert_media .= ",
										slide='non',
										press_book='non'";
									}
									$resultat_insert_media = $connexion->exec($requete_insert_media);
									
									$chemin_media = "../img/medias/" . remplacer_espaces(addslashes($_POST['titre_media'])) . "." . $extension;

									echo "Upload: " . $_FILES["fichier_media"]["name"] . "<br />";
									echo "Type: " . $_FILES["fichier_media"]["type"] . "<br />";
									echo "Size: " . ($_FILES["fichier_media"]["size"] / 1024) . " Kb<br />";
									echo "Temp file: " . $_FILES["fichier_media"]["tmp_name"] . "<br />";

									if(move_uploaded_file($_FILES["fichier_media"]["tmp_name"], $chemin_media))
									{
										$avertissement = "<label id=\"ok\">Fichier uploadé</label>\n";
										header("Location:admin.php?action=medias");
									}
								}
							}
							else
							{
								$avertissement = "<label id=\"avertissement\">fichier invalide</label>\n";
							}
						}
					}
					$requete_affichage_medias = "SELECT * FROM medias ORDER BY type_media, fichier_media, id_media";	// on affiche toujours les medias présents dans la table
					$affichage_centre = afficher_medias($requete_affichage_medias, "centre", $connexion);  
					$affichage = afficher_medias($requete_affichage_medias, "droite", $connexion);

				break;
/*************************

	cas supprimer medias
	
***********************/
				case "supprimer_medias":
				
					$contenu = "form_medias.html";  //toujours afficher formulaire rubriques 
					$titre = "Gestion des médias";
					$confirm_suppression = "confirm_suppression.html";
					$action_suppression_oui = "supprimer_medias&amp;cas=2&amp;extension=" . $_GET['extension'];
					$action_suppression_non = "medias";
					$action_form = "medias";
					$bouton_form = "CREER";
					$invisible = "style=\"display:none\"";

					if(isset($_GET['id_media'])) $_SESSION['id_media'] = $_GET['id_media'];         
					if(isset($_GET['cas']) && $_GET['cas'] == 2)   // si on a cliqué sur OUI
					{
						$invisible = "";	//permet de réafficher le formulaire de saisie
						$requete = "SELECT titre_media FROM medias WHERE id_media = " . $_SESSION['id_media'] . "";
						$sql = $connexion->query($requete);
						$sql->setFetchMode(PDO::FETCH_ASSOC);
						$ligne = $sql->fetch();
						
						if(isset($_GET['extension']) && $_GET['extension'] != "non") @unlink("../img/medias/" . $ligne['titre_media'] . "." . $_GET['extension']);	//on supprime le fichier media
						
						$resultat_suppr_media = $connexion->exec("DELETE FROM medias WHERE id_media='" . $_SESSION['id_media']. "'");	// on supprime la ligne dans la table
						$confirm_suppression = "vide.html";
						$_SESSION['avertissement'] = "<label id=\"ok\">Média supprimé</label>\n";
						unset($_SESSION['id_media']);	//on efface les variables $_SESSION id_media et $_SESSION extension
						unset($_SESSION['extension']);
						header("Location:admin.php?action=medias");    
					}         
					$requete_affichage_medias = "SELECT * FROM medias ORDER BY  type_media, fichier_media, id_media";	// on affiche toujours les medias présents dans la table
					$affichage_centre = afficher_medias($requete_affichage_medias, "centre", $connexion);  
					$affichage = afficher_medias($requete_affichage_medias, "droite", $connexion);

				break;       
/*************************

	cas modifier medias
	
***********************/
				case "modifier_medias":
					
					$contenu = "form_medias.html";
					$titre = "Gestion des médias";
					$confirm_suppression = "vide.html";
					$action_form = "modifier_medias";
					$bouton_form = "MODIFIER";                

/*************************
	préparation du formulaire
***********************/
					if(isset($_GET['id_media']))
					{
						$sql = $connexion->query("SELECT * FROM medias WHERE id_media='" . $_GET['id_media'] . "'");
						$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
						$ligne_medias = $sql->fetch();
						$_POST['titre_media'] = $ligne_medias->titre_media;
						$_SESSION['titre_media'] = $ligne_medias->titre_media;
						$_POST['alt_media'] = $ligne_medias->alt_media;
						$_POST['lien_media'] = $ligne_medias->lien_media;       
						$_SESSION['id_media'] = $_GET['id_media'];
						$_POST['slide'] = $ligne_medias->slide;
						$_POST['press_book'] = $ligne_medias->press_book; 
						$_SESSION['fichier_media'] = $ligne_medias->fichier_media; 	//on va stocker en session l'extension du fichier média pour pouvoir le détruire si besoin
					}

					if(isset($_POST['slide']) && $_POST['slide'] == "oui") $checked['slide']=" checked=\"checked\"";
					else $_POST['slide'] = "non";
					
					if(isset($_POST['press_book']) && $_POST['press_book'] == "oui")$checked['press_book']=" checked=\"checked\"";
					else$_POST['press_book'] = "non";
/*************************
	lors de la demande de modification
***********************/
					if(isset($_POST['submit']))
					{
/*************************
	on vérifie que les champs nécessaires sont bien remplis
***********************/
						if(empty($_POST['titre_media']))
						{  
							$avertissement = "<label id=\"avertissement\">Saisissez un titre pour ce média</label>\n";
							$color_champ['titre_media'] = " class=\"color_champ\"";
						} 
						elseif(empty($_POST['alt_media']))
						{
							$avertissement = "<label id=\"avertissement\">Saisissez une légende pour ce media</label>\n";
							$color_champ['alt_media'] = " class=\"color_champ\"";        
						}
						elseif($_FILES['fichier_media']['name'] != "")
						{
							$allowedExts = array("jpg", "jpeg", "gif", "png", "pdf", "mp3", "mp4", "wma");
							$fileName = $_FILES["fichier_media"]['name'];
							$extension = substr($fileName, strrpos($fileName, '.') + 1); // getting the info about the image to get its extension
							
							if($extension == "jpg" || $extension == "jpeg" || $extension == "gif" || $extension == "png") $type_media = '1';
							elseif($extension == "pdf") $type_media = '2';
							else $type_media = '3';

							if(in_array($extension, $allowedExts))
							{
/*************************
	1 : un fichier est uploadé
***********************/
								if ($_FILES["fichier_media"]["error"] > 0)
								{
									echo "Return Code: " . $_FILES["fichier_media"]["error"] . "<br />";
								}
								else
								{
									$requete = "UPDATE medias SET  
									titre_media='" . remplacer_espaces(addslashes($_POST['titre_media'])) . "',
									alt_media='" . addslashes($_POST['alt_media']) . "',
									lien_media='" . addslashes($_POST['lien_media']) . "',
									fichier_media='" . $extension . "',
									type_media='" . $type_media . "'";
									
									if($type_media == '1')
									{
										$requete_insert_media .= ",
										slide='" . $_POST['slide'] . "',
										press_book='" . $_POST['press_book'] . "'";
									}
									else
									{
										$requete_insert_media .= ",
										slide='non',
										press_book='non'";
									}
									$requete .= "WHERE id_media = '" . $_SESSION['id_media'] . "'";
									$sql = $connexion->exec($requete);
									
									$chemin_media = "../img/medias/" . remplacer_espaces(addslashes($_POST['titre_media'])) . "." . $extension;

									echo "Upload: " . $_FILES["fichier_media"]["name"] . "<br />";
									echo "Type: " . $_FILES["fichier_media"]["type"] . "<br />";
									echo "Size: " . ($_FILES["fichier_media"]["size"] / 1024) . " Kb<br />";
									echo "Temp file: " . $_FILES["fichier_media"]["tmp_name"] . "<br />";

									if(move_uploaded_file($_FILES["fichier_media"]["tmp_name"], $chemin_media))
									{
										if($extension != $_SESSION['fichier_media'])
										{
											$fichier_a_detruire = "../img/medias/" . $_SESSION['titre_media'] . "." . $_SESSION['fichier_media'];
											unlink($fichier_a_detruire);
										}
										$avertissement = "<label id=\"ok\">Modification effectuée</label>\n";
										header("Location:admin.php?action=medias");
									}
								}
							}
							else
							{
								$avertissement = "<label id=\"avertissement\">Fichier invalide</label>\n";
							}
						}
						else
						{
/*************************
	2 : Si l'on n'a pas fourni de fichier, on vérifie s'il s'agit d'une video ou d'une modification sans changement de fichier
***********************/
							if($_FILES['fichier_media']['name'] == "")
							{
/*************************
	2.1 : pas d'url : il s'agit de renommer le media existant
***********************/
								if($_POST['lien_media'] == "")
								{
									$ancien_media = "../img/medias/" . $_SESSION['titre_media'] . "." . $_SESSION['fichier_media'];
									$nouveau_media = "../img/medias/" . remplacer_espaces(addslashes($_POST['titre_media'])) . "." . $_SESSION['fichier_media'];
									rename($ancien_media, $nouveau_media);
									
									$requete = "UPDATE medias SET  
									titre_media='" . remplacer_espaces(addslashes($_POST['titre_media'])) . "',
									alt_media='" . addslashes($_POST['alt_media']) . "',
									slide='" . $_POST['slide'] . "',
									press_book='" . $_POST['press_book'] . "'
									WHERE id_media = '" . $_SESSION['id_media'] . "'";
									$resultat = $connexion->exec($requete);
									
									$avertissement = "<label id=\"ok\">Modification effectuée</label>\n";
								}
/*************************
	2.2 : on fournit une url : on passe d'un fichier à un lien ou on modifie un lien existant
***********************/
								else
								{
									if($_SESSION['fichier_media'] != "")
									{
										$fichier_a_detruire = "../img/medias/" . $_SESSION['titre_media'] . "." . $_SESSION['fichier_media'];
										unlink($fichier_a_detruire);
									}
									$requete = "UPDATE medias SET  
									titre_media='" . remplacer_espaces(addslashes($_POST['titre_media'])) . "',
									alt_media='" . addslashes($_POST['alt_media']) . "',
									lien_media='" . addslashes($_POST['lien_media']) . "',
									fichier_media='',
									type_media='4', 
									slide='" . $_POST['slide'] . "',
									press_book='" . $_POST['press_book'] . "'
									WHERE id_media = '" . $_SESSION['id_media'] . "'";
									$resultat = $connexion->exec($requete);
									
									$avertissement = "<label id=\"ok\">Modification effectuée</label>\n";
								}
							}               
						}
						foreach($_POST as $nom_champ=>$valeur)	//on efface le contenu des champs
						{
							$_POST[$nom_champ]="";
						}
						header("Location:admin.php?action=medias");
					}

					$requete = "SELECT * FROM medias ORDER BY  type_media, fichier_media, id_media";	// on affiche toujours les medias présents dans la table
					$affichage_centre = afficher_medias($requete, "centre", $connexion);  
					$affichage = afficher_medias($requete, "droite", $connexion);
					
				break;
/*************************

	cas actus
	
***********************/
				case "actus":
				
					$contenu = "form_actus.html";
					$titre = "Gestion des actus";
					$confirm_suppression = "vide.html";
					$action_form = "actus";
					$bouton_form = "CREER";
					$checked['non'] = " checked=\"checked\"";  
					$script = "<script type=\"text/javascript\" src=\"../editeur/editeur.js\"></script>\n";
					$css = "<link href=\"../editeur/editeur.css\" rel=\"stylesheet\" type=\"text/css\" />\n";

					$sql = $connexion->query("SELECT * FROM langues ORDER BY pays");	// on créé la liste déroulante dynamique des langues
					$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
					$ld_langues = "<option value=\"\">Langues</option>\n";
					while($ligne_langues = $sql->fetch())
					{
						$ld_langues .= "<option value=\"" . $ligne_langues->id_langue . "\">" . $ligne_langues->pays . " (" . $ligne_langues->symbole . ")</option>\n";
					}      

					if(isset($_SESSION['id_actu'])) unset($_SESSION['id_actu']); 

					if(isset($_POST['submit']))
					{
						$checked[$_POST['rss']] = " checked=\"checked\"";  
						$tab_champ = array("titre_actu"=>"Titre de l'actu", "id_langue"=>"Langue");	//liste des champs obligatoires
						$tab_vide = array();  
						while(list($key, $value) = each($tab_champ)) 
						{
							if(empty($_POST[$key]))
							{
								$color_champ[$key] = " class=\"color_champ\"";
								array_push($tab_vide, $value);
							}
						}        

						if(!empty($tab_vide)) $avertissement="<label id=\"avertissement\">" . champs(count($tab_vide), "debut") . implode(", ", $tab_vide) . champs(count($tab_vide), "fin") ."</label>\n";              
						else
						{         
							$requete_insert_actus = "INSERT INTO actus SET titre_actu='" . addslashes($_POST['titre_actu']) . "',
							contenu_actu='" . addslashes($_POST['contenu_actu']) . "',
							date_debut_actu='" . addslashes($_POST['date_debut_actu']) . "',
							date_fin_actu='" . addslashes($_POST['date_fin_actu']) . "',
							rss='" . $_POST['rss'] . "',
							date_creation_actu='" . date("Y-m-d H:i:s") . "',
							id_langue='" . $_POST['id_langue'] . "'";
							$resultat = $connexion->exec($requete_insert_actus);
							$avertissement = "<label id=\"ok\">Actu créée</label>\n";

							foreach($_POST as $nom_champ=>$valeur)	// permet de vider l'intégralité des champs du formulaire
							{
								$_POST[$nom_champ] = "";
							}          
						}
					}
					$requete_affichage_actus = "SELECT a.*,l.* FROM actus a,langues l WHERE a.id_langue=l.id_langue ORDER BY l.pays, a.date_creation_actu";// affichage des actus présentes dans la table  
					$affichage = afficher_actus($requete_affichage_actus, $connexion);

					$requete_affichage_medias = "SELECT * FROM medias ORDER BY  type_media, fichier_media, id_media";	// on affiche toujours les medias présents dans la table
					$affichage_centre = afficher_medias($requete_affichage_medias, "centre", $connexion);       

				break;
/*************************

	cas modifier actus
	
***********************/
				case "modifier_actus":
				
					$contenu = "form_actus.html";  //toujours afficher formulaire actus 
					$titre = "Gestion des actus";
					$confirm_suppression = "vide.html";
					$action_form = "modifier_actus";
					$bouton_form = "MODIFIER";
					$script = "<script type=\"text/javascript\" src=\"../editeur/editeur.js\"></script>\n";
					$css="<link href=\"../editeur/editeur.css\" rel=\"stylesheet\" type=\"text/css\" />\n";      

					if(isset($_POST['id_langue'])) $selected[$_POST['id_langue']]=" selected=\"selected\"";        
					
					if(isset($_GET['id_actu']))//on recharge le formulaire avec les valeurs stockées en base
					{
						$sql = $connexion->query("SELECT * FROM actus WHERE id_actu='" . $_GET['id_actu'] . "'");
						$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
						$ligne_actus = $sql->fetch();  //pas besoin d'un while car 1 seul resultat
						$_POST['titre_actu'] = $ligne_actus->titre_actu;
						$_POST['id_langue'] = $ligne_actus->id_langue;      
						$selected[$ligne_actus->id_langue] = " selected=\"selected\"";
						$_POST['contenu_actu'] = $ligne_actus->contenu_actu; 
						$_POST['date_debut_actu'] = $ligne_actus->date_debut_actu;
						$_POST['date_fin_actu'] = $ligne_actus->date_fin_actu;                        
						$_SESSION['id_actu'] = $ligne_actus->id_actu;
						$checked[$ligne_actus->rss] = " checked=\"checked\"";
					}      
					
					$sql = $connexion->query("SELECT * FROM langues ORDER BY pays");// on créé la liste déroulante dynamique des langues
					$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
					$ld_langues = "<option value=\"\"></option>\n";
					while($ligne_langues = $sql->fetch())
					{
						if($ligne_langues->id_langue == $ligne_actus->id_langue) $select = $selected[$ligne_langues->id_langue];
						else $select = "";

						$ld_langues .= "<option value=\"" . $ligne_langues->id_langue . "\"$select>" . $ligne_langues->pays . " (" . $ligne_langues->symbole . ")</option>\n";
					}          
				
					if(isset($_POST['submit']))	//si qq'1 a appuyé sur le bouton modifier
					{
						$tab_champ = array("titre_actu"=>"Titre de l'actu", "id_langue"=>"Langue", "contenu_actu"=>"Contenu");	//liste des champs obligatoires
						$tab_vide = array();  

						while(list($key, $value) = each($tab_champ)) 
						{
							if(empty($_POST[$key]))
							{
								$color_champ[$key] = " class=\"color_champ\"";
								array_push($tab_vide, $value);
							}
						}        

						if(!empty($tab_vide)) $avertissement="<label id=\"avertissement\">" . champs(count($tab_vide), "debut") . implode(", ", $tab_vide) . champs(count($tab_vide), "fin") ."</label>\n";              
						else
						{  
							$requete_maj_actus = "UPDATE actus SET titre_actu='" . addslashes($_POST['titre_actu']) . "', 
							id_langue='" . $_POST['id_langue'] . "',
							contenu_actu='" . addslashes($_POST['contenu_actu']) . "',                                      
							date_debut_actu='" . $_POST['date_debut_actu'] . "',
							date_fin_actu='" . $_POST['date_fin_actu'] . "',
							rss='" . $_POST['rss'] . "',
							date_creation_actu='" . date("Y-m-d H:i:s") . "'                                                                               
							WHERE id_actu='" . $_SESSION['id_actu'] . "'";
							$resultat_maj_actus = $connexion->exec($requete_maj_actus);
							$_SESSION['avertissement'] = "<label id=\"ok\">Modification effectuée</label>\n";
							header("Location:admin.php?action=actus");
						} 
					}  
					
					$requete_affichage_actus = "SELECT a.*,l.* FROM actus a,langues l WHERE a.id_langue=l.id_langue ORDER BY l.pays, a.date_creation_actu";	// affichage des actus présentes dans la table
					$affichage=afficher_actus($requete_affichage_actus, $connexion); 

					$requete_affichage_medias = "SELECT * FROM medias ORDER BY  type_media, fichier_media, id_media";	// on affiche toujours les medias présents dans la table
					$affichage_centre = afficher_medias($requete_affichage_medias, "centre", $connexion);

				break;
/*************************

	cas suprimer actus
	
***********************/
				case "supprimer_actus":
				
					$contenu = "form_actus.html";  //toujours afficher formulaire actus 
					$titre  = "Gestion des actus";
					$confirm_suppression = "confirm_suppression.html";
					$action_suppression_oui = "supprimer_actus&amp;cas=2";
					$action_suppression_non = "actus";
					$action_form = "actus";
					$bouton_form = "CREER";
					$invisible = "style=\"display:none\"";
					$script = "<script type=\"text/javascript\" src=\"../editeur/editeur.js\"></script>\n";
					$css = "<link href=\"../editeur/editeur.css\" rel=\"stylesheet\" type=\"text/css\" />\n";      
				
					$sql = $connexion->query("SELECT * FROM langues ORDER BY pays");// on créé la liste déroulante dynamique des langues
					$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
					$ld_langues = "<option value=\"\">Langues</option>\n";
					while($ligne_langues = $sql->fetch())
					{
						$ld_langues .= "<option value=\"" . $ligne_langues->id_langue . "\">" . $ligne_langues->pays . " (" . $ligne_langues->symbole . ")</option>\n";
					}       

					if(isset($_GET['id_actu'])) $_SESSION['id_actu']=$_GET['id_actu'];
					if(isset($_GET['cas']) && $_GET['cas'] == 2) // si on a cliqué sur OUI
					{
						$invisible="";	// permet de réafficher le formulaire
						$resultat_suppr_actus = $connexion->exec("DELETE FROM actus WHERE id_actu='" . $_SESSION['id_actu']. "'");
						$confirm_suppression = "vide.html";
						$_SESSION['avertissement'] = "<label id=\"ok\">Actu supprimée</label>\n";
						header("Location:admin.php?action=actus"); 
					}             
				
					$requete_affichage_actus = "SELECT a.*,l.* FROM actus a,langues l WHERE a.id_langue=l.id_langue ORDER BY l.pays, a.date_creation_actu";	// affichage des actus présentes dans la table
					$affichage = afficher_actus($requete_affichage_actus, $connexion); 

					$requete_affichage_medias = "SELECT * FROM medias ORDER BY  type_media, fichier_media, id_media";	// on affiche toujours les medias présents dans la table
					$affichage_centre = afficher_medias($requete_affichage_medias, "centre", $connexion);   

				break;       
/*************************

	cas syndication
	
***********************/
				case "syndications":

					$contenu = "form_syndications.html";
					$titre = "Gestion des syndications";
					$action = "syndications";  
					$abort = $action;
					$bouton = "Ajouter";
					$select = " selected=\"selected\""; 
					$checked['non'] = " checked=\"checked\""; 
				
					$sql = $connexion->query("SELECT * FROM langues ORDER BY pays");	// on créé la liste déroulante dynamique des langues
					$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
					$ld_langues = "<option value=\"\">Langues</option>\n";
					while($ligne_langues = $sql->fetch())
					{
						$ld_langues .= "<option value=\"" . $ligne_langues->id_langue . "\">" . $ligne_langues->pays . " (" . $ligne_langues->symbole . ")</option>\n";
					}        

					if(isset($_SESSION['id_syndication'])) unset($_SESSION['id_syndication']);
					
					if(isset($_POST['submit']))
					{
						$tab_champ = array("id_langue"=>"Langue", "titre_syndication"=>"Titre de la syndication", "url_syndication"=>"Adresse de la syndication", "nombre"=>"Nombre de lignes affichées");
						$tab_vide = array();  

						while(list($key, $value) = each($tab_champ)) 
						{
							if(empty($_POST[$key]))
							{
								$color_champ[$key] = " class=\"color_champ\"";
								array_push($tab_vide, $value);
							}
						}        

						if(!empty($tab_vide)) $avertissement = "<label id=\"avertissement\">" . champs(count($tab_vide), "debut") . implode(", ", $tab_vide) . champs(count($tab_vide), "fin") ."</label>\n";              
						else
						{
							$sql = $connexion->prepare("SELECT count(*) FROM syndications");
							$sql->execute();
							$nb_syndications = $sql->fetchColumn();

							$requete_insert_syndications = "INSERT INTO syndications SET id_langue='" . $_POST['id_langue'] . "',
							titre_syndication='" . addslashes($_POST['titre_syndication']) . "', 
							url_syndication='" . addslashes($_POST['url_syndication']) . "', 
							nombre='" . $_POST['nombre'] . "',
							affiche='" . $_POST['affiche'] . "'";
							if($nb_syndications  ==  0) $requete .= ", affiche='oui'";

							$resultat_insert_syndications = $connexion->exec($requete_insert_syndications);
							$_SESSION['avertissement'] = "<label id=\"ok\">Création effectuée.</label>";        
							header("Location:admin.php?action=syndications");
						}       
					}

					$ld_nombre = "";
					for($i = 1; $i <= 15; $i++)
					{
						$selection = "";
						if(isset($_POST['nombre']) && $_POST['nombre']  ==  $i) $selection=$select;

						$ld_nombre .= "<option class=\"option_plein\" value=\"" . $i . "\"" . $selection . ">" . $i . "</option>\n";
					}

					$requete_syndications = "SELECT * FROM syndications ORDER BY titre_syndication";
					$affichage = afficher_syndications($requete_syndications, $connexion);

				break;
/*************************

	cas modifier syndication
	
***********************/
				case "modifier_syndications":

					$contenu = "form_syndications.html";
					$titre = "Gestion des syndications";
					$action = "modifier_syndications";  
					$abort = "syndications";
					$bouton = "Modifier";

					//if(isset($_POST['id_langue'])) $selected[$_POST['id_langue']] = $select;        

					if(isset($_GET['id_syndication']))
					{
						$_SESSION['id_syndication'] = $_GET['id_syndication'];
						$sql = $connexion->query("SELECT * FROM syndications WHERE id_syndication='" . $_GET['id_syndication'] . "'");
						$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
						$ligne_syndications = $sql->fetch();
						$selected[$ligne_syndications->id_langue] = " selected=\"selected\"";       
						$_POST['titre_syndication']  =$ligne_syndications->titre_syndication;
						$_POST['url_syndication'] = $ligne_syndications->url_syndication;
						$_POST['nombre'] = $ligne_syndications->nombre;
						$checked[$ligne_syndications->affiche] = " checked=\"checked\"";
				
						$sql = $connexion->query("SELECT * FROM langues ORDER BY pays");	// on créé la liste déroulante dynamique des langues
						$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
						$ld_langues = "<option value=\"\">Langues</option>\n";
						while($ligne_langues = $sql->fetch())
						{
							if($ligne_langues->id_langue == $ligne_syndications->id_langue) $select = $selected[$ligne_langues->id_langue];
							else $select = "";

							$ld_langues .= "<option value=\"" . $ligne_langues->id_langue . "\"$select>" . $ligne_langues->pays . " (" . $ligne_langues->symbole . ")</option>\n";
						}
					}

					$ld_nombre = "";
					for($i = 1; $i <= 15; $i++)
					{
						$selection="";
						
						if(isset($_POST['nombre']) && $_POST['nombre']  ==  $i) $selection=" selected=\"selected\""; 

						$ld_nombre .= "<option class=\"option_plein\" value=\"" . $i . "\"" . $selection . ">" . $i . "</option>\n";
					}               

					if(isset($_POST['submit']))
					{
						$tab_champ = array("id_langue"=>"Langue souhaitée", "titre_syndication"=>"Titre de la syndication", "url_syndication"=>"Adresse de la syndication", "nombre"=>"Nombre de lignes affichées");
						$tab_vide = array();  

						while(list($key, $value) = each($tab_champ)) 
						{
							if(empty($_POST[$key]))
							{
								$color_champ[$key] = " class=\"color_champ\"";
								array_push($tab_vide, $value);
							}
						}        

						if(!empty($tab_vide)) $avertissement="<label id=\"avertissement\">" . champs(count($tab_vide), "debut") . implode(", ", $tab_vide) . champs(count($tab_vide), "fin") ."</label>\n";              
						else
						{
							$requete_maj_syndications = "UPDATE syndications SET id_langue='" . $_POST['id_langue'] . "', 
							titre_syndication='" . addslashes($_POST['titre_syndication']) . "', 
							url_syndication='" . addslashes($_POST['url_syndication']) . "', nombre='" . $_POST['nombre'] . "', affiche='" . $_POST['affiche'] . "'  
							WHERE id_syndication='" . $_SESSION['id_syndication'] . "'";
							$resultat_maj_syndications = $connexion->exec($requete_maj_syndications);
							$_SESSION['avertissement'] = "<label id=\"ok\">Modification effectuée.</label>";        
							header("Location:admin.php?action=syndications");
						}       
					}

					$requete_affichage_syndications = "SELECT * FROM syndications ORDER BY titre_syndication";
					$affichage = afficher_syndications($requete_affichage_syndications, $connexion);

				break;
/*************************

	cas supprimer syndication
	
***********************/
				case "supprimer_syndications":

					$contenu = "form_syndications.html";
					$titre = "Gestion des syndications";
					$confirm_suppression = "confirm_suppression.html";
					$action_suppression_oui = "supprimer_syndications&amp;cas=2";
					$action_suppression_non = "syndications";
					$invisible = "style=\"display:none\"";
					$abort = $suppr_non;

					$sql = $connexion->query("SELECT * FROM langues ORDER BY pays");	// on créé la liste déroulante dynamique des langues
					$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);	
					$ld_langues = "<option value=\"\">Langues</option>\n";
					while($ligne_langues = $sql->fetch())
					{
						$ld_langues .= "<option value=\"" . $ligne_langues->id_langue . "\">" . $ligne_langues->pays . " (" . $ligne_langues->symbole . ")</option>\n";
					} 

					if(isset($_GET['id_syndication'])) $_SESSION['id_syndication']=$_GET['id_syndication'];

					if(isset($_SESSION['id_syndication']) && isset($_GET['cas']))
					{             
						$resultat_suppr_syndications = $connexion->exec("DELETE FROM syndications WHERE id_syndication='" . $_SESSION['id_syndication'] . "'");
						$_SESSION['avertissement'] = "<label id=\"ok\">Suppression effectuée.</label>";        
						header("Location:admin.php?action=syndications");          
					}

					$requete_affichage_syndication = "SELECT * FROM syndications ORDER BY titre_syndication";
					$affichage = afficher_syndications($requete_affichage_syndication, $connexion);

				break;
/*************************

	cas evenements
	
***********************/
				case "evenements":
					
					$contenu = "form_evenements.html";
					$titre = "Gestion des événements";
					$confirm_suppression = "vide.html";
					$action_form = "evenements";
					$bouton_form = "CREER";
					$checked['non'] = " checked=\"checked\"";  
					$script = "<script type=\"text/javascript\" src=\"../editeur/editeur.js\"></script>\n";
					$css = "<link href=\"../editeur/editeur.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
					
					$sql = $connexion->query("SELECT * FROM langues ORDER BY pays");	// on créé la liste déroulante dynamique des langues
					$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
					$ld_langues = "<option value=\"\">Langues</option>\n";
					while($ligne_langues = $sql->fetch())
					{
						$ld_langues .= "<option value=\"" . $ligne_langues->id_langue . "\">" . $ligne_langues->pays . " (" . $ligne_langues->symbole . ")</option>\n";
					}      

					if(isset($_SESSION['id_evenement'])) unset($_SESSION['id_evenement']); 

					if(isset($_POST['submit']))
					{
						$checked[$_POST['visible']] = " checked=\"checked\"";  
					
						$tab_champ = array("titre_evenement"=>"Titre de l'événement", "id_langue"=>"Langue", "date_debut_evenement"=>"Date de début de l'événement", "date_fin_evenement"=>"Date de fin de l'événement");	//liste des champs obligatoires
						$tab_vide = array();
						
						while(list($key, $value) = each($tab_champ)) 
						{
							if(empty($_POST[$key]))
							{
								$color_champ[$key] = " class=\"color_champ\"";
								array_push($tab_vide, $value);
							}
						}        

						if(!empty($tab_vide)) $avertissement="<label id=\"avertissement\">" . champs(count($tab_vide), "debut") . implode(", ", $tab_vide) . champs(count($tab_vide), "fin") ."</label>\n";              
						else
						{
							$requete = "
							SELECT count(id_evenement) FROM evenements 
							WHERE date_debut_evenement <= " . $_POST['date_fin_evenement'] . " 
							AND date_fin_evenement >= " . $_POST['date_debut_evenement'] . "";
							$sql = $connexion->prepare($requete);
							$sql->execute();
							$nb = $sql->fetchColumn();
							$_SESSION['toto'] = $requete;
							
							if($nb > 1)
							{
								$avertissement = "
								<label id=\"avertissement\">Vous ne pouvez pas faire se chevaucher plus de 2 événements.<br>\n
								Modifiez les dates de début / fin ou supprimez un événement pour ajouter celui-ci.</label>\n";
							}
							else
							{
							
								$requete_insert_evenements = "INSERT INTO evenements SET titre_evenement='" . addslashes($_POST['titre_evenement']) . "',
								contenu_evenement='" . addslashes($_POST['contenu_evenement']) . "',
								date_debut_evenement='" . addslashes($_POST['date_debut_evenement']) . "',
								date_fin_evenement='" . addslashes($_POST['date_fin_evenement']) . "',
								nature='" . $_POST['nature'] . "',
								visible='" . $_POST['visible'] . "',
								id_langue='" . $_POST['id_langue'] . "'";
								$resultat_insert_evenements = $connexion->exec($requete_insert_evenements);
								$avertissement = "<label id=\"ok\">Evénement créé</label>\n";
							
								foreach($_POST as $nom_champ=>$valeur)	// permet de vider l'intégralité des champs du formulaire
								{
									$_POST[$nom_champ]="";
								}
							}
						}    
					}
				
					$requete_affichage_evenements = "SELECT e.*,l.* FROM evenements e,langues l WHERE e.id_langue=l.id_langue ORDER BY l.pays, e.date_debut_evenement";            
					$affichage = afficher_evenements($requete_affichage_evenements, $connexion);	// affichage des evenements présentes dans la table

					// on affiche toujours les medias présents dans la table
					$requete = "SELECT * FROM medias ORDER BY  type_media, fichier_media, id_media";
					$affichage_centre=afficher_medias($requete, "centre", $connexion);       

				break;
/*************************

	cas modifier evenements
	
***********************/
				case "modifier_evenements":
				
					$contenu = "form_evenements.html";  //toujours afficher formulaire evenements 
					$titre = "Gestion des événements";
					$confirm_suppression = "vide.html";
					$action_form = "modifier_evenements";
					$bouton_form = "MODIFIER";
					$script = "<script type=\"text/javascript\" src=\"../editeur/editeur.js\"></script>\n";
					$css = "<link href=\"../editeur/editeur.css\" rel=\"stylesheet\" type=\"text/css\" />\n";      

					if(isset($_POST['id_langue']))$selected[$_POST['id_langue']]=" selected=\"selected\"";        
					if(isset($_POST['nature']))$selected2[$_POST['nature']]=" selected=\"selected\"";        
				
					if(isset($_GET['id_evenement']))	//on recharge le formulaire avec les valeurs stockées en base
					{
						$sql = $connexion->query("SELECT * FROM evenements WHERE id_evenement='" . $_GET['id_evenement'] . "'");
						$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
						$ligne_evenements = $sql->fetch();  //pas besoin d'un while car 1 seul resultat
						$_POST['titre_evenement'] = $ligne_evenements->titre_evenement;
						$_POST['id_langue'] = $ligne_evenements->id_langue;      
						$selected[$ligne_evenements->id_langue] = " selected=\"selected\"";
						$selected2[$ligne_evenements->nature] = " selected=\"selected\"";        
						$_POST['contenu_evenement'] = $ligne_evenements->contenu_evenement; 
						$_POST['date_debut_evenement'] = $ligne_evenements->date_debut_evenement;
						$_POST['date_fin_evenement'] = $ligne_evenements->date_fin_evenement;                        
						$_SESSION['id_evenement'] = $ligne_evenements->id_evenement;
						$checked[$ligne_evenements->visible] = " checked=\"checked\"";
					}      
					
					$sql = $connexion->query("SELECT * FROM langues ORDER BY pays");	// on créé la liste déroulante dynamique des langues
					$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
					$ld_langues = "<option value=\"\"></option>\n";
					while($ligne_langues = $sql->fetch())
					{
						if($ligne_langues->id_langue == $ligne_evenements->id_langue) $select = $selected[$ligne_langues->id_langue];
						else $select = "";

						$ld_langues .= "<option value=\"" . $ligne_langues->id_langue . "\"$select>" . $ligne_langues->pays . " (" . $ligne_langues->symbole . ")</option>\n";
					}          
					
					if(isset($_POST['submit']))//si qq'1 a appuyé sur le bouton modifier
					{
						$tab_champ = array("titre_evenement"=>"Titre de l'événement", "id_langue"=>"Langue", "date_debut_evenement"=>"Date de début de l'événement", "date_fin_evenement"=>"Date de fin de l'événement");	//liste des champs obligatoires
						$tab_vide = array();  

						while(list($key, $value) = each($tab_champ)) 
						{
							if(empty($_POST[$key]))
							{
								$color_champ[$key] = " class=\"color_champ\"";
								array_push($tab_vide, $value);
							}
						}        

						if(!empty($tab_vide)) $avertissement = "<label id=\"avertissement\">" . champs(count($tab_vide), "debut") . implode(", ", $tab_vide) . champs(count($tab_vide), "fin") ."</label>\n";              
						else
						{  
							$requete_maj_evenements = "UPDATE evenements SET titre_evenement='" . addslashes($_POST['titre_evenement']) . "', 
							id_langue='" . $_POST['id_langue'] . "',
							contenu_evenement='" . addslashes($_POST['contenu_evenement']) . "',                                      
							date_debut_evenement='" . $_POST['date_debut_evenement'] . "',
							date_fin_evenement='" . $_POST['date_fin_evenement'] . "',
							visible='" . $_POST['visible'] . "',
							nature='" . $_POST['nature'] . "'                                                                            
							WHERE id_evenement='" . $_SESSION['id_evenement'] . "'";
							$resultat_maj_evenements = $connexion->exec($requete_maj_evenements);
							$_SESSION['avertissement'] = "<label id=\"ok\">Modification effectuée</label>\n";
							header("Location:admin.php?action=evenements");
						} 
					}  

					$requete_affichage_evenements = "SELECT e.*,l.* FROM evenements e,langues l WHERE e.id_langue=l.id_langue ORDER BY l.pays, e.date_debut_evenement";	// affichage des evenements présentes dans la table
					$affichage = afficher_evenements($requete_affichage_evenements, $connexion); 

					$requete_affichage_medias = "SELECT * FROM medias ORDER BY  type_media, fichier_media, id_media";	// on affiche toujours les medias présents dans la table
					$affichage_centre = afficher_medias($requete_affichage_medias, "centre", $connexion);

				break;
/*************************

	cas supprimer evenements
	
***********************/
				case "supprimer_evenements":
					
					$contenu = "form_evenements.html";  //toujours afficher formulaire evenements 
					$titre = "Gestion des événements";
					$confirm_suppression = "confirm_suppression.html";
					$action_suppression_oui = "supprimer_evenements&amp;cas=2";
					$action_suppression_non = "evenements";
					$action_form = "evenements";
					$bouton_form = "CREER";
					$invisible = "style=\"display:none\"";
					$script = "<script type=\"text/javascript\" src=\"../editeur/editeur.js\"></script>\n";
					$css = "<link href=\"../editeur/editeur.css\" rel=\"stylesheet\" type=\"text/css\" />\n";      
				
					$sql = $connexion->query("SELECT * FROM langues ORDER BY pays");	// on créé la liste déroulante dynamique des langues
					$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
					$ld_langues = "<option value=\"\">Langues</option>\n";
					while($ligne_langues = $sql->fetch())
					{
						$ld_langues .= "<option value=\"" . $ligne_langues->id_langue . "\">" . $ligne_langues->pays . " (" . $ligne_langues->symbole . ")</option>\n";
					}       

					if(isset($_GET['id_evenement'])) $_SESSION['id_evenement'] = $_GET['id_evenement'];
					if(isset($_GET['cas']) && $_GET['cas'] == 2) // si on a cliqué sur OUI
					{
						$invisible = "";	// permet de réafficher le formulaire
						$resultat = $connexion->exec("DELETE FROM evenements WHERE id_evenement='" . $_SESSION['id_evenement']. "'");
						$confirm_suppression = "vide.html";
						$_SESSION['avertissement'] = "<label id=\"ok\">Evénement supprimé</label>\n";
						header("Location:admin.php?action=evenements"); 
					}             

					$requete_affichage_evenements = "SELECT e.*,l.* FROM evenements e,langues l WHERE e.id_langue=l.id_langue ORDER BY l.pays, e.date_debut_actu";	// affichage des evenements présentes dans la table
					$affichage = afficher_evenements($requete_affichage_evenements, $connexion); 

					// on affiche toujours les medias présents dans la table
					$requete_affichage_medias = "SELECT * FROM medias ORDER BY  type_media, fichier_media, id_media";
					$affichage_centre = afficher_medias($requete_affichage_medias, "centre", $connexion);   

				break;       
/*************************

	cas contacts
	
***********************/
				case "contacts":

					unset($_SESSION['id_contact']);
					$titre = "Gestion des messages"; 
					$contenu = "contacts.html"; 
					$confirm_suppression = "vide.html";         
					$requete_affichage_messages = "SELECT co.*, ca.* FROM comptes co RIGHT JOIN 
					contacts ca ON 
					co.id_compte=ca.id_compte  
					ORDER BY ca.date_contact DESC";

					$invisible = "style=\"display:none\""; 
					$liste_messages = afficher_messages($requete_affichage_messages, $connexion);

				break;
/*************************

	cas supprimer contacts
	
***********************/
				case "supprimer_contact":

					$titre = "Gestion des messages"; 
					$contenu = "contacts.html";
					$confirm_suppression = "confirm_suppression.html";
					$action_suppression_non = "contacts";
					$action_suppression_oui = "supprimer_contact&amp;cas=2";
					$invisible = "style=\"display:none\""; 
					if(isset($_GET['id_contact'])) $_SESSION['id_contact'] = $_GET['id_contact'];

					if(isset($_GET['cas'])&& $_GET['cas'] == 2)
					{        
						$resultat_suppr_contacts = $connexion->exec("DELETE FROM contacts WHERE id_contact='" . $_SESSION['id_contact'] . "'");
						$confirm_suppression = "vide.html";      
						unset($_SESSION['id_contact']);
					
						$sql = $connexion->prepare("SELECT count(id_contact) FROM contacts WHERE rep='non'");	//on remet à jour la notification
						$sql->execute();
						$nb_contact = $sql->fetchColumn();
						
						if($nb_contact > 0) $notification="<span id=\"notification\">" . $nb_contact . "</span>"; 
						else $notification="";  
					}
					
					$requete_messages = "SELECT co.*, ca.* FROM comptes co RIGHT JOIN 
					contacts ca ON 
					co.id_compte=ca.id_compte  
					ORDER BY ca.date_contact DESC";               
					$liste_messages = afficher_messages($requete_messages,$connexion);         

				break; 
/*************************

	cas repondre contacts
	
***********************/
				case "repondre_contact":

					$titre = "Gestion des messages"; 
					$contenu = "contacts.html";
					$confirm_suppression = "vide.html";
					$invisible = "";  
					$action_form = "repondre_contact";
					$bouton_form = "Envoyer";    
					if(isset($_GET['id_contact']))
					{
						$_SESSION['id_contact'] = $_GET['id_contact']; 
						$sql = $connexion->query("SELECT * FROM contacts WHERE id_contact='". $_SESSION['id_contact'] . "'");
						$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
						$ligne_contact = $sql->fetch(); 
						$_SESSION['nom_contact']=$ligne_contact->nom_contact;
						$_SESSION['prenom_contact']=$ligne_contact->prenom_contact;
						$_SESSION['mel_contact']=$ligne_contact->mel_contact;
						$_SESSION['entreprise']=$ligne_contact->entreprise;
						$_SESSION['objet']=$ligne_contact->objet;       
						$_SESSION['message']=$ligne_contact->message;
						$_SESSION['date_contact']=$ligne_contact->date_contact;
					
						$resultat = $connexion->exec("UPDATE contacts SET rep='1' WHERE id_contact='" . $_SESSION['id_contact'] . "'");	// on implemente le champ rep pour simuler une réponse
					}
					if(isset($_POST['submit']))
					{
						if(empty($_POST['rep_message'])) 
						{
							$avertissement = "<label id=\"avertissement\">Veuillez saisir votre message</label>\n";
							$color_champ['rep_message'] = " id=\"color_champ\"";
						}                                                                                            
						else
						{
							$expediteur = "MAIL_REPONSE";	//fait appel à la constante calculée au début de global.php (formulaire paramètres)
							$_SESSION['rep_message'] = $_POST['rep_message'];
							$requete_messages = "UPDATE contacts 
							SET id_compte='" . $_SESSION['id_acces'] . "',
							message='" . $_SESSION['rep_message']  . "\r\r" . $_SESSION['message'] . "' 
							WHERE id_contact='" . $_SESSION['id_contact'] . "'";
							$resultat_messages = $connexion->exec($requete_messages);

							$sql = $connexion->query("SELECT * FROM contacts WHERE id_contact='". $_SESSION['id_contact'] . "'");
							$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
							$ligne_contacts = $sql->fetch();
							$reponse = $ligne_contacts->message; 

							envoi_mel($_SESSION['mel_contact'],$_SESSION['objet'],$reponse,$expediteur);   
							unset($_SESSION['id_contact'],$_SESSION['nom_contact'],$_SESSION['prenom_contact'],$_SESSION['objet'],$_SESSION['mel_contact'],$_SESSION['message'], $_SESSION['rep_message']);  
							$avertissement = "<label id=\"ok\">Votre message a bien été envoyé.</label>\n";
							$_POST['rep_message'] = "";
							header("Location:admin.php?action=contacts");
						}
					}
					$requete_messages = "SELECT co.*, ca.* FROM comptes co RIGHT JOIN 
					contacts ca ON 
					co.id_compte=ca.id_compte  
					ORDER BY ca.date_contact DESC";               
					$liste_messages = afficher_messages($requete_messages, $connexion); 

				break;          
/*************************

	cas css
	
***********************/
				case "css":

					$contenu = "form_css.html";
					$titre = "Gestion des styles";
					$contenu = "form_css.html";
					$titre = "Gestion des thèmes";
					$action_form = "admin.php?action=css";
					$bouton_form = "Enregistrer";
					$confirm_suppression = "vide.html";
					$abort = $action_form;

					if(isset($_SESSION['id_css'])) unset($_SESSION['id_css']);
					if(isset($_POST['id_template']))  $selected[$_POST['id_template']]=" selected=\"selected\""; 

					$sql = $connexion->query("SELECT * FROM templates ORDER BY nom_template");
					$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
					$ld_templates = "<option value=\"\">Liste des thèmes</option>\n";
					while($ligne_templates = $sql->fetch())
					{
						if(isset($_POST['id_template']) && $ligne_templates->id_template == $_POST['id_template']) $select = $selected[$ligne_templates->id_template];
						else $select = "";

						$ld_templates .= "<option value=\"" . $ligne_templates->id_template . "\"" . $select . ">" . $ligne_templates->nom_template . "</option>\n";               
					}

					if(isset($_POST['submit']))
					{
						$tab_champ = array("id_template"=>"Template", "nom_css"=>"Nom du fichier CSS");
						$tab_vide = array();  

						while(list($key, $value) = each($tab_champ)) 
						{
							if(empty($_POST[$key]))
							{
								$color[$key] = "class=\"color_champ\"";
								array_push($tab_vide, $value);
							}
						}        

						if(!empty($tab_vide)) $avertissement="<p id=\"avertissement\">" . champs(count($tab_vide), "debut") . implode(", ", $tab_vide) . champs(count($tab_vide), "fin") ."</p>\n";              
						else
						{
							$chemin_css = "../templates/";
							$nom_fichier_css = $_POST['nom_css']  . "_" . $_POST['id_template'] . ".css";					
							$nom_fichier_css = remplacer_accents($nom_fichier_css);
							$chemin_complet_css = $chemin_css.$nom_fichier_css;

							$resultat_insert_templates = $connexion->exec("INSERT INTO css SET id_template='" . $_POST['id_template']  . "', nom_css='" . remplacer_accents($chemin_complet_css) . "'");
							$id_css = $connexion->lastInsertId();
							
							if(!empty($_FILES['lien_css']['name']))
							{
								if(fichier_type($_FILES['lien_css']['name'])  ==  "css") 
								{
									if(is_uploaded_file($_FILES['lien_css']['tmp_name']))
									{               			
										if(copy($_FILES['lien_css']['tmp_name'], $chemin_complet_css))
										{
											$resultat = $connexion->exec("UPDATE css SET lien_css='" . $_POST['nom_css']  . "_" . $_POST['id_template'] . "' WHERE id_css='" . $id_css . "'");
										}
									}
								}
								else $avertissement="Ce n'est pas une feuille de style valide";
							}
							else
							{
								$fichier_texte = @fopen($chemin_complet_css, "w");	
								$resultat_css = $connexion->exec("UPDATE css SET lien_css='" . $_POST['nom_css']  . "_" . $_POST['id_template'] . "' WHERE id_css='" . $id_css . "'");
							}
							$_SESSION['avertissement'] = "<p id=\"ok\">Création de CSS effectuée.</p>\n";
							header("Location:admin.php?action=css");
						}
					}
					$requete_css = "SELECT t.*, c.* FROM templates t LEFT JOIN css c 
					ON t.id_template=c.id_template 
					ORDER BY t.nom_template, c.id_css";
					$affichage = afficher_css($requete_css,$connexion);  

				break;
/*************************

	cas supprimer css
	
***********************/
				case "supprimer_css":
				
					$contenu = "form_css.html";
					$titre = "Gestion des thèmes";
					$action_form = "admin.php?action=css";
					$bouton_form = "ENREGISTRER";      
					$confirm_suppression = "confirm_suppression.html";
					$action_suppression_oui = "supprimer_css&amp;cas=2";
					$action_suppression_non = "css";
					$invisible = "style=\"display:none\"";

					if(isset($_GET['id_css']))   $_SESSION['id_css']=$_GET['id_css'];

					if(isset($_GET['cas']) && $_GET['cas'] == 2) // si on a cliqué sur OUI
					{
						$invisible = "";	// permet de réafficher le formulaire
					
						$sql = $connexion->query("SELECT * FROM css WHERE id_css='" . $_SESSION['id_css'] . "'");	//on sélectionne la feuille de style à supprimer du dossier
						$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
						$ligne_css = $sql->fetch();
						@unlink($ligne_css->lien_css);

						$resultat_suppr_css = $connexion->exec("DELETE FROM css WHERE id_css='" . $_SESSION['id_css'] . "'");	//on supprime ensuite la ligne de la table "css"
						$confirm_suppression = "vide.html";
						$avertissement = "<label id=\"ok\">Feuille de style supprimée</label>\n";

						
						unset($_SESSION['id_css']);	//on efface la variable $_SESSION id_rubrique
					
						$resultat_css = $connexion->query("SELECT * FROM templates ORDER BY nom_template");	//on recréé la liste déroulante des templates
						$ld_templates = "<option value=\"\">Liste des thèmes</option>\n";
						while($ligne_css = $resultat_css->fetch())
						{           
							$ld_templates .= "<option value=\"" . $ligne_css->id_template . "\">" . $ligne_css->nom_template . "</option>\n";               
						}
					}
					$requete_css = "SELECT t.*, c.* FROM templates t LEFT JOIN css c 
					ON t.id_template=c.id_template 
					ORDER BY t.nom_template, c.id_css";
					$affichage = afficher_css($requete_css,$connexion); 
					
				break;
/*************************

	modifier css
	
***********************/
				case "modifier_css":
				
					$contenu = "form_css.html";
					$titre = "Gestion des thèmes";
					$action_form = "admin.php?action=modifier_css";// la variable cas permet de distinguer
					//le rechargement des champs de formulaire du reenregistrement des champs modifiés
					$bouton_form = "Modifier"; 
					$confirm_suppression = "vide.html"; 	
					if(isset($_GET['id_css']))
					{
						$_SESSION['id_css'] = $_GET['id_css'];
						$sql = $connexion->query("SELECT * FROM css WHERE id_css='" . $_GET['id_css'] . "'");
						$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
						$ligne_css = $sql->fetch();
						$selected[$ligne_css->id_template] = " selected=\"selected\"";
						$_SESSION['id_template'] = $ligne_css->id_template;              
						$_POST['nom_css'] = $ligne_css->nom_css;
						$_SESSION['lien_css'] = $ligne_css->lien_css;   

						$sql = $connexion->query("SELECT * FROM templates ORDER BY nom_template");	//on créé la liste déroulante des templates
						$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
						$ld_templates = "<option value=\"\">Liste des thèmes</option>\n";
						while($ligne_noms_css = $sql->fetch())
						{
							if($ligne_noms_css->id_template == $ligne_css->id_template) $select = $selected[$ligne_css->id_template];
							else$select="";

							$ld_templates .= "<option value=\"" . $ligne_noms_css->id_template . "\"" . $select . ">" . $ligne_noms_css->nom_template . "</option>\n";               
						}
					}
				
					if(isset($_POST['submit']))	//si qq'1 a appuyé sur le bouton modifier
					{
						if(empty($_POST['id_template']))
						{
							$avertissement = "<label id=\"avertissement\">Veuillez sélectionner un thème</label>\n";
							$color_champ['id_template'] = " class=\"color_champ\"";
						} 			   
						elseif(empty($_POST['nom_css']))
						{
							$avertissement = "<label id=\"avertissement\">Veuillez saisir le nom de la feuille de style</label>\n";
							$color_champ['nom_css'] = " class=\"color_champ\"";
						}  			
						else
						{
							$chemin_css = "../templates/";	
							$nom_fichier_css = $_POST['nom_css'] . "_" . $_POST['id_template'] . ".css";
							$nom_fichier_css = strtolower(remplacer_accents($nom_fichier_css));
							$chemin_complet_css =  $chemin_css.$nom_fichier_css;	

							if($_FILES['lien_css']['name']) //si le champ parcourir est rempli
							{
								if(fichier_type($_FILES['lien_css']['name']) == "css") // si le type de fichier est correct
								{
									if(is_uploaded_file($_FILES['lien_css']['tmp_name'])) copy($_FILES['lien_css']['tmp_name'], $chemin_complet_css);	// on copie le fichier dans le répertoire templates
								}
								else $avertissement="<label id=\"avertissement\">Ce n'est pas une feuille de style valide</label>\n";
							}
							else
							{
								rename($_SESSION['lien_css'], $chemin_complet_css);	// on renomme le fichier css en fonction des modifs effectuées
							}
							$requete_maj_css = "UPDATE css SET 
							id_template='" . $_POST['id_template'] . "', 											
							nom_css='" . strtolower(remplacer_accents($_POST['nom_css'])) . "',													
							lien_css='" . $chemin_complet_css . "' WHERE id_css='" . $_SESSION['id_css'] . "'";	
							$resultat_maj_css = $connexion->exec($requete_maj_css);                 				
						}
						foreach($_POST as $nom_champ=>$valeur)	//on efface le contenu des champs
						{
							$_POST[$nom_champ]="";
						}  
					}
					
					$requete_affichage_templates = "SELECT t.*, c.* FROM templates t LEFT JOIN css c 
					ON t.id_template=c.id_template 
					ORDER BY t.nom_template, c.id_css";
					$affichage = afficher_css($requete_affichage_templates,$connexion);
					
				break;
/*************************

	editer css
	
***********************/
				case "editer_css":

					$contenu = "form_edit_css.html";
					$action_form = "admin.php?action=editer_css";
					$bouton_form = "Modifier";  
					$script = "<script type=\"text/javascript\" src=\"../js/syntax/syntax.js\"></script>\n";   
					$script .= "<script type=\"text/javascript\" src=\"../js/syntax/css.js\"></script>\n";
					$css = "<link href=\"../css/syntax.css\" rel=\"stylesheet\" type=\"text/css\" />\n";

					if(isset($_GET['id_css']))
					{
						$_SESSION['id_css'] = $_GET['id_css'];
						$sql = $connexion->query("SELECT t.*, c.* FROM templates t INNER JOIN css c ON t.id_template=c.id_template WHERE c.id_css='" . $_GET['id_css'] . "'");
						$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
						$ligne_css = $sql->fetch();
						$h3 = "Edition de \"" . $ligne_css->nom_css . ".css\" (" . $ligne_css->nom_template . ")";

						$source_css = ""; 
						$_SESSION['file_css'] = $ligne_css->lien_css;
						$fichier_texte = @fopen($_SESSION['file_css'], "r");
						if($fichier_texte)
						{
							while($ligne_css = fgets($fichier_texte))
							{
								$source_css .= $ligne_css;                   
							}                    
						}
						@fclose($fichier_texte);
					}

					if(isset($_POST['submit']))
					{
						$tab1 = array("<br>", "&nbsp;");
						$tab2 = array("\r", "");              
						$source_css = strip_tags($_POST['source_css'], "<br>");
						$source_css = str_replace($tab1, $tab2, $source_css);                               
						$fichier_texte = fopen("../templates/" . $_SESSION['file_css'] . ".css", "w");	   
						fwrite($fichier_texte, $source_css);
						@fclose($fichier_texte);
					}             

				break;
/*************************

	cas templates
	
***********************/
				case "templates":
				
					$contenu = "form_templates.html";
					$titre = "Gestion des thèmes";
					$action_form = "admin.php?action=templates";
					$bouton_form = "INSÉRER";
					$confirm_suppression = "vide.html";

					if(isset($_SESSION['id_template'])) unset($_SESSION['id_template']);	//on efface la variable $_SESSION id_template

					if(isset($_POST['submit']))
					{
						$tab_champ = array("nom_template"=>"Nom du template", "auteur_template"=>"Auteur du template");	//liste des champs obligatoires
						$tab_vide = array();  

						while(list($key, $value) = each($tab_champ)) 
						{
							if(empty($_POST[$key]))
							{
								$color_champ[$key] = " class=\"color_champ\"";
								array_push($tab_vide, $value);
							}
						}        

						if(!empty($tab_vide)) $avertissement="<label id=\"avertissement\">" . champs(count($tab_vide), "debut") . implode(", ", $tab_vide) . champs(count($tab_vide), "fin") ."</label>\n";              
/*************************

	BUG : Fatal error: Call to a member function setFetchMode() on a non-object in C:\Users\Tahitibob\Logiciels\UwAmp\www\sites_perso\beew\admin\admin.php on line 2441
	
***********************/
						else
						{
							$sql = $connexion->prepare("SELECT count(*) FROM templates WHERE nom_template='" . $_POST['nom_template'] . "' AND auteur_template='" . $_POST['auteur_template'] . "'");
							$sql->execute();
							$nb_templates = $sql->fetchColumn();
							
							if($nb_templates == 0)
							{
								$requete = "INSERT INTO templates SET nom_template='" . $_POST['nom_template'] . "', auteur_template='" . $_POST['auteur_template'] . "', date_template='" . date('Y-m-d') . "'";
								$resultat = $connexion->exec($requete);	// on insere un nouveau template
								header("Location:admin.php?action=css");
							}
							else $avertissement="<label id=\"avertissement\">Un template du même nom et du même auteur existe déjà !</label>\n";

							$avertissement="<label id=\"ok\">Template créé</label>\n";
						
							foreach($_POST as $nom_champ=>$valeur)	// on efface le contenu des champs
							{
								$_POST[$nom_champ]="";
							}
						}
					}
					$requete_affichage_template = "SELECT * FROM templates ORDER BY nom_template";
					$affichage = afficher_templates($requete_affichage_template,$connexion);

				break;  
/*************************

	cas supprimer templates
	
***********************/
				case "supprimer_templates":
				
					$contenu = "form_templates.html";
					$titre = "Gestion des thèmes";
					$action_form = "admin.php?action=templates";
					$bouton_form = "INSÉRER"; 
					$confirm_suppression = "confirm_suppression.html";
					$action_suppression_non = "templates";
					$action_suppression_oui = "supprimer_templates&amp;cas=2";    //aurait pu se faire en 2 cas, mais plus simple pour le copicol de faire comme ça  
					$invisible = "style=\"display:none\"";

					if(isset($_GET['id_template'])) $_SESSION['id_template'] = $_GET['id_template'];

					if(isset($_GET['cas']) and $_GET['cas'] == 2)                   
					{
						$resultat_suppr_template = $connexion->exec("DELETE FROM templates WHERE id_template='" . $_SESSION['id_template'] . "'");
						$resultat_suppr_template_2 = $connexion->exec("DELETE FROM css WHERE id_template='" . $_SESSION['id_template'] . "'");
						$_SESSION['avertissement']="<label id=\"ok\">Template supprimé</label>\n"; 
						header("Location:admin.php?action=templates");
					}
					$requete_affichage_template = "SELECT * FROM templates ORDER BY nom_template";
					$affichage = afficher_templates($requete_affichage,$requete_template);

				break;
/*************************

	cas modifier templates
	
***********************/
				case "modifier_templates":
				
					$contenu = "form_templates.html";
					$titre = "Gestion des thèmes";
					$action_form = "admin.php?action=modifier_templates";
					$bouton_form = "MODIFIER"; 
					$confirm_suppression = "vide.html";

					if(isset($_GET['id_template']))
					{
						$_SESSION['id_template'] = $_GET['id_template'];
						$sql = $connexion->query("SELECT * FROM templates WHERE id_template='" . $_GET['id_template'] . "'");
						$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
						$ligne_resultat = $sql->fetch();
						$_POST['nom_template'] = $ligne_resultat->nom_template;
						$_POST['auteur_template'] = $ligne_resultat->auteur_template;			  
					}

					if(isset($_POST['submit']))
					{
						$tab_champ = array("nom_template"=>"Nom du template", "auteur_template"=>"Auteur du template");	//liste des champs obligatoires
						$tab_vide = array();  

						while(list($key, $value) = each($tab_champ)) 
						{            
							if(empty($_POST[$key]))
							{
								$color_champ[$key] = " class=\"color_champ\"";
								array_push($tab_vide, $value);
							}
						}        

						if(!empty($tab_vide)) $avertissement="<label id=\"avertissement\">" . champs(count($tab_vide), "debut") . implode(", ", $tab_vide) . champs(count($tab_vide), "fin") ."</label>\n";              
						else
						{ 
							$requete_maj_templates = "UPDATE templates 
							SET nom_template='" . $_POST['nom_template'] . "', 
							auteur_template='" . $_POST['auteur_template'] . "' 
							WHERE id_template='" . $_SESSION['id_template'] . "'"; 
							$resultat_maj_templates = $connexion->exec($requete_maj_templates);
							$_SESSION['avertissement'] = "<label id=\"ok\">Template modifié</label>\n";					
						}
						header("Location:admin.php?action=templates"); 
					}

					$requete_affichage_template="SELECT * FROM templates ORDER BY nom_template";
					$affichage = afficher_templates($requete_affichage_template,$connexion);          

				break;
/*************************

	cas langues
	
***********************/
				case "langues":
				
					$contenu = "form_langues.html";
					$action_form = "admin.php?action=langues";
					$bouton_form = "VALIDER";
					$confirm_suppression = "vide.html";

					if(isset($_SESSION['id_langue'])) unset($_SESSION['id_langue']);	//on efface la variable $_SESSION[id_langue]

					if(isset($_POST['submit']))
					{
						$tab_champ = array("pays"=>"Pays", "langue"=>"Langue", "symbole"=>"Symbole");	//liste des champs obligatoires
						$tab_vide = array();  

						while(list($key, $value) = each($tab_champ)) 
						{            
							if(empty($_POST[$key]))
							{
								$color_champ[$key] = " class=\"color_champ\"";
								array_push($tab_vide, $value);
							}
						}        

						if(!empty($tab_vide)) $avertissement="<label id=\"avertissement\">" . champs(count($tab_vide), "debut") . implode(", ", $tab_vide) . champs(count($tab_vide), "fin") ."</label>\n";              
						else
						{
							$sql = $connexion->prepare("SELECT count(*) FROM langues WHERE pays='" . $_POST['pays'] . "'");	//on vérifie que pas de pays identique dans la table langues
							$sql->execute();
							$nb_langues = $sql->fetchColumn();
							if($nb_langues == 0)	// on insere une nouvelle langue
							{
								$requete_langues = "INSERT INTO langues SET pays='" . $_POST['pays'] . "',
								langue='" . $_POST['langue'] . "',
								symbole='" . $_POST['symbole'] . "'";                              
								$resultat_langues = $connexion->exec($requete_langues);
							}
							else
							{
								$avertissement = "<label id=\"avertissement\">Un pays identique existe déjà !</label>\n";
								$color_champ['pays'] = " id=\" color_champ\"";
							}
							$avertissement = "<label id=\"ok\">Langue créée</label>\n";
							foreach($_POST as $nom_champ=>$valeur)
							{
								$_POST[$nom_champ]="";
							}                    
						}      
					}
					$requete_affichage_langues = "SELECT * FROM langues ORDER BY pays";
					$affichage = afficher_langues($requete_affichage_langues,$connexion);

				break;	
/*************************

	cas supprimer langues
	
***********************/
				case "supprimer_langues":
				
					$contenu = "form_langues.html";
					$titre = "Gestion des langues"; 
					$action_form = "admin.php?action=langues";
					$bouton_form = "VALIDER";
					$confirm_suppression = "confirm_suppression.html";
					$action_suppression_non = "langues";
					$action_suppression_oui = "supprimer_langues&amp;cas=2";    //aurait pu se faire en 2 cas, mais plus simple pour le copicol de faire comme ça  
					$invisible = "style=\"display:none\"";

					if(isset($_GET['id_langue'])) $_SESSION['id_langue']=$_GET['id_langue'];

					if(isset($_GET['cas']) and $_GET['cas'] == 2)
					{
						$sql = $connexion->prepare("SELECT count(*) FROM rubriques WHERE id_langue='" . $_SESSION['id_langue'] . "'");
						$sql->execute();
						$nb_langues = $sql->fetch();  
						if($nb_langues > 0) $avertissement="<label id=\"avertissement\">Impossible car des enregistrements connexes existent</label>\n";                 
						else
						{
							$resultat_suppr_langues = $connexion->exec("DELETE FROM langues WHERE id_langue='" . $_SESSION['id_langue'] . "'"); 
							$_SESSION['avertissement'] = "<label id=\"ok\">Langue supprimée</label>\n";         
						}                                  
						header("Location:admin.php?action=langues");
					}
					
					$requete_affichage_langues = "SELECT * FROM langues ORDER BY pays";
					$affichage = afficher_langues($requete_affichage_langues,$connexion);  

				break;
/*************************

	cas modifier langues
	
***********************/
				case "modifier_langues":
				
					$contenu = "form_langues.html";
					$action_form = "admin.php?action=modifier_langues";
					$bouton_form = "MODIFIER"; 
					$confirm_suppression = "vide.html"; 

					if(isset($_GET['id_langue']))
					{
						$_SESSION['id_langue']=$_GET['id_langue'];
						$sql = $connexion->query("SELECT * FROM langues WHERE id_langue='" . $_GET['id_langue'] . "'");
						$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
						$ligne_langues = $sql->fetch();
						$_POST['pays']=$ligne_langues->pays;
						$_POST['langue']=$ligne_langues->langue;
						$_POST['symbole']=$ligne_langues->symbole;
					}
					if(isset($_POST['submit']))
					{
						$tab_champ = array("pays"=>"Pays", "langue"=>"Langue", "symbole"=>"Symbole");	//liste des champs obligatoires
						$tab_vide = array();  

						while(list($key, $value) = each($tab_champ)) 
						{
							if(empty($_POST[$key]))
							{
								$color_champ[$key] = " class=\"color_champ\"";
								array_push($tab_vide, $value);
							}
						}        

						if(!empty($tab_vide)) $avertissement="<label id=\"avertissement\">" . champs(count($tab_vide), "debut") . implode(", ", $tab_vide) . champs(count($tab_vide), "fin") ."</label>\n";              
						else
						{ 
							$requete_maj_langues = "UPDATE langues SET pays='" . $_POST['pays'] . "',
							langue='" . $_POST['langue'] . "',
							symbole='" . $_POST['symbole'] . "'  
							WHERE id_langue='" . $_SESSION['id_langue'] . "'"; 
							$resultat2 = $connexion->exec($requete_maj_langues);	
							$_SESSION['avertissement'] = "<label id=\"ok\">Langue modifiée</label>\n";                        				
						}
						header("Location:admin.php?action=langues");         
					}
					$requeteaffichage_langues = "SELECT * FROM langues ORDER BY pays";
					$affichage = afficher_langues($requeteaffichage_langues,$connexion);       

				break;      
/*************************

	cas albums
	
***********************/
				case "albums":
					$contenu = "form_albums.html";
					$titre = "Gestion des albums";
					$confirm_suppression = "vide.html";
					$action_form = "albums";
					$bouton_form = "+"; 

					$sql = $connexion->query("SELECT * FROM langues ORDER BY pays");	// on créé la liste déroulante dynamique des langues
					$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
					$ld_langues = "<option value=\"\">Langues</option>\n";
					while($ligne_langues = $sql->fetch())
					{
						$ld_langues .= "<option value=\"" . $ligne_langues->id_langue . "\">" . $ligne_langues->pays . " (" . $ligne_langues->symbole . ")</option>\n";
					}
					
					if(isset($_SESSION['id_album'])) unset($_SESSION['id_album']); 

					if(isset($_POST['submit']))
					{
						if(empty($_POST['id_langue']))
						{  
							$avertissement = "<label id=\"avertissement\">Sélectionnez la langue concernée</label>\n";
							$color_champ['id_langue'] = " class=\"color_champ\"";
						}
						elseif(empty($_POST['titre_album']))
						{
							$avertissement = "<label id=\"avertissement\">Saisissez un titre d'album</label>\n";
							$color_champ['titre_album'] = " class=\"color_champ\"";
						}                              
						else
						{
							$requete_albums = "SELECT count(titre_album) FROM albums WHERE titre_album ='" . $_POST['titre_album'] . "' AND id_langue='" . $_POST['id_langue'] . "'";
							$sql = $connexion->prepare($requete_albums);	//on teste si l'album existe deja dans cette langue
							$sql->execute();
							$nb_albums = $sql->fetchColumn();
							
							if($nb_albums != 0) $avertissement = "<label id=\"avertissement\">L'album <strong>'" . $_POST['titre_album'] . "'</strong> existe déjà dans cette langue</label>\n";                      
							else
							{
								$requete_insert_albums = "INSERT INTO albums SET 
								id_langue='" . $_POST['id_langue'] . "', 
								titre_album='" . $_POST['titre_album'] . "',
								date_album='" . date("Y-m-d") . "'";
								$resultat_insert_albums = $connexion->exec($requete_insert_albums);
							}
						}
					}           
				
					$requete_affichage_albums = "SELECT a.*, l.* 
					FROM albums a, langues l 
					WHERE a.id_langue = l.id_langue 
					ORDER BY a.date_album ASC";
					$affichage = afficher_albums($requete_affichage_albums, $connexion);  	//affichage des albums présents en base

				break; 	  
/*************************

	cas supprimer albums
	
***********************/
				case "supprimer_albums":
				
					$contenu = "form_albums.html";  //toujours afficher formulaire albums 
					$titre = "Gestion des albums";
					$confirm_suppression = "confirm_suppression.html";
					$action_suppression_oui = "supprimer_albums&amp;cas=2";
					$action_suppression_non = "albums";
					$action_form = "albums";
					$bouton_form = "CREER";
					$invisible = "style=\"display:none\"";
				
					$sql = $connexion->query("SELECT * FROM langues ORDER BY pays");	// on créé la liste déroulante dynamique des langues
					$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
					$ld_langues="<option value=\"\">Langues</option>\n";
					while($ligne_langues = $sql->fetch())
					{
						$ld_langues .= "<option value=\"" . $ligne_langues->id_langue . "\">" . $ligne_langues->pays . " (" . $ligne_langues->symbole . ")</option>\n";
					}

					if(isset($_GET['id_album']))   $_SESSION['id_album'] = $_GET['id_album'];
					if(isset($_GET['cas']) && $_GET['cas'] == 2) // si on a cliqué sur OUI
					{
						$invisible = "";						// permet de réafficher le formulaire
						$sql = $connexion->exec("DELETE FROM albums WHERE id_album='" . $_SESSION['id_album']. "'");
						$confirm_suppression = "vide.html";        

						$_SESSION['avertissement'] = "<label id=\"ok\">album supprimé</label>\n";      
						header("Location:admin.php?action=albums");      
					}                   
				
					$requete_affichage_albums = "SELECT a.*,l.* FROM albums a,langues l WHERE a.id_langue=l.id_langue ORDER BY l.pays, a.titre_album";      
					$affichage = afficher_albums($requete_affichage_albums, $connexion);	// affichage des albums présentes dans la table
					$affichage_centre = $affichage; 

				break;
/*************************

	cas modifier albums
	
***********************/
				case "modifier_albums":

					$contenu = "form_albums.html";  //toujours afficher formulaire albums 
					$titre = "Gestion des albums";
					$confirm_suppression = "vide.html";
					$action_form = "modifier_albums";
					$bouton_form = "OK";
					if(isset($_POST['id_langue'])) $selected[$_POST['id_langue']]=" selected=\"selected\"";        
				
					if(isset($_GET['id_album']))		//on recharge le formulaire avec les valeurs stockées en base
					{
						$sql = $connexion->query("SELECT * FROM albums WHERE id_album='" . $_GET['id_album'] . "'");
						$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
						$ligne_albums = $sql->fetch();  //pas besoin d'un while car 1 seul resultat
						$_POST['titre_album'] = $ligne_albums->titre_album;  
						$_POST['id_langue'] = $ligne_albums->id_langue;      
						$selected[$ligne_albums->id_langue] = " selected=\"selected\"";
						$_SESSION['id_album'] = $ligne_albums->id_album;
					}      
					
					$sql = $connexion->query("SELECT * FROM langues ORDER BY pays");	// on créé la liste déroulante dynamique des langues
					$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
					$ld_langues="<option value=\"\"></option>\n";
					while($ligne_langues = $sql->fetch())
					{
						if(isset($_POST['id_langue']) && $ligne_langues->id_langue == $_POST['id_langue']) $select = $selected[$ligne_langues->id_langue];
						else $select = "";

						$ld_langues .= "<option value=\"" . $ligne_langues->id_langue . "\"$select>" . $ligne_langues->pays . " (" . $ligne_langues->symbole . ")</option>\n";
					}

					if(isset($_POST['submit']))	//si qq'1 a appuyé sur le bouton modifier
					{
						if(empty($_POST['titre_album'])) $avertissement = "<label id=\"avertissement\">Vous devez saisir un nom d'album</label>\n";
						else
						{
							$requete_maj_albums = "UPDATE albums 
							SET titre_album='" . $_POST['titre_album'] . "', id_langue='" . $_POST['id_langue'] . "' 
							WHERE id_album='" . $_SESSION['id_album'] . "'";
							$resultat_maj_albums = $connexion->exec($requete_maj_albums);
							$_SESSION['avertissement']="<label id=\"ok\">Modification effectuée</label>\n";
						} 
						header("Location:admin.php?action=albums");  
					}        
				
					$requete_affichage_albums = "SELECT a.*,l.* FROM albums a,langues l WHERE a.id_langue=l.id_langue ORDER BY l.pays, a.titre_album";      
					$affichage = afficher_albums($requete_affichage_albums, $connexion);	// affichage des albums présentes dans la table
					$affichage_centre = $affichage; 

				break;
			}
/***************************************************************************





	FIN SWITCH
	
	
	
	
	
***************************************************************************/
		}

	
		$requete = "SELECT m.*, d.*, c.* FROM modules m, droits d, comptes c 
		WHERE c.id_compte='" . $_SESSION['id_acces'] . "' 
		AND c.id_compte=d.id_compte 
		AND d.id_module=m.id_module 
		ORDER BY m.rang";
		$sql = $connexion->query($requete);	//on calcule le menu du back office en fonction des droits de la personne connectée
		$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
		
		$menu_admin = "<ul>\n";          
		while($ligne_menu = $sql->fetch())
		{
			if(isset($_GET['action']) && ($_GET['action'] == $ligne_menu->action || $_GET['action'] == "modifier_" . $ligne_menu->action || $_GET['action'] == "supprimer_" . $ligne_menu->action)) $surbrillance = $actif[$_GET['action']];
			else $surbrillance = "";

			if($ligne_menu->valeur == 1)	// on désactive (grisé) les modules sans droit d'utilisateur
			{
				if($ligne_menu->module == "contacts") $menu_admin .= "<li><a href=\"admin.php?action=" . $ligne_menu->action . "\"" . $surbrillance . "><span>" . $ligne_menu->module . "</span></a>" . $notification . "</li>\n";                   
				else $menu_admin .= "<li><a href=\"admin.php?action=" . $ligne_menu->action . "\"" . $surbrillance . "><span>" . $ligne_menu->module . "</span></a></li>\n";          
			}
			else $menu_admin .= "<li id=\"desactive\"><a href=\"#\" id=\"desactive\">" . $ligne_menu->module . "</a></li>\n";                 
		}  
		$menu_admin .= "</ul>\n";	//fin du calcul du menu admin  

		$connexion = null;
		include("admin.html");
	}

?>
