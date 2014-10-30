<?php
ob_start();//pour faire fonctionner les redirections header
/*************************

	affichage des parametres
	
***********************/
	function afficher_parametres($requete, $connexion)
	{
		$sql = $connexion->query($requete);
		$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
		$liste_parametres = "<table cellspacing=\"0\" class=\"tableau_resultat\">\n";// j'initialise la variable
		$liste_parametres .= "<tr>\n";
		$liste_parametres .= "<td>Comptes mail</td>\n"; 

		$liste_parametres .= "<td>Template choisi</td>\n";  
		$liste_parametres .= "</tr>\n";   
		$i = 0;
		while($ligne = $sql->fetch())
		{
			$liste_parametres .= "<tr>\n";                        
			$liste_parametres .= "<td>Destinataire : " . $ligne->mail_retour . "<br />Expéditeur : " . $ligne->mail_reponse . "</td>\n";  
			$liste_parametres .= "<td>" . $ligne->nom_template . "</td>\n";  
			$liste_parametres .= "</tr>\n"; 
			$_SESSION['style_admin'] = "<link href=\"../css/admin" . $ligne->id_theme . ".css\" rel=\"stylesheet\" type=\"text/css\" />\n"; 
			$i++;
		}
		$liste_parametres .= "</table>\n";  
		return $liste_parametres;   
	}
/*************************

	affichage des rubriques
	
***********************/
	function afficher_css($requete, $connexion)
	{
		$sql = $connexion->query($requete);
		$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
		$liste_css = "<table cellspacing=\"0\" class=\"tableau_resultat\">\n";// on initialise la variable
		$i = 0;
		$tab_template = array();
		while($ligne = $sql->fetch())
		{
			if(isset($_SESSION['id_css']) AND $ligne->id_css == $_SESSION['id_css']) $style = " id=\"ligne_coloree\""; 
			else $style = "";

			$tab_template[$i] = $ligne->id_template;
			if($i == 0 || ($i>0 && $tab_template[$i] != $tab_template[$i-1]))
			{
				$liste_css .= "<tr style=\"background-color:#e5ecf0;height:25px;text-transform:uppercase\">\n";
				$liste_css .= "<td colspan=\"3\" style=\"text-align:left;padding-left:15px\">" . strtoupper($ligne->nom_template) . " du " . format_date($ligne->date_template, "francais") . " - <span style=\"text-transform:capitalize;color:gray;font-style:italic\">by " . $ligne->auteur_template . "</span></td>\n";			
				$liste_css .= "</tr>\n"; 
			}
			if($ligne->lien_css != "")
			{
				$feuille_css = "<a href=\"../templates/" .  $ligne->lien_css . ".css\" target=\"_blank\">" . $ligne->lien_css . "</a>";
				$actions = "<td>     
				<a href=\"admin.php?action=editer_css&id_css=" . $ligne->id_css . "\"><img class=\"css\" src=\"../img/icones/css.png\" title=\"éditer la feuille de style\" alt=\"\" /></a>
				<a href=\"admin.php?action=modifier_css&id_css=" . $ligne->id_css . "\"><img class=\"modifier\" src=\"../img/icones/modifier.png\" title=\"modifier\" alt=\"\" /></a>
				<a href=\"admin.php?action=supprimer_css&id_css=" . $ligne->id_css . "\"><img class=\"supprimer\" src=\"../img/icones/supprimer.png\" title=\"supprimer\" alt=\"\" /></a>
				</td>\n"; 
			}
			else
			{
				$feuille_css = "<span style=\"font-style:italic;color:gray\">aucune feuille de style pour le moment</span>";        
				$actions = "<td>     
				<a href=\"#\"><img class=\"css\" style=\"opacity:0.3;filter:alpha(opacity=30)\" src=\"../img/icones/css.png\" title=\"éditer la feuille de style\" alt=\"\" /></a>
				<a href=\"#\"><img class=\"modifier\" style=\"opacity:0.3;filter:alpha(opacity=30)\" src=\"../img/icones/modifier.png\" title=\"modifier\" alt=\"\" /></a>
				<a href=\"#\"><img class=\"supprimer\" style=\"opacity:0.3;filter:alpha(opacity=30)\" src=\"../img/icones/supprimer.png\" title=\"supprimer\" alt=\"\" /></a>
				</td>\n"; 
			}
			$liste_css .= "<tr " . $style . ">\n";   
			$liste_css .= "<td style=\"text-align:left;padding-left:30px\">" . $feuille_css . "</td>\n";  
			$liste_css .= $actions;		
			$liste_css .= "</tr>\n"; 
			$i++;
		}
		$liste_css .= "</table>\n";  
		return $liste_css;   
	}
/*************************

	affichage des rubriques
	
***********************/
	function afficher_rubriques($requete, $connexion)
	{
		$sql = $connexion->query($requete);
		$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
		$liste_rubriques = "<table class=\"tableau_resultat\" cellspacing=\"0\">\n";
		$i = 0;
		while($ligne = $sql->fetch())
		{
			if(isset($_SESSION['id_rubrique']) AND $ligne->id_rubrique == $_SESSION['id_rubrique']) $style = " id=\"ligne_coloree\""; 
			else $style="";

			$tab_lang[$i] = $ligne->id_langue;
			if($i == 0) $liste_rubriques .= "<tr class=\"titre_langue\">\n<td colspan=\"3\" style=\"background-color:#e5ecf0;height:25px;text-transform:uppercase\">" . $ligne->pays . " (" . $ligne->symbole . ")</td>\n</tr>\n";
			if($i>0 && $tab_lang[$i] != $tab_lang[$i-1]) $liste_rubriques .= "<tr class=\"titre_langue\">\n<td colspan=\"3\" style=\"background-color:#e5ecf0;height:25px;text-transform:uppercase\">" . $ligne->pays . " (" . $ligne->symbole . ")</td>\n</tr>\n";      			

			$liste_rubriques .= "<tr" . $style . ">\n"; 
			$liste_rubriques .= "<td id=\"rubrique-" . $ligne->id_rubrique . "\" class=\"lang-" . $ligne->id_langue . "\">
			<a class=\"up\" href=\"javascript:void(0)\">
			<img src=\"../img/icones/up.png\" alt=\"up\" />
			</a>
			<a class=\"down\" href=\"javascript:void(0)\">
			<img src=\"../img/icones/down.png\" alt=\"down\" />
			</a>
			" . stripslashes($ligne->rubrique) . "</td>\n";        //permet de supprimer l'antislash qui gère apostrophe en bdd    
			$liste_rubriques .= "<td>" . $ligne->symbole . "</td>\n";  
			$liste_rubriques .= "<td>                
			<a href=\"admin.php?action=modifier_rubriques&amp;id_langue=" . $ligne->id_langue . "&amp;id_rubrique=" . $ligne->id_rubrique . "\"><img class=\"modifier\" src=\"../img/icones/modifier.png\" title=\"modifier\" alt=\"\" /></a>
			<a href=\"admin.php?action=supprimer_rubriques&amp;id_langue=" . $ligne->id_langue . "&amp;id_rubrique=" . $ligne->id_rubrique . "\"><img class=\"supprimer\" src=\"../img/icones/supprimer.png\" title=\"supprimer\" alt=\"\" /></a>
			</td>\n"; 
			$liste_rubriques .= "</tr>\n"; 
			$i++;
		}
		$liste_rubriques .= "</table>\n";  
		return $liste_rubriques;   
	}
/*************************

	affichage des actus
	
***********************/
	function afficher_actus($requete, $connexion)
	{
		$sql = $connexion->query($requete);
		$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
		$liste_actus = "<table class=\"tableau_resultat\" cellspacing=\"0\">\n";
		$i = 0;
		while($ligne = $sql->fetch())
		{
			if(isset($_SESSION['id_actu']) AND $ligne->id_actu == $_SESSION['id_actu']) $style=" id=\"ligne_coloree\""; 
			else $style = "";
			
			$tab_lang[$i] = $ligne->id_langue;
			
			if($i == 0) $liste_actus .= "<tr class=\"titre_langue\">\n<td colspan=\"4\" style=\"background-color:#e5ecf0;height:25px;text-transform:uppercase\">" . $ligne->pays . " (" . $ligne->symbole . ")</td>\n</tr>\n";      			
			if($i>0 && $tab_lang[$i] != $tab_lang[$i-1]) $liste_actus .= "<tr class=\"titre_langue\">\n<td colspan=\"4\" style=\"background-color:#e5ecf0;height:25px;text-transform:uppercase\">" . $ligne->pays . " (" . $ligne->symbole . ")</td>\n</tr>\n";      			
			if($ligne->date_debut_actu == 0) $debut = " » ";
			else $debut = $ligne->date_debut_actu . " »";

			if($ligne->date_fin_actu == 0) $fin = " » ";
			else $fin = "» " . $ligne->date_fin_actu;

			if($ligne->rss  ==  "oui") $rss="<img class=\"ok\" style=\"vertical-align:middle\" src=\"../img/icones/ok.png\" alt=\"\" />\n";       
			else $rss = "<img class=\"pas_ok\" style=\"vertical-align:middle\" src=\"../img/icones/pas_ok.png\" alt=\"\" />\n";   

			$liste_actus .= "<tr" . $style . ">\n"; 
			$liste_actus .= "<td>\n<strong>[" . time_ago($ligne->date_creation_actu) . "]</strong> " . $ligne->titre_actu . "</strong>\n</td>\n"; 
			$liste_actus .= "<td>" . $debut . " " . $fin . "</td>\n"; 
			$liste_actus .= "<td>RSS : " . $rss . "</td>\n";          
			$liste_actus .= "<td>                
			<a href=\"admin.php?action=modifier_actus&amp;id_langue=" . $ligne->id_langue . "&amp;id_actu=" . $ligne->id_actu . "\"><img class=\"modifier\" src=\"../img/icones/modifier.png\" title=\"modifier\" alt=\"\" /></a>
			<a href=\"admin.php?action=supprimer_actus&amp;id_langue=" . $ligne->id_langue . "&amp;id_actu=" . $ligne->id_actu . "\"><img class=\"supprimer\" src=\"../img/icones/supprimer.png\" title=\"supprimer\" alt=\"\" /></a>
			</td>\n"; 
			$liste_actus .= "</tr>\n"; 
			$i++;
		}
		$liste_actus .= "</table>\n";  
		return $liste_actus;   
	}
/*************************

	affichage des evenements
	
***********************/
	function afficher_evenements($requete, $connexion)
	{
		$sql = $connexion->query($requete);
		$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
		$liste_evenements = "<table class=\"tableau_resultat\" cellspacing=\"0\">\n";
		$i = 0;
		while($ligne = $sql->fetch())
		{
			if(isset($_SESSION['id_evenement']) AND $ligne->id_evenement == $_SESSION['id_evenement']) $style = " id=\"ligne_coloree\""; 
			else $style = "";

			$tab_lang[$i]=$ligne->id_langue;
			
			if($i == 0) $liste_evenements .= "<tr class=\"titre_langue\">\n<td colspan=\"5\" style=\"background-color:#e5ecf0;height:25px;text-transform:uppercase\">" . $ligne->pays . " (" . $ligne->symbole . ")</td>\n</tr>\n";
			
			if($i>0 && $tab_lang[$i] != $tab_lang[$i-1]) $liste_evenements .= "<tr class=\"titre_langue\">\n<td colspan=\"5\" style=\"background-color:#e5ecf0;height:25px;text-transform:uppercase\">" . $ligne->pays . " (" . $ligne->symbole . ")</td>\n</tr>\n";
			
			if($ligne->visible  ==  "oui") $visible="<img class=\"ok\" style=\"vertical-align:middle\" src=\"../img/icones/ok.png\" alt=\"\" />\n";       
			else $visible = "<img class=\"pas_ok\" style=\"vertical-align:middle\" src=\"../img/icones/pas_ok.png\" alt=\"\" />\n";   

			if($ligne->nature  !=  "") $nature=$ligne->nature;
			else $nature = "c8c8c8";  

			$liste_evenements .= "<tr" . $style . ">\n"; 
			$liste_evenements .= "<td style=\"background-color:#" . $nature . ";width:5%\">&nbsp;</td>\n"; 
			$liste_evenements .= "<td style=\"width:57%\"><h3>" . $ligne->titre_evenement . "</h3></td>\n";//initialement placé après le h3. -> pb : devient énorme si une grande image y figure..." . substr($ligne->contenu_evenement,0,120) . "
			$liste_evenements .= "<td style=\"width:18%\">Du " . $ligne->date_debut_evenement . " au " . $ligne->date_fin_evenement . "</td>\n"; 
			$liste_evenements .= "<td style=\"width:10%\">Visible : " . $visible . "</td>\n";          
			$liste_evenements .= "<td style=\"width:10%\">                
			<a href=\"admin.php?action=modifier_evenements&amp;id_langue=" . $ligne->id_langue . "&amp;id_evenement=" . $ligne->id_evenement . "\"><img class=\"modifier\" src=\"../img/icones/modifier.png\" title=\"modifier\" alt=\"\" /></a>
			<a href=\"admin.php?action=supprimer_evenements&amp;id_langue=" . $ligne->id_langue . "&amp;id_evenement=" . $ligne->id_evenement . "\"><img class=\"supprimer\" src=\"../img/icones/supprimer.png\" title=\"supprimer\" alt=\"\" /></a>
			</td>\n"; 
			$liste_evenements .= "</tr>\n"; 
			$i++;
		}
		$liste_evenements .= "</table>\n";  
		return $liste_evenements;   
	}
/*************************

	affichage des evenements
	
***********************/
	function afficher_langues($requete, $connexion)
	{
		$sql = $connexion->query($requete);
		$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
		$liste_langues = "<table cellspacing=\"0\" class=\"tableau_resultat\">\n";
		$liste_langues .= "<tr style=\"background-color:#e5ecf0;height:25px;text-transform:uppercase\">\n";
		$liste_langues .= "<td>Pays</td>\n";
		$liste_langues .= "<td>Langue</td>\n";  
		$liste_langues .= "<td>Symbole</td>\n";  
		$liste_langues .= "<td>Actions</td>\n";  
		$liste_langues .= "</tr>\n";   
		
		$i = 0;
		while($ligne = $sql->fetch())
		{
			if(isset($_SESSION['id_langue']) AND $ligne->id_langue == $_SESSION['id_langue']) $style=" id=\"ligne_coloree\""; 
			else $style = "";

			$liste_langues .= "<tr " . $style . ">\n";                        
			$liste_langues .= "<td>" . $ligne->pays . "</td>\n";
			$liste_langues .= "<td>" . $ligne->langue . "</td>\n";  
			$liste_langues .= "<td>" . $ligne->symbole . "</td>\n";  
			$liste_langues .= "<td>
			<a href=\"admin.php?action=modifier_langues&id_langue=" . $ligne->id_langue . "\"><img class=\"modifier\" src=\"../img/icones/modifier.png\" title=\"modifier\" alt=\"\" /></a>
			<a href=\"admin.php?action=supprimer_langues&id_langue=" . $ligne->id_langue . "\"><img class=\"supprimer\" src=\"../img/icones/supprimer.png\" title=\"supprimer\" alt=\"\" /></a>
			</td>\n"; 
			$liste_langues .= "</tr>\n"; 
			$i++;
		}
		$liste_langues .= "</table>\n";  
		return $liste_langues;   
	}
/*************************

	affichage des templates
	
***********************/
	function afficher_templates($requete, $connexion)
	{
		$sql = $connexion->query($requete);
		$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
		$liste_templates = "<table cellspacing=\"0\" class=\"tableau_resultat\">\n";// j'initialise la variable
		$liste_templates .= "<tr>\n";
		$liste_templates .= "<td colspan=\"2\">Nom du template</td>\n";  
		$liste_templates .= "<td>Auteur</td>\n";  
		$liste_templates .= "<td>Date</td>\n";  
		$liste_templates .= "<td>Actions</td>\n";  
		$liste_templates .= "</tr>\n";   
		$i = 0;
		while($ligne = $sql->fetch())
		{
			if(isset($_SESSION['id_template']) AND $ligne->id_template == $_SESSION['id_template']) $style=" id=\"ligne_coloree\""; 
			else $style="";

			$sql = $connexion->query("SELECT count(*) FROM css WHERE id_template='" . $ligne->id_template . "'");
			$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
			$nb_template = $sql->fetchColumn();
			if($nb_template > 0) $css = "<img class=\"css\" src=\"../img/icones/css.png\" alt=\"\" title=\"feuille(s) de style liée(s)\" />";
			else $css = "";

			$liste_templates .= "<tr " . $style . ">\n";                        
			$liste_templates .= "<td>" . $ligne->nom_template . "</td>\n";
			$liste_templates .= "<td>" . $css . "</td>\n";  
			$liste_templates .= "<td>" . $ligne->auteur_template . "</td>\n";
			$liste_templates .= "<td>" . format_date($ligne->date_template, "francais") . "</td>\n";		
			$liste_templates .= "<td>
			<a href=\"admin.php?action=modifier_templates&id_template=" . $ligne->id_template . "\"><img class=\"modifier\" src=\"../img/icones/modifier.png\" title=\"modifier\" alt=\"\" /></a>
			<a href=\"admin.php?action=supprimer_templates&id_template=" . $ligne->id_template . "\"><img class=\"supprimer\" src=\"../img/icones/supprimer.png\" title=\"supprimer\" alt=\"\" /></a>
			</td>\n"; 
			$liste_templates .= "</tr>\n"; 
			$i++;
		}
		$liste_templates .= "<tr>\n<td colspan=\"5\" style=\"height:25px;border-top:1px solid gray\"><a href=\"admin.php?action=css\">Retour aux CSS</a></td>\n</tr>\n";   
		$liste_templates .= "</table>\n";  
		return $liste_templates;   
	}
/*************************

	affichage des pages
	
***********************/
	function afficher_pages($requete, $connexion)
	{
		$sql = $connexion->query($requete);
		$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
		$i = 0;
		
		$liste_pages = "<table class=\"tableau_resultat\" cellspacing=\"0\">\n";   
		$liste_pages .= "<tr class=\"tr\">\n";

		$liste_pages .= "<th>Page</th>\n"; 
		$liste_pages .= "<th>Rubrique</th>\n";
		$liste_pages .= "<th>Visibilité</th>\n";
		$liste_pages .= "<th>Indexation</th>\n";
		$liste_pages .= "<th>Mots clés</th>\n";
		$liste_pages .= "<th>Créée le</th>\n";
		$liste_pages .= "<th>Actions</th>\n";  
		$liste_pages .= "</tr>\n";  
		while($ligne = $sql->fetch())
		{
			$style = ""; 
			$rubrique = ""; 
			$id_rubrique = ""; 
			if(isset($_SESSION['id_page']) AND $ligne->id_page == $_SESSION['id_page']) $style = " id=\"ligne_coloree\"";
			if(isset($rubrique_precedente) && ($rubrique_precedente != $ligne->rubrique && $i > 0))
			{
				$i = 0;
				$liste_pages .= "</table>\n";   
				$liste_pages .= "<table class=\"tableau_resultat\" cellspacing=\"0\">\n";   
				$liste_pages .= "<tr class=\"tr\">\n";

				$liste_pages .= "<th>Page</th>\n"; 
				$liste_pages .= "<th>Rubrique</th>\n";
				$liste_pages .= "<th>Visibilité</th>\n";
				$liste_pages .= "<th>Indexation</th>\n";
				$liste_pages .= "<th>Mots clés</th>\n";
				$liste_pages .= "<th>Créée le</th>\n";
				$liste_pages .= "<th>Actions</th>\n";  
				$liste_pages .= "</tr>\n";  
			}
			
			if($ligne->id_rubrique != "") 
			{
				$rubrique = $ligne->rubrique;
				$id_rubrique = " rubrique-" . $ligne->id_rubrique;
			}				
			else $rubrique = "<em style=\"color:gray\">pas de rubrique</em>";

			$liste_pages .= "<tr" . $style . ">\n";
			$liste_pages .= "</td>\n";       
			$liste_pages .= "<td class=\"violet" . $id_rubrique . "\" id=\"page-" . $ligne->id_page . "\">\n";
			$liste_pages .= "<a class=\"up\" href=\"javascript:void(0)\"><img src=\"../img/icones/up.png\" alt=\"up\" /></a>
			 <a class=\"down\" href=\"javascript:void(0)\"><img src=\"../img/icones/down.png\" alt=\"down\" /></a>" . stripslashes($ligne->titre_page) . " ( page=" . $ligne->id_page . " )<br /><span style=\"display:block;float:right;color:#41a62a;font-size:11px\">http://.../" . $ligne->url_rewriting . ".html</span></td>\n";       
			$liste_pages .= "<td class=\"center capi normal id_rubrique\">" . $rubrique . "</td>\n";
			$liste_pages .= "<td class=\"center\">\n";
			$img = "pas_ok";  
			if($ligne->visible  ==  "oui") $img = "ok";  
			
			$liste_pages .= "<img class=\"ok\" src=\"../img/icones/" . $img . ".png\"/>\n";   
			$liste_pages .= "</td>\n";
			$liste_pages .= "<td class=\"center\">\n";
			$img2 = "pas_ok";      
			if($ligne->indexation  ==  "oui")$img2="ok";       
			
			$liste_pages .= "<img class=\"ok\" src=\"../img/icones/" . $img2 . ".png\" />\n";
			$liste_pages .= "</td>\n";
			$liste_pages .= "<td class=\"center\">\n"; 
			$img3 = "pas_ok";      
			if($ligne->keyword  ==  "oui")$img3="ok"; 
			
			$liste_pages .= "<img class=\"ok\" src=\"../img/icones/" . $img3 . ".png\" />\n";
			$liste_pages .= "</td>\n"; 
			$liste_pages .= "<td class=\"date center\">" . $ligne->date_page . "</td>\n"; 
			$liste_pages .= "<td>                
			<a href=\"admin.php?action=modifier_pages&amp;id_page=" . $ligne->id_page . "\"><img class=\"modifier\" src=\"../img/icones/modifier.png\" title=\"modifier\" alt=\"\" /></a>
			<a href=\"admin.php?action=supprimer_pages&amp;id_page=" . $ligne->id_page . "\"><img class=\"supprimer\" src=\"../img/icones/supprimer.png\" title=\"supprimer\" alt=\"\" /></a>
			</td>\n"; 
			$liste_pages .= "</tr>\n";
			
			$rubrique_precedente = $rubrique;
			$i++;
		}
		$liste_pages .= "</table>\n";  
		return $liste_pages;   
	}    
/*************************

	affichage des commentaires
	
***********************/
	function afficher_commentaires($requete, $connexion, $cas)
	{
		$sql = $connexion->query($requete);
		$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
		$fichier_texte = @fopen("../pages/mots_censures.txt", "r");	// on parse un fichier contenant les mots interdits pour en faire un tableau de variables
		if($fichier_texte)
		{
			$i = 0;
			$tab_mots_interdits = array();
			while($ligne = fgets($fichier_texte))
			{
				$tab_mots_interdits[$i] = rtrim($ligne);// rtrim est essentiel pour enlever le vilain espace qui traine à la droite de chaque ligne
				$i++;
			}
		}  
		@fclose($fichier_texte);

		switch($cas)
		{
			case "1": 

				$liste_commentaires = "";  
				while($ligne = $sql->fetch())
				{
					$liste_commentaires .= "<div class=\"bloc_commentaire\">\n";
					$commentaire_corrige=str_replace($tab_mots_interdits,"xxxx",$ligne->commentaire);
					$liste_commentaires .= "<p>" . $commentaire_corrige . "</p>\n";
					$liste_commentaires .= "<p class=\"signature\">" . $ligne->nom . "</p>\n";    
					//$liste_commentaires .= "<a href=\"javascript:void(0);\" onClick=\"suppr_commentaire(" . $ligne->id_commentaire . ", " . $ligne->id_page . ");\">Supprimer</a>\n";     
					$liste_commentaires .= "</div>\n";
				}  
				return $liste_commentaires; 

			break;

			case "2":
			
				$liste_commentaires = "<table cellspacing=\"0\" id=\"tableau_resultat\">\n";
				
				$i = 0;
				while($ligne = $sql->fetch())
				{
					$tab_valide[$i] = $ligne->valide;
					$tab_item_titre[$i] = $ligne->nom_item . " / " . $ligne->nom_page;	// on stocke dans un tableau de variables le nom de l'item et de la page

					if($tab_valide[$i] == "non") $valide="<a href=\"admin.php?action=valider_commentaire&id_commentaire=" . $ligne->id_commentaire . "\"><img class=\"ok\" src=\"../img/icones/ok.png\" title=\"valider\" alt=\"valider\" /></a>";
					else $valide="<img src=\"../img/icones/invalider.gif\" title=\"déjà validé !\" alt=\"\" />";         

					if(!is_int($i/2)) $style = " class=\"color_ligne\"";	//si je suis sur un chiffre impair
					else $style = "";                          

					if($i == 0 || ($i>0 && $tab_valide[$i] != $tab_valide[$i-1]))  
					{ 
						$liste_commentaires .= "<tr>\n";   
						$liste_commentaires .= "<td colspan=\"2\" class=\"titre_valide\">
						Commentaire(s) : " . $tab_valide[$i] . "</td>\n";               
					}

					$liste_commentaires .= "</tr>\n";

					/* si on est au premier tour de boucle ($i == 0)
					OU
					si la valeur stockée dans le tableau de variable $tab_valide 
					précédemment construit est différente au tour de boucle suivant
					(différence entre $i et $i-1)                   
					c.a.d on passe de validé "non" à validé "oui"
					OU
					si la valeur stockée dans le tableau de variable $tab_item_titre
					précédemment construit est différente au tour de boucle suivant
					(différence entre $i et $i-1)
					c.a.d on passe de "item1 / page1" à "item1 / page2"
					alors j'affiche le titre "item / page"*/ 

					if($i == 0 || $tab_valide[$i] != $tab_valide[$i-1] || ($i>0 && $tab_item_titre[$i] != $tab_item_titre[$i-1]))
					{
						$liste_commentaires .= "<tr>\n";   
						$liste_commentaires .= "<td colspan=\"2\" class=\"titre_item\">
						" . $tab_item_titre[$i] . "</td>\n";
						$liste_commentaires .= "</tr>\n";          
					}
					$liste_commentaires .= "<tr" . $style . ">\n";                        
					$liste_commentaires .= "<td>
					<span style=\"font-style:italic;color:orange\">" . $ligne->nom . " le "
					. format_date($ligne->date_commentaire,"francais") . "</span> :<br />
					<a href=\"#\" title=\"" . str_replace($tab_mots_interdits,"xxxx",$ligne->commentaire) . "\">"
					. $ligne->commentaire . "</a></td>\n";  

					$liste_commentaires .= "<td>" . $valide . "&nbsp;&nbsp;&nbsp;<a href=\"admin.php?action=supprimer_commentaire&id_commentaire=" . $ligne->id_commentaire . "\"><img src=\"../img/supprimer.gif\" title=\"supprimer\" alt=\"supprimer\" /></a>
					</td>\n"; 
					$liste_commentaires .= "</tr>\n"; 
					$i++; 
				}
				$liste_commentaires .= "</table>\n";  
				return $liste_commentaires;  
			break;
		}
	}
/*************************

	affichage des comptes
	
***********************/
	function afficher_comptes($requete, $position, $connexion)
	{
		$sql = $connexion->query($requete);
		$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);

		switch($position)
		{
			case "centre" :

			$liste_comptes = "<div id=\"comptes\">\n";
			$i = 0;
			while($ligne = $sql->fetch())
			{
				if($ligne->fichier_compte != "") $image = "../img/medias/profil" . $ligne->id_compte . "." . $ligne->fichier_compte . "?" . code_image(4);
				else $image = "../img/icones/icone_compte.png"; 

				$liste_comptes .= "<div style=\"background:url(" . $image . ") no-repeat center bottom white\">\n";
				$liste_comptes .= "<h3><a href=\"admin.php?action=modifier_comptes&amp;id_compte=" . $ligne->id_compte . "\">" . $ligne->nom . " " . substr($ligne->prenom, 0, 1) . ".</a></h3>\n";
				$liste_comptes .= "</div>\n";
				$i++;
			}
			$liste_comptes .= "</div>\n";

			return $liste_comptes;
			break;

			case "droite" :

			$liste_comptes = "<table class=\"tableau_resultat\" cellspacing=\"0\">\n";
			while($ligne = $sql->fetch())
			{
				if($ligne->fichier_compte != "") $image = "<img style=\"height:24px;border-radius:4px;margin-right:20px\" src=\"../img/medias/profil" . $ligne->id_compte . "." . $ligne->fichier_compte . "?" . code_image(4) . "\" alt=\"\" />";
				else $image = "<img style=\"height:24px;border-radius:4px;margin-right:20px\" src=\"../img/icones/icone_compte.png\" alt=\"\" />"; 

				if(isset($_SESSION['id_compte']) AND $ligne->id_compte == $_SESSION['id_compte']) $style = "id=\"ligne_coloree\"";  
				else $style="";

				$liste_comptes .= "<tr " . $style .">\n";
				$liste_comptes .= "<td>" . $image . " " . $ligne->nom . " " . $ligne->prenom . " [" . $ligne->statut . "]</td>\n";
				$liste_comptes .= "<td>Login : " . $ligne->login . "</td>\n";
				$liste_comptes .= "<td>\n";    
				$liste_comptes .= "<a href=\"admin.php?action=modifier_comptes&amp;id_compte=" . $ligne->id_compte . "\"><img class=\"modifier\" src=\"../img/icones/modifier.png\" alt=\"\" title=\"modifier\" /></a>\n";
				$liste_comptes .= "<a href=\"admin.php?action=supprimer_comptes&amp;id_compte=" . $ligne->id_compte . "\"><img class=\"supprimer\" src=\"../img/icones/supprimer.png\" alt=\"\" title=\"supprimer\" /></a>\n";     
				$liste_comptes .= "</td>\n";      
				$liste_comptes .= "</tr>\n";         
			}
			$liste_comptes .= "</table>\n";

			return $liste_comptes;
			break;  
		}

	}
/*************************

	affichage des medias
	
***********************/
	function afficher_medias($requete, $position, $connexion)
	{
		$sql = $connexion->query($requete);
		$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);

		switch($position)
		{
			case "centre":

				$liste_medias = "<div id=\"galerie_bo\">\n";
				$i = 0;
				$j = 0;
				$k = 0;
				$l = 0;
				$m = 0;
				while($ligne = $sql->fetch())
				{
					/*if($ligne->type_media == "1") $cas = 1;	// cas des images
					elseif($ligne->type_media == "2") $cas = 2;	// cas des pdf
					elseif($ligne->type_media == "3") $cas = 3;	// cas des mp3
					else $cas = 4;	// cas des video
					$tab_lien[$i] = $cas;
					if($i > 0 && $tab_lien[$i] != $tab_lien[$i-1]) $liste_medias .= "<hr />\n";*/
/*************************
	1 : il s'agit d'une image
***********************/
					if($ligne->fichier_media != "" &&  $ligne->fichier_media != "pdf" &&  $ligne->fichier_media != "mp3")
					{
						if($j == 0) $liste_medias .= "<div class=\"type_media\" ><a href=\"javascript:void(0)\" class=\"ouverture\" >Images</a><hr><div>";

						$image = "../img/medias/" . $ligne->titre_media . "." . $ligne->fichier_media;
						$lien = $image;

						$code_insertion = "[" . $ligne->id_media . "]";  
						$liste_medias .= "<div class=\"image\" style=\"background-image:url(" . $image . "?" . code_image(4) . ")
						\">\n<img src=\"" . $image . "\" alt=\"\" />\n";      
						$liste_medias .= "<p title=\"\" id=\"" . $ligne->titre_media . '#_#' . $ligne->alt_media . "\" >" . $code_insertion . "</p>\n</div>\n";
						$j++;
					}
/*************************
	2 : il s'agit d'un pdf
***********************/
					elseif($ligne->fichier_media != "" && $ligne->fichier_media != "mp3") //cas des pdf
					{
						if($k == 0)
						{
							if($j != 0) $liste_medias .= "</div></div>\n<div class=\"type_media\" ><a href=\"javascript:void(0)\" class=\"ouverture\" >PDF</a><hr><div>";
							else $liste_medias .= "<div class=\"type_media\" ><a href=\"javascript:void(0)\" class=\"ouverture\" >PDF</a><hr><div>";
						}
						$image = "../img/icones/pdf.png"; 
						$code_css = "pdf" . $ligne->id_media;
						$lien = "../img/medias/" . $ligne->titre_media . ".pdf";

						$code_insertion = "[" . $ligne->id_media . "]";  
						$liste_medias .= "<div style=\"background-image:url(" . $image . "?" . code_image(4) . ")
						\">\n<img src=\"" . $image . "\" alt=\"\" />\n";      
						$liste_medias .= "<p title=\"\" id=\"" . $ligne->titre_media . '#_#' . $ligne->alt_media . "\" >" . $code_insertion . "</p>\n</div>\n";
						$k++;
					}        
/*************************
	3 : il s'agit d'un fichier mp3
***********************/
					else if($ligne->fichier_media == "mp3")
					{
						if($l == 0)
						{
							if(($j != 0 && $k == 0) || $k != 0) $liste_medias .= "</div></div>\n<div class=\"type_media\" ><a href=\"javascript:void(0)\" class=\"ouverture\" >Musique</a><hr><div>";
							else $liste_medias .= "<div class=\"type_media\" ><a href=\"javascript:void(0)\" class=\"ouverture\" >Musique</a><hr><div>";
						}
						$liste_medias .= "<div><div class=\"audiojs\" ><audio src=\"../img/medias/" . $ligne->titre_media . ".mp3\" preload=\"auto\" /></div>";
						$liste_medias .= "<p title=\"\" id=\"" . $ligne->titre_media . '#_#' . $ligne->alt_media . "\" style=\"margin-top:45px\" >[" . $ligne->id_media . "]</p>\n</div>\n";
						$l++;
					}
/*************************
	4 : il s'agit d'une video
***********************/
					else
					{
						if($m == 0)
						{
							if(($j != 0 && $k == 0 && $l == 0) || ($k != 0 && $l == 0) || $l != 0) $liste_medias .= "</div></div>\n<div class=\"type_media\" ><a href=\"javascript:void(0)\" class=\"ouverture\" >Videos</a><hr><div>";
							else $liste_medias .= "<div class=\"type_media\" ><a href=\"javascript:void(0)\" class=\"ouverture\" >Videos</a><hr><div>";
						}
						$liste_medias .= "<div class=\"video\" id=\"video_" . $ligne->id_media . "\" >\n";
						$media = video($ligne->lien_media);
						$code_css = "mov" . $ligne->id_media;
						$liste_medias .= "<iframe src=\"http://www.youtube.com/embed/" . $media . "?wmode=transparent\" frameborder=\"0\" ></iframe>";
						$liste_medias .= "<p title=\"\" id=\"" . $ligne->titre_media . '#_#' . $ligne->alt_media . "\" >[" . $ligne->id_media . "]</p>\n</div>\n";
						$m++;
					}
					$i++;
				}
				
				if($j + $k + $l + $m > 0) $liste_medias .= "</div></div>\n";
				else $liste_medias .= "</div>\n";
				
				$liste_medias .= "</div>\n";

				return $liste_medias;

			break;

			case "droite":

				$liste_medias = "<table class=\"tableau_resultat\" cellspacing=\"0\">\n";  
				while($ligne = $sql->fetch())
				{
					if(isset($_SESSION['id_media']) AND $ligne->id_media == $_SESSION['id_media']) $style = "id=\"ligne_coloree\"";  
					else$style = "";
					
					if($ligne->fichier_media != "" && $ligne->fichier_media != "mp3")
					{
						if($ligne->fichier_media != "pdf")// cas des images
						{
							$media = "<img class=\"apercu_image\" src=\"../img/medias/" . $ligne->titre_media . "." . $ligne->fichier_media ."\" alt=\"\" />";
							
							if($ligne->slide == "oui") $slide = "<img class=\"ok\" src=\"../img/icones/ok.png\" alt=\"\" />";
							else $slide = "<img class=\"ok\" src=\"../img/icones/pas_ok.png\" alt=\"\" />";

							if($ligne->press_book == "oui") $press_book = "<img class=\"ok\" src=\"../img/icones/ok.png\" alt=\"\" />";
							else	$press_book = "<img class=\"ok\" class=\"pas_ok\" src=\"../img/icones/pas_ok.png\" alt=\"\" />";
						}
						else // cas des documents pdf
						{
							$media = "<a href=\"../img/medias/" . $ligne->titre_media . ".pdf\" target=\"_blank\"><img class=\"apercu_image\" src=\"../img/icones/pdf.png\" alt=\"Document pdf\" /></a>";          
							$slide = "NC";
							$press_book = "NC";
						}
						$extension = $ligne->fichier_media;
					}
					else if($ligne->fichier_media == "mp3")//cas des mp3
					{
						$media = "<a href=\"../img/medias/" . $ligne->titre_media . ".mp3\" target=\"_blank\"><img class=\"apercu_image\" src=\"../img/icones/music.png\" alt=\"Morceau de musique\" /></a>";          
						$slide = "NC";
						$press_book = "NC";
					}
					else //cas des vidéos
					{
						$media = "<a href=\"" . $ligne->lien_media . "\" target=\"_blank\"><img class=\"apercu_image\" src=\"../img/icones/audio_video.png\" alt=\"Vidéo\" /></a>";          
						$extension = "non";
						$slide = "NC";
						$press_book = "NC";
					}

					$liste_medias .= "<tr " . $style . ">\n";
					$liste_medias .= "<td>" . $ligne->titre_media ."</td>\n";
					$liste_medias .= "<td style=\"padding:0;text-align:center\">[" . $ligne->id_media ."]</td>\n";        
					$liste_medias .= "<td style=\"text-align:center\">" . $media . "</td>\n";
					$liste_medias .= "<td>Slide : " . $slide ."</td>\n";
					$liste_medias .= "<td>Galerie : " . $press_book ."</td>\n";
					$liste_medias .= "<td>\n";
					$liste_medias .= "<a href=\"admin.php?action=modifier_medias&amp;id_media=" . $ligne->id_media . "\"><img class=\"modifier\" src=\"../img/icones/modifier.png\" alt=\"\" title=\"modifier\" /></a>\n";
					$liste_medias .= "<a href=\"admin.php?action=supprimer_medias&amp;id_media=" . $ligne->id_media . "&amp;extension=" . $extension . "\"><img class=\"supprimer\" src=\"../img/icones/supprimer.png\" alt=\"\" title=\"supprimer\" /></a>\n";     
					$liste_medias .= "</td>\n"; 
					$liste_medias .= "</tr>\n";      
				}      
				$liste_medias .= "</table>\n";
				return $liste_medias; 

			break;
		}
	}
/*************************

	afficher les messages
	
***********************/
	function afficher_messages($requete, $connexion)
	{
		$sql = $connexion->query($requete);
		$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
		$liste_messages = "<table class=\"tableau_resultat\" cellspacing=\"0\">\n";
		$liste_messages .= "<tr>\n<td style=\"width:25%\">DATE</td>\n<td style=\"width:25%\">NOM</td>\n<td colspan=\"2\" style=\"width:50%;text-align:left;padding-left:20px\">OBJET</td>\n</tr>";

		while($ligne = $sql->fetch())
		{
			if(isset($_SESSION['id_contact']) AND $ligne->id_contact  ==  $_SESSION['id_contact']) $style = "id=\"ligne_coloree\""; 
			else $style = "style=\"font-weight:bold\"";

			if($ligne->rep  ==  1)
			{
				$liste_messages .= "<tr style=\"color:gray;\" class=\"deroul\"" . $style . ">\n"; 
				$liste_messages .= "<td>Répondu par : " . $ligne->prenom . "<br />" . $ligne->date_contact . "</td>\n<td style=\"overflow:hidden;\">" . $ligne->prenom_contact . " " . $ligne->nom_contact . "</td>\n<td style=\"width:40%\">" . stripslashes($ligne->objet) . "</td>\n";        //permet de supprimer l'antislash qui gère apostrophe en bdd    
				$liste_messages .= "<td>";
				$liste_messages .= "<a href=\"admin.php?action=supprimer_contact&amp;id_contact=" . $ligne->id_contact . "\"><img src=\"../img/icones/supprimer.png\" title=\"supprimer\" alt=\"\" /></a>";
				$liste_messages .= "</td>\n";      
				$liste_messages .= "</tr>\n";
				$liste_messages .= "<tr style=\"background-color:rgb(200,200,200);\">\n";
				$liste_messages .= "<td colspan=\"4\">\n<div id=\"contenu_message\" class=\"cache\">\n<p style=\"margin-bottom:10px;\">Réponse :<br />" . nl2br(htmlspecialchars($ligne->message)) . "</p><br />\n<p>mail du contact : <a href=\"mailto:" . $ligne->mel_contact . "\" style=\"text-decoration:underline;color:#9999ff;\">" . $ligne->mel_contact . "</a></p>\n</div>\n</td>\n";
				$liste_messages .= "</tr>\n";
			}
			else
			{
				$liste_messages .= "<tr class=\"deroul\"" . $style . ">\n"; 
				$liste_messages .= "<td>" . $ligne->date_contact . "</td>\n<td style=\"overflow:hidden;\">" . $ligne->prenom_contact . " " . $ligne->nom_contact . "</td>\n<td>" . stripslashes($ligne->objet) . "</td>\n";
				$liste_messages .= "<td>";
				$liste_messages .= "<a href=\"admin.php?action=repondre_contact&amp;id_contact=" . $ligne->id_contact . "\"><img src=\"../img/icones/repondre.png\" title=\"repondre\" alt=\"\" /></a>\n";
				$liste_messages .= "<a href=\"admin.php?action=supprimer_contact&amp;id_contact=" . $ligne->id_contact . "\"><img src=\"../img/icones/supprimer.png\" title=\"supprimer\" alt=\"\" /></a>";
				$liste_messages .= "</td>\n";      
				$liste_messages .= "</tr>\n";
				$liste_messages .= "<tr style=\"background-color:rgb(200,200,200);\"><td colspan=\"4\"><div id=\"contenu_message\" class=\"cache\"><p style=\"margin-bottom:10px;\">mail du contact : <a href=\"mailto:" . $ligne->mel_contact . "\" style=\"text-decoration:underline;color:#9999ff;\">" . $ligne->mel_contact . "</a></p>\n<p>" . nl2br(htmlspecialchars($ligne->message)) . "</p></div></td></tr>";
			}
		}
		$liste_messages .= "</table>\n";  
		return $liste_messages;   
	}
/*************************

	affichage des syndications (rss)
	
***********************/
	function afficher_syndications($requete,$connexion)
	{
		$sql = $connexion->query($requete);
		$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
		
		$syndications = "<table class=\"tableau_resultat\" cellspacing=\"0\">\n";
		$syndications .= "<tr class=\"tr\">\n";   
		$syndications .= "<th>Affichage</th>\n";  
		$syndications .= "<th>Titre syndication</th>\n";     
		$syndications .= "<th>Adresse syndication</th>\n";
		$syndications .= "<th>Nombre lignes</th>\n"; 
		$syndications .= "<th>Actions</th>\n";  
		$syndications .= "</tr>\n";  
		$i = 0;
		while($ligne = $sql->fetch())
		{
			$style = "";
			$img = "pas_ok"; 
			$class = "";
			
			if(isset($_SESSION['id_syndication']) AND $ligne->id_syndication == $_SESSION['id_syndication']) $style=" id=\"ligne_coloree\""; 

			$syndications .= "<tr" . $style . ">\n";
			
			if($ligne->affiche  ==  "oui")
			{
				$img = "ok";
				$class = " affichee"; 
			}
			
			$syndications .= "<td id=\"syndication-" . $ligne->id_syndication . "\" class=\"center affiche_syndication" . $class . "\"><img class=\"ok\" src=\"../img/icones/" . $img . ".png\" /></td>\n";
			$syndications .= "<td class=\"center couleur\">" . stripslashes($ligne->titre_syndication) . "</td>\n";             
			$syndications .= "<td class=\"center\">" . stripslashes($ligne->url_syndication) . "</td>\n"; 
			$syndications .= "<td class=\"center\">" . $ligne->nombre . "</td>\n";         
			$syndications .= "<td class=\"td_action_s\">                
			<a href=\"admin.php?action=modifier_syndications&id_syndication=" . $ligne->id_syndication . "\"><img class=\"modifier\" src=\"../img/icones/modifier.png\" title=\"modifier\" alt=\"\" /></a>
			<a href=\"admin.php?action=supprimer_syndications&id_syndication=" . $ligne->id_syndication . "\"><img class=\"supprimer\" src=\"../img/icones/supprimer.png\" title=\"supprimer\" alt=\"\" /></a>
			</td>\n"; 
			$syndications .= "</tr>\n"; 
			$i++;      
		}     
		return $syndications; 
	}
/*************************

	affichage des resultats de recherche
	
***********************/
	function afficher_resultats_recherche($requete,$connexion)
	{
		$sql = $connexion->query($requete);
		$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
		$liste_resultats = "<ul id=\"resultats_recherche\">\n";
		while($ligne = $sql->fetch())
		{
			$liste_resultats .= "<li>\n<a href=\"global.php?action=page&id_page=" . $ligne->id_page . "\">" 
			. $ligne->rubrique . " - "
			. $ligne->titre_page . " ("
			. $ligne->date_page . ")</a>\n</li>\n";
		}
		$liste_resultats .= "</ul>\n";

		return $liste_resultats;
	}  
/*************************

	affichage des albums
	
***********************/
	function afficher_albums($requete, $connexion)
	{
		$sql = $connexion->query($requete);
		$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
		$liste_albums = "";
		
		$i = 0;
		while($ligne = $sql->fetch())
		{
			if(isset($_SESSION['id_album']) AND $ligne->id_album == $_SESSION['id_album']) $style = " id=\"ligne_colore\""; 
			else $style="";

			$liste_albums .= "<div" . $style . " class=\"album\">\n"; 
			$liste_albums .= "
			<div class=\"nav_album\" >\n
			<a href=\"admin.php?action=modifier_albums&amp;id_langue=" . $ligne->id_langue . "&amp;id_album=" . $ligne->id_album . "\"><img class=\"modifier\" src=\"../img/icones/modifier.png\" title=\"modifier\" alt=\"\" /></a>\n
			<a href=\"admin.php?action=supprimer_albums&amp;id_langue=" . $ligne->id_langue . "&amp;id_album=" . $ligne->id_album . "\"><img class=\"supprimer\" src=\"../img/icones/supprimer.png\" title=\"supprimer\" alt=\"\" /></a>\n
			</div>"; 
			$liste_albums .= "<h2>" . stripslashes($ligne->titre_album) . "</h2>\n  
			<p>(" . $ligne->langue . ")</p>\n";        
			$liste_albums .= "</div>\n"; 
			$i++;
		}
		return $liste_albums;   
	}


?>