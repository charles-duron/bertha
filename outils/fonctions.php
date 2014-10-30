<?php
/************************************************************************************************************************







	CONNEXION
	
	
	
	
	
	
	
***********************************************************************************************************************/
/*************************

	connexion à la base de données
	
***********************/
	function connexion()
	{
		require_once("connect.php");
		$connexion = new PDO('mysql:host=' . SERVEUR . ';dbname=' . BASE , LOGIN , PASSE);
		/*$connexion=mysql_pconnect (SERVEUR, LOGIN, PASSE);
		if (! $connexion)
		{
			$message="impossible de se connecter à la base de données\n";
			echo $message;
			exit;
		}
		elseif (! mysql_select_db(BASE, $connexion))
		{
			$message="impossible d'accéder à la base\n";
			echo $message;
			exit;
		}
		else
		{
			mysql_select_db(BASE);
		}*/
		return $connexion;
	}
/*************************

	log in
	
***********************/
	function verifUser($login,$password)
	{
		$connexion = connexion();
		$sql = $connexion->query("SELECT count(*) FROM comptes WHERE login= '" . $login . "' AND pass=PASSWORD('" . $password . "')");
		$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
		$nb_connexion = $sql->fetchColumn();

		if($nb_connexion == 0)
		{
			return false;
		}
		else
		{
			$sql = $connexion->query("SELECT * FROM comptes WHERE login= '" . $login . "' AND pass=PASSWORD('" . $password . "')");
			$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
			$ligne_connexion = $sql->fetch();
			$_SESSION['id_acces'] = $ligne_connexion->id_compte;
			$_SESSION['prenom'] = $ligne_connexion->prenom;    
			$_SESSION['nom'] = $ligne_connexion->nom; 
			$_SESSION['statut'] = $ligne_connexion->statut; 
			$_SESSION['photo_connecte'] = "<img id=\"image_connect\" src=\"../img/medias/profil" . $ligne_connexion->id_compte . "." . $ligne_connexion->fichier_compte . "\" alt=\"\" />\n";
			
			return true;
		}
		$connexion = null;
	}
/************************************************************************************************************************







	GESTION DES CHAINES DE CARACTERES
	
	
	
	
	
	
	
***********************************************************************************************************************/
/*************************

	remplacement des caractères spéciaux
	
***********************/
	function remplacer_caracteres_speciaux($string)   
	{
		$tab_car_a_remplacer = array("'",",",";",".","_","-",":","\"","#","!","?","\/","+","*","=","<",">","$","%","&","\\","@");//on vire la ponctuation
		$tab_car_de_remplacement = array("");	
		$string= str_replace($tab_car_a_remplacer,$tab_car_de_remplacement,$string);          
		return $string;
	}
/*************************

	remplacement des espaces
	
***********************/
	function remplacer_espaces($string)   
	{
		$tab_car_a_remplacer = array(" ");
		$tab_car_de_remplacement = array("-");	
		$string= str_replace($tab_car_a_remplacer,$tab_car_de_remplacement,$string);          
		return $string;    
	}
/*************************

	remise des espaces
	
***********************/
	function remettre_espaces($string)   
	{
		$tab_car_de_remplacement = array(" ");
		$tab_car_a_remplacer = array("-");	
		$string= str_replace($tab_car_a_remplacer,$tab_car_de_remplacement,$string);          
		return $string;    
	}
/*************************

	remplacement des accents
	
***********************/
	function remplacer_accents($string)   
	{
		$accent=array("é","è","ê","à","â","ô","ù","û","î","ï","ç");
		$sans_accent=array("e","e","e","a","a","o","u","u","i","i","c");
		$string= str_replace($accent,$sans_accent,$string);          
		return $string;    
	}
/*************************

	remplacement des caracteres speciaux par des caractères ascii
	
***********************/
	function remplacer_caracteres_speciaux_par_ascii($string)   
	{
		$accent=array("é","è","ê","ë","à","â","ô","ù","û","î","ï","ç","’");
		$sans_accent=array("&eacute;","&egrave;","&ecirc;","&euml;","&agrave;","&acirc;","&ocirc;","&ugrave;","&ucirc;","&icirc;","&iuml;","&ccedil;","&rsquo;");
		$string= str_replace($accent,$sans_accent,$string);          
		return $string;    
	}
/*************************

	remplacement des caracteres ascii par des caractères non accentués
	
***********************/
	function remplacer_caracteres_ascii_par_non_accentues($string)   
	{
		$accent=array("&eacute;","&egrave;","&ecirc;","&euml;","&agrave;","&acirc;","&ocirc;","&ugrave;","&ucirc;","&icirc;","&iuml;","&ccedil;","&rsquo;");
		$sans_accent=array("e","e","e","e","a","a","o","u","u","i","i","c","-");
		$string= str_replace($accent,$sans_accent,$string);          
		return $string;    
	}
/*************************

	remplacement des caracteres ascii par des caractères accentués
	
***********************/
	function remplacer_caracteres_ascii_par_accentues($string)   
	{
		$accent=array("&eacute;","&egrave;","&ecirc;","&euml;","&agrave;","&acirc;","&ocirc;","&ugrave;","&ucirc;","&icirc;","&iuml;","&ccedil;","&rsquo;");
		$sans_accent=array("é","è","ê","ë","à","â","ô","ù","û","î","ï","ç","'");
		$string= str_replace($accent,$sans_accent,$string);          
		return $string;    
	}
/*************************

	remplacement des caractères de l'url pour les rendre compatibles
	
***********************/
	function format_url($chaine, $separateur)
	{	
		$chaine = remplacer_caracteres_ascii_par_non_accentues($chaine);//on vire les caractères ascii

		$tab_car_a_remplacer = array("'",",",";",".",":","\"","!","?","/","+","*","=","<",">","$","%","&","\\","@");//on vire la ponctuation
		$tab_car_de_remplacement = array("");	
		$chaine = str_replace($tab_car_a_remplacer,$tab_car_de_remplacement,$chaine);
		
		$chaine = remplacer_accents($chaine);//on vire les accents
		$chaine = remplacer_caracteres_speciaux($chaine);//on vire les caractères spéciaux
		$chaine = strtolower($chaine);//on ramène en minuscules
		$chaine = str_replace(' ',$separateur,$chaine);	//on choisit un caractre de remplacement pour les espaces
		$chaine = str_replace($separateur.$separateur,$separateur,$chaine);
		
		return $chaine;
	}
/*************************

	formatage de chaine de caractère pour insertion en base
	
***********************/
	function format_bdd($chaine)
	{	
		$chaine = remplacer_caracteres_speciaux_par_ascii($chaine);	//on vire les caractères accents
		$chaine = strtolower($chaine);	//on passe tout en minuscule
		
		return $chaine;
	}
/*************************

	troncature d'une chaîne de caractères
	
***********************/
	function troncature_chaine($chaine, $nombre_caractères)
	{	
	
		if (strlen($chaine) > $nombre_caractères)
		{
			$chaine = substr($chaine, 0, $nombre_caractères);	//coupe une chaine de caractères (chaine, caractère de départ, longueur de la troncature)
			$last_space = strrpos($chaine, " ");	//on recherche la dernière occurrence du caractère "espace"
			$chaine = substr($chaine, 0, $last_space)."...";	//et on recoupe pour finir propre
		}		
		
		return $chaine;
	}
/************************************************************************************************************************







	DIVERS
	
	
	
	
	
	
	
***********************************************************************************************************************/
/*************************

	calcul des dates
	
***********************/
	function time_ago($date)
	{
		if(empty($date))  return "Aucune date";

		$periods = array("seconde", "minute", "heure", "jour", "semaine", "mois", "an", "décennie");
		$lengths = array("60","60","24","7","4.35","12","10");

		$now = time();
		$unix_date = @strtotime($date);

		if(empty($unix_date))  return "Format date incorrect";

		if($now > $unix_date) 
		{
			$difference = $now - $unix_date;
			$tense = "Il y a";  

			for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) 
			{
				$difference /= $lengths[$j];
			}
			$difference = round($difference);

			if($difference != 1) 
			{
				if($periods[$j] != "mois") $periods[$j].= "s";
			}

			if($periods[$j] == "secondes" || $periods[$j] == "seconde") return "Moins d'une minute";
			else return "{$tense} $difference $periods[$j]";

			} 
		else return "A l'instant";
	} 
/*************************

	gestion du format des dates
	
***********************/
	function format_date($date,$format)
	{
		if($format=="anglais")
		{
			$tab_date=explode("/",$date);
			$date_au_format = $tab_date[2] . "-" . $tab_date[1] . "-" . $tab_date[0];	
		}
		if($format == "francais")
		{
			$tab_date = explode("-",$date);
			$date_au_format = $tab_date[2] . "/" . $tab_date[1] . "/" . $tab_date[0];	
		}
		return $date_au_format;	
	}
/*************************

	generation des messages de contrôle des champs
	
***********************/
	function champs($nb, $cas)
	{
		if($cas == "debut")
		{
			$msg = "Le champ ";
			if($nb > 1) $msg="Les champs ";
		}   
		if($cas == "fin")
		{
			$msg = " est requis.";      
			if($nb > 1) $msg = " sont requis.";
		} 
		return $msg;
	}
/*************************

	detection de l'extension de fichier
	
***********************/
	function fichier_type($uploadedFile)
	{
		$tabType = explode(".", $uploadedFile);
		$nb = sizeof($tabType)-1;
		$typeFichier = $tabType[$nb];
		
		if($typeFichier == "jpeg") $typeFichier = "jpg";

		$extension = strtolower($typeFichier);
		
		return $extension;
	}
/*************************

	generation d'un code aleatoire pour les images
	
***********************/
	function code_image($nb) 
	{
		$string = "";
		$chaine = "0123456789";
		srand((double)microtime()*1000000);
		for($i = 0; $i < $nb; $i++) 
		{
			$string .= $chaine[rand()%strlen($chaine)];
		}
		return $string;
	}
/*************************

	gestion des url des videos
	
***********************/
	function video($url)
	{
		if(stristr($url, "=") == true) 
		{ 
			$video_explose = explode("=", $url); 
			$id_video = $video_explose[1];    
		}
		elseif(stristr($url, "/") == true)
		{
			$video_explose = explode("/", $url); 
			$id_video = $video_explose[3];  
		}   
		else $id_video = $url;

		return $id_video;     
	}
/*************************

	envoi de mail
	
***********************/
	function envoi_mel($destinataire,$sujet,$corps,$expediteur, $type)
	{ 
		$sujet = '=?UTF-8?B?'.base64_encode($sujet).'?=';	// on traite les données pour envoyer en utf8 le mail
		$corps = stripslashes($corps); //permet d'enlever d’éventuels "\" restant dans le corps du texte
	
		$headers = array();	// Entêtes du message
		$headers[] = 'From: '. $expediteur;
		$headers[] = 'Content-Type: text/' . $type . '; charset=UTF-8; format=flowed;';
		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-Transfer-Encoding: 8bit';
		$headers = join("\n", $headers);

		// Envoi du mail
		mail($destinataire, $sujet, $corps, $headers);    
	}
/*************************

	géneration du flux rss
	
***********************/
function generer_flux_rss($requete,$connexion, $title_flux, $description_flux)
{
$sql = $connexion->query($requete);
$flux_rss="<rss version=\"2.0\" xmlns:content=\"http://purl.org/rss/1.0/module/content/\" xmlns:atom=\"http://w3.org/2005/Atom\">\n";
$flux_rss.="<channel>\n";
$flux_rss.="<title>" . $title_flux . "</title>\n";
$flux_rss.="<description>" . $description_flux . "</description>\n";
$flux_rss.="<image>\n";

$lien_flux=dirname($_SERVER['SERVER_PROTOCOL']) . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; 
$tab_image_flux=explode("/",$lien_flux);
$image_flux=$tab_image_flux[0] . "/" . $tab_image_flux[1] . "/" . $tab_image_flux[2] . "/"  . $tab_image_flux[3] . "/" . $_SESSION['image_flux'];

// pour l'url il faudra passer en adresse absolue quand le site sera hébergé sur serveur distant
$flux_rss.="<url>" . $image_flux . "</url>\n";
$flux_rss.="<title>" . $title_flux . "</title>\n";
$flux_rss.="<link>" . $lien_flux . "</link>\n";
$flux_rss.="<width>90</width>\n"; 
$flux_rss.="<height>90</height>\n"; 
$flux_rss.="<description>" . $description_flux . "</description>\n";      
$flux_rss.="</image>\n";           
while($ligne = $sql->fetch())
     {
     $flux_rss.="<item>\n";
       $flux_rss.="<title>" . $ligne->titre_actu . "</title>\n";
       $flux_rss.="<link>" . $lien_flux . "</link>\n";
       $flux_rss.="<guid>" . $lien_flux . "</guid>\n";
       $flux_rss.="<description>" . str_replace("&","&amp;",$ligne->contenu_actu) . "</description>\n";
       
       $date_flux=date("r",strtotime($ligne->date_debut_actu));
       $flux_rss.="<pubDate>" . $date_flux . "</pubDate>\n";
     $flux_rss.="</item>\n";
     }
$flux_rss.="</channel>\n";
$flux_rss.="</rss>\n";           

return $flux_rss; 
}
/*************************

	lecture des fichiers xml
	
***********************/
	function lit_xml($fichier,$item,$champs,$nb) {
		// on lit le fichier
		if($chaine = @implode("",@file($fichier)))
		{
			// on explode sur <item>
			$tmp = preg_split("/<\/?".$item.">/",$chaine);

			if($nb == 0) $nb = sizeof($tmp);
			else $nb = round($nb*2);
			
			// pour chaque <item> donc les nb premiers profils
			for($i = 1;$i < $nb;$i += 2)       
			{
				// on lit les champs demandés <champ> donc il s'agit de 'id' et 'prenom'
				foreach($champs as $champ)
				{
					$tmp2 = preg_split("/<\/?".$champ.">/",$tmp[$i]);
					// on ajoute l'élément au tableau
					$tmp3[$i-1][] = @$tmp2[1];
				}
			}
			// et on retourne le tableau dans la fonction
			return $tmp3;
		} 
	}     
/*************************

	pour le module calendrier
	
***********************/
function get_selected($temps, $i)
{
    $selected = "";
    if ($temps == $i) $selected = " selected=\"selected\"";
    return $selected;
}
/* Renvoie une string représentant l'appel à une classe CSS
 *
 * Pour les valeurs par défaut :
 *      - 1 : ' class="aut"'
 *      - 2 : ''
 *
 * @param   integer     $jour       Le jour en cours
 * @param   integer     $index      La valeur par défaut de la string
 * @return  string                  La string nécessaire pour appeller la classe CSS voulue
 */
	function get_classe($jour, $index, $mode)
	{
		switch ($index)
		{
			case 1:
				$classe = " class=\"aut\"";
			break;
			
			default:
			$classe = "";
		}
		
		switch ($mode)
		{
			case "en":
			$x1 = 0;
			$x2 = 6;
			break;
			
			default:
			$x1 = 6;
			$x2 = 5;
		}
		
		if ($jour == $x1) $classe = " class=\"dim\"";
		elseif ($jour == $x2) $classe = " class=\"sam\"";

		return $classe;
	}
	/* Détermine si on est sur un dimanche ou un samedi, à partir du 1er du mois
	 *
	 * @param   array       $ajd            Le jour, mois et année de maintenant
	 * @param   integer     $annee          L'année en cours
	 * @param   integer     $mois           Le mois en cours
	 * @param   integer     $jour           Le jour en cours
	 * @param   integer     $cptJour        Le numéro du jour en cours de la semaine
	 * @param   integer     $premierJour    Le numéro du 1er jour (dans la semaine) du mois
	 * @param   array       $nomj           Le tableau des noms des jours
	 * @param   integer     $prems          Le numéro du dernier jour de la semaine du mois précédent
	 * @param   string      $mode           Le mode d'affichage du calendrier ("fr" ou "en")
	 * @return  string                      La string nécessaire pour appeller la classe CSS voulue
	 */
	function get_classeJour($ajd, $annee, $mois, $jour, $cptJour, $premierJour, $nomj, $prems, $mode)
	{
		$classe = "";
		if ($mode == "en")
		{
			if (($cptJour == 0 && $jour > 1) || ($jour == 1 && $premierJour == 0))		$classe = " class=\"dim\"";
			elseif ($cptJour == 6 || (count($nomj) - $jour == $prems))		$classe = " class=\"sam\"";
		}
		else
		{
			if ($cptJour == 6 || (count($nomj) - $jour == $prems))		$classe = " class=\"dim\"";
			else if ($cptJour == 5 || (count($nomj) - $jour - 1 == $prems))		$classe = " class=\"sam\"";
		}
		
		if ($jour == $ajd[0] && $mois == $ajd[1] && $annee == $ajd[2])		$classe = " class=\"ajd\"";

		return $classe;
	}
	/* Détermine si on est sur un samedi, lorsqu'on complète le tableau
	 *
	 * @param   integer     $i              Le jour en cours
	 * @param   integer     $cptJour        Le numéro du dernier jour (dans la semaine) du mois
	 * @param   string      $mode           Le mode d'affichage du calendrier ("fr" ou "en")
	 * @return  string                      La string nécessaire pour appeller la classe CSS voulue
	 */
	 
	function get_classeJourReste($i, $cptJour, $mode)
	{
		$classe = "";
		if ($mode == "en")
		{
			if ($i == (7 - $cptJour) - 1) $classe = " class=\"sam\"";
		}
		else
		{
			if ($i == (6 - $cptJour) - 1) $classe = " class=\"sam\"";
			else if ($i == (7 - $cptJour) - 1) $classe = " class=\"dim\"";
		}
		return $classe;
	}
/*************************

	redimensionnement image
	
***********************/
	function redimage($img_src,$img_dest,$dst_w,$dst_h)
	{  
		$extension = fichier_type($img_src);
		$size = @GetImageSize($img_src);	// Lecture les dimensions de l'image
		$src_w = $size[0];
		$src_h = $size[1];
		$dst_im = @ImageCreatetruecolor($dst_w,$dst_h);	// Création d'une image vierge aux bonnes dimensions truecolor
		/*************************
			On y copie l'image initiale redimensionnée
		***********************/
		if($extension == "jpg")
		{
			$src_im = @ImageCreateFromJpeg($img_src);
			@ImageCopyResized($dst_im,$src_im,0,0,0,0,$dst_w,$dst_h,$src_w,$src_h);
			@ImageJpeg($dst_im,$img_dest,100);	// Sauve la nouvelle image
		}
		if($extension == "png")
		{
			$src_im = @ImageCreateFromPng($img_src);
			@ImageCopyResized($dst_im,$src_im,0,0,0,0,$dst_w,$dst_h,$src_w,$src_h);
			@ImageJpeg($dst_im,$img_dest,100);	// Sauve la nouvelle image
		}     
		if($extension == "gif")
		{
			$src_im = @ImageCreateFromGif($img_src);
			@ImageCopyResized($dst_im,$src_im,0,0,0,0,$dst_w,$dst_h,$src_w,$src_h);
			@ImageJpeg($dst_im,$img_dest,100);	// Sauve la nouvelle image
		}
		/*************************
			destruction des tampons
		***********************/
		@ImageDestroy($dst_im);
		@ImageDestroy($src_im);
	} 
/************************************************************************************************************************







	AFFICHAGE
	
	
	
	
	
	
	
***********************************************************************************************************************/
/**************************************************



	FRONT


	
************************************************/
/*************************

	affichage du menu
	
***********************/
	function afficher_menu()
	{		
		if(!isset($_GET['page']))
		{
			$_GET['page'] = 'accueil';
		}
		
		$connexion = connexion();
		$pages = array();
		$noms_parent = array();
		$sous_pages = array();
		
		$requete_rubriques = "SELECT * FROM rubriques ORDER BY niveau, rang";	//récupération des rubriques
        $resultat = mysql_query($requete_rubriques,$connexion);
		
		while($ligne = mysql_fetch_object($resultat))	//stockage des rub dans un tableau
		{
			if($ligne->niveau == 1 && $ligne->nom_rubrique != 'accueil')	//niveau 1
			{
				array_push($pages, $ligne->nom_rubrique);
			}
			elseif($ligne->niveau != 1 && $ligne->nom_rubrique != 'accueil')	//niveau 2
			{
				array_push($id_parent,$ligne->id_parent);
				array_push($sous_pages, $ligne->nom_rubrique);
			}
		}
		
		$nb_menu=count($pages);
		$nb_sous_menu=count($sous_pages);
		$i = 0;
		
		$menu = "<div id=\"menu\" class=\"ferme\" >\n";

		while($i < $nb_menu)	//on fait le tour des éléments de lvl 1 et on les affiche... S'ils ont des sous-éléments, on annullera l'action de leur lien
		{
			$nom_page = remettre_espaces($pages[$i]);
			$url_page = format_url($pages[$i], '-');
			$j = 0;
			$k = 0;
			
			$menu .= "<div id=\"" . $url_page . "\" class=\"niveau_1\" >\n";
			
			while($j < $nb_sous_menu)	//on fait le tour des éléments de lvl 2 pour voir s'ils sont des sous-éléments du lien en cours de construction
			{
				if($pages[$i] == $id_parent[$j])
				{
					if($k == 0)	//on a trouvé un premier enfant : on termine le lien en annullant son action
					{
						$menu .= "<a href=\"javascript:void(0)\" class=\"lien_principal deselectionne\" >" . $nom_page . "</a>\n";
					}
					
					$nom_sous_page = remettre_espaces($sous_pages[$j]);
					$url_sous_page = format_url($sous_pages[$j], '-');
					
					$menu .= "<a href=\"global.php?page=" . $url_sous_page . "\" class=\"niveau_2\" >" . $nom_sous_page . "</a>\n";
					$k++;
				}
				$j++;				
			}

			if($k == 0)	//on n'a trouvé aucun enfant : on termine le lien en précisant la destination
			{
				$menu .= "<a href=\"global.php?page=" . $url_page . "\" class=\"lien_principal deselectionne\" >" . $nom_page . "</a>\n";
			}
			
			$menu .= "</div>\n";
			$i++;
		}
		
		$menu .= "</div>\n";
		
		return $menu;
		$connexion = null;
	}
/*************************

	affichage du sitemap
	
***********************/
	function afficher_sitemap()
	{
		$connexion = connexion();
		$pages = array();
		$sous_pages = array();
		
		$requete_rubriques = "SELECT * FROM rubriques ORDER BY niveau, rang";	//récupération des rubriques
        $resultat = mysql_query($requete_rubriques,$connexion);
		
		while($ligne = mysql_fetch_object($resultat))	//stockage dans un tableau
		{		
			if($ligne->niveau == 1)
			{
				array_push($pages, $ligne->nom_rubrique);
			}
			else
			{
				array_push($sous_pages, $ligne->nom_rubrique);
			}			
		}

		$nb_sitemap = count($pages);
		$sitemap = "
		<h2>Plan du site</h2>\n
		<div id=\"texte_page_full_width\" >\n
			<ul id=\"sitemap\" >\n";
		$i = 0;

		while($i < $nb_sitemap)
		{
			$sitemap .= "<li>\n
										<a href=\"global.php?page=" . remplacer_espaces($pages[$i]) . "\">" . remettre_espaces($pages[$i]) . "</a>\n";
			
			if($pages[$i] == 'realisations')
			{
				$j = 0;
				$nb_sous_menu = count($sous_pages);
				$sitemap .= "<ul class=\"sitemap_niveau_2\">\n";
				
				while($j < $nb_sous_menu)
				{
					$sitemap .= "
					<li>\n
						<a href=\"global.php?page=" . remplacer_espaces($pages[$i]) . "&sous_page=" . remplacer_espaces($sous_pages[$j]) . "\">" . remettre_espaces($sous_pages[$j]) . "</a>\n
					</li>\n";
					$j++;
				}
				
				$sitemap .= "</ul>\n";		
			}
			
			$sitemap .= "</li>\n";
			$i++;
		}

		$sitemap .= "
				<li>\n
					<a href=\"global.php?page=mentions-legales\" >mentions légales</a>\n
				</li>\n
				<li>\n
					<a href=\"global.php?page=sitemap\" >plan du site</a>\n
				</li>\n
				<li>\n
					<a href=\"global.php?page=credits\" >crédits</a>\n
				</li>\n
			</ul>\n
		</div>";
		
		return $sitemap;
		$connexion = null;
	}
/*************************

	affichage du contenu des pages en front
	
***********************/
	function afficher_page($page)
	{
		$connexion = connexion();			
		$contenu_page = "";
			
		if($_GET['page'] != 'news')
		{
			$contenu_page .= remplacer_caracteres_ascii_par_accentues(side_news(2));
		
			$requete = "SELECT contenu_rubrique FROM rubriques WHERE nom_rubrique = '" . $page . "'";	//on récupère le contenu propre à la page
			$resultat = mysql_query($requete, $connexion);		
			$ligne = mysql_fetch_array($resultat);
			
			$contenu_page .= remplacer_caracteres_ascii_par_accentues($ligne['contenu_rubrique']);
		}
		else	//les news ont un contenu spécifique
		{
			if(isset($_GET['id_news']))	//si l'on a sélectionné une news en particulier
			{
				$requete = "SELECT * FROM news WHERE id_news = '" . $_GET['id_news'] . "'";	//on récupère la news demandée
				$resultat = mysql_query($requete, $connexion);		
							
				$ligne = mysql_fetch_array($resultat);

				$contenu_page .= '
				<div id="zone_contenu">
					<h2>' . remplacer_caracteres_ascii_par_accentues($ligne['titre_news']) . '</h2>
					<div>' . remplacer_caracteres_ascii_par_accentues($ligne['contenu_news']) . '</div>
				</div>';
			}
			else
			{
				$requete = "SELECT * FROM news";	//on récupère toutes les news
				$resultat = mysql_query($requete, $connexion);		
							
				while($ligne = mysql_fetch_object($resultat))
				{
					$contenu_page .= '
					<div id="zone_contenu">
						<h2>' . remplacer_caracteres_ascii_par_accentues($ligne->titre_news) . '</h2>
						<div>' . remplacer_caracteres_ascii_par_accentues($ligne->contenu_news) . '</div>
					</div>';
				}
			}
		}
		
		return $contenu_page;	//et on affiche
		$connexion = null;
	}
/*************************

	création des news latérales
	
***********************/
	function side_news($nombre_news)
	{
/*
			$longueur = strrpos($nom, '/');
			$titre0 = substr($nom, $longueur + 1);
			$longueur = strrpos($titre0, '.html');
			$titre = substr($titre0, 0, $longueur);
			$side_news .= '
			<div class="news" id="' . $titre . '">
				<h3><a href="global.php?page=blog#' . $titre . '" >' . remettre_espaces($titre) . '</a></h3>
				<p>';
				include(troncature_chaine($nom, 400));
				$side_news .= '</p>
				<a href="global.php?page=blog#' . $titre . '" class="suite" >lire la suite</a>
			</div>';


		$connexion = connexion();			
		$side_news = '
		<div id="side_news" >
			<h2>Quoi de neuf ?</h2>';
			
		$requete = "
		SELECT * FROM news ORDER BY date_news DESC LIMIT 0, " . $nombre_news . "";	//on récupère les x news les plus récentes
		$resultat = mysql_query($requete, $connexion);
		
		while($ligne = mysql_fetch_object($resultat))
		{
		$side_news .= '
			<div class="news" >
				<h3><a href="global.php?page=news&id_news=' . $ligne->id_news . '" >' . $ligne->titre_news . '</a></h3>
				<p>' . troncature_chaine($ligne->contenu_news, 250) . '</p>
				<a href="global.php?page=news&id_news=' . $ligne->id_news . '" class="suite" >lire la suite +</a>
			</div>';
		}
		$side_news .= '
			<a href="global.php?page=news" id="toutes_les_news" >Toutes les news</a>
		</div>';
		
		return $side_news;	//et on affiche
		$connexion = null;*/
	}
/**************************************************



	BACK


	
************************************************/
/*************************

	affichage du logo en back
	
***********************/
	function afficher_logo()
	{
		$connexion = connexion();
		$logo = '';
		
		$requete = "SELECT logo FROM parametres";	//on récupère le logo
		$resultat = mysql_query($requete, $connexion);		
		$ligne = mysql_fetch_array($resultat);
		
		if($ligne['logo'] != '')
		{
			$logo .= '<a href="../index.php" onclick="window.open(this.href); return false;" ><img src="' . $ligne['logo'] . '"</a>';
		}
		else
		{
			$logo .= '<h1><a href="../index.php" onclick="window.open(this.href); return false;" >BEEW</a><div><a href="index.php" >présence numérique</a></div></h1>';
		}

		return $logo;	//et on affiche
		$connexion = null;
	}
/************************************************************************************************************************







	EDITION DU CONTENU STOCKE EN BASE
	
	
	
	
	
	
	
***********************************************************************************************************************/
/*************************

	ajouter une rubrique ou en modifier le contenu
	
***********************/
	function ajouter_rubriques()
	{
	
		$connexion = connexion();
		
		if(!isset($_GET['id_rubrique']))	//si l'on crée une nouvelle rubrique
		{
			$resultat_test = $connexion->query("
			SELECT nom_rubrique FROM rubriques
			WHERE nom_rubrique = '" . format_bdd($_POST['nom_rubrique']) . "'");	//on vérifie que le nom de rubrique n'est pas déjà pris
			$resultat_test->setFetchMode(PDO::FETCH_OBJ);
			
			$ligne_test = $resultat_test->fetch();

			if($ligne_test->nom_rubrique != '')	//s'il l'est, on en informe l'utilisateur tout en maintenant les champs du formulaire
			{
				$_SESSION['infos'] = 'la rubrique "' . $ligne_test->nom_rubrique . '" existe déjà';
				$_SESSION['couleur_message'] = 'rouge'; 						
				$_SESSION['nom_rubrique'] = $_POST['nom_rubrique'];
				$_SESSION['contenu_rubrique'] = $_POST['contenu_rubrique'];				
				$connexion = null;
				header("Location:admin.php?page=rubriques&affichage=oui");         				
			}
			elseif($_POST['nom_rubrique'] == '')
			{
				$_SESSION['infos'] = 'veuillez choisir une nom de rubrique';
				$_SESSION['couleur_message'] = 'rouge'; 						
				$_SESSION['nom_rubrique'] = $_POST['nom_rubrique'];
				$_SESSION['contenu_rubrique'] = $_POST['contenu_rubrique'];
				$connexion = null;
				header("Location:admin.php?page=rubriques&affichage=oui");         				
			}
			elseif($_POST['rubrique_principale'] == 'non' && (!isset($_POST['nom_parent']) || $_POST['nom_parent'] == ''))
			{
				$_SESSION['infos'] = 'veuillez choisir une rubrique parente';
				$_SESSION['couleur_message'] = 'rouge'; 						
				$_SESSION['nom_rubrique'] = $_POST['nom_rubrique'];
				$_SESSION['contenu_rubrique'] = $_POST['contenu_rubrique'];
				$connexion = null;
				header("Location:admin.php?page=rubriques&affichage=oui");         				
			}
			else	//si le nom est libre et que les conditions sont remplies
			{

				$resultat_rang = $connexion->query("
				SELECT MAX(rang) AS rang 
				FROM rubriques 
				WHERE niveau = " . $_POST['niveau'] . " 
				AND id_parent = '" . $_POST['nom_parent'] . "'");	//on calcule le rang de la rubrique
				
				$resultat_rang->setFetchMode(PDO::FETCH_OBJ);				
				$ligne_rang = $resultat_rang->fetch();
				$rang = $ligne_rang->rang + 1;
				
				$requete = "INSERT INTO rubriques 
				SET nom_rubrique = '" . format_bdd($_POST['nom_rubrique']) . "', 
				contenu_rubrique = '" . $_POST['contenu_rubrique'] . "', 				
				niveau = " . $_POST['niveau'] . ", 
				rang = '" . $rang . "', 
				id_parent = '" . $_POST['nom_parent'] . "'";	//construction de la requete d'insertion en fonction des champs renseignés
				
				$requete_rubriques = $connexion->exec($requete);	//insertion
				
				$_SESSION['infos'] = 'rubrique "' . $_POST['nom_rubrique'] . '" ajoutée';
				$_SESSION['couleur_message'] = 'vert'; 		
				
				$connexion = null;
				header("Location:admin.php?page=rubriques&affichage=oui");
			}
		}
		else	//si l'on modifie une rubrique
		{			
			if(!isset($_POST['nom_rubrique']) || $_POST['nom_rubrique'] == '')	//si l'on a oublié de renseigner le nom de la rubrique, on renvoie
			{			
				$_SESSION['infos'] = 'le nom de la rubrique est vide';
				header("Location:admin.php?page=rubriques&affichage=oui&id_rubrique=" . $_GET['id_rubrique'] . "");
			}
			else	//sinon on éxecute
			{
				/*$resultat_niveau_rubriques = $connexion->query("
				SELECT niveau FROM rubriques 
				WHERE id_rubrique = '" . $_GET['id_rubrique'] . "'");
				$resultat_niveau_rubriques->setFetchMode(PDO::FETCH_OBJ);				
				$ligne_niveau_rubriques = $resultat_niveau_rubriques->fetch();*/

				$resultat_rang = $connexion->query("
				SELECT MAX(rang) AS rang 
				FROM rubriques 
				WHERE niveau = " . $_POST['niveau'] . " 
				AND id_parent = '" . $_POST['nom_parent'] . "'");	//on calcule le rang de la rubrique
				
				$resultat_rang->setFetchMode(PDO::FETCH_OBJ);				
				$ligne_rang = $resultat_rang->fetch();
				$rang = $ligne_rang->rang + 1;
				
				$requete = "
				UPDATE rubriques 
				SET nom_rubrique = '" . format_bdd($_POST['nom_rubrique']) . "', 
				contenu_rubrique = '" . $_POST['contenu_rubrique'] . "',
				niveau = '" . $_POST['niveau'] . "',
				rang = '" . $rang . "', 
				id_parent = '" . $_POST['nom_parent'] . "'
				WHERE id_rubrique = '" . $_GET['id_rubrique'] . "'";
				$resultat_rubriques = $connexion->exec($requete);	//écriture en base des nouveaux éléments
				
/*				$requete = "
				SELECT nom_rubrique FROM rubriques 
				WHERE id_rubrique = '" . $_GET['id_rubrique'] . "'";
				$resultat_nom_rubriques = $connexion->query($requete);
				$resultat_nom_rubriques->setFetchMode(PDO::FETCH_OBJ);				
				$ligne_nom_rubriques = $resultat_nom_rubriques->fetch();	//on va chercher le nom de la rubrique pour rédiger le message*/

				$_SESSION['infos'] = 'rubrique "' . $_POST['nom_rubrique'] . '" modifiée';
				$_SESSION['couleur_message'] = 'vert';

				$connexion = null;
				header("Location:admin.php?page=rubriques&affichage=oui");
			}
		}
	}
/*************************

	modifier l'ordre des rubriques
	
***********************/
	function modifier_ordre_rubriques()
	{
		$tableau = explode(',', $_POST['ordre_rubriques']);
		$i = 0;
		$nb_tableau = count($tableau);
		$requete = "UPDATE rubriques
		SET rang = CASE nom_rubrique";
		$requete .= remplacer_caracteres_speciaux_par_ascii($_POST['ordre_rubriques']);
		$requete .= " END
		WHERE id_parent = " . $_POST['stock_id_parent'] . "";
	
		$connexion = connexion();
		$resultat_rubriques = $connexion->exec($requete);	//écriture en base des nouveaux éléments

		$_SESSION['infos'] = 'rubrique ' . $_POST['nom_rubrique'] . ' modifiée';
		$_SESSION['contenu_rubrique'] = $_POST['contenu_rubrique'];
		$_SESSION['nom_rubrique'] = $_POST['nom_rubrique'];
		$connexion = null;
		header("Location:admin.php?page=rubriques&action_formulaire=afficher&affichage=oui&id_rubrique=" . $_GET['id_rubrique']);
	}
/*************************

	supprimer une rubrique
	
***********************/
	function supprimer_rubriques()
	{
		if(isset($_GET['id_rubrique']))  
		{
			$_SESSION['id_rubrique'] = $_GET['id_rubrique'];
		}

		$connexion = connexion();

		$resultat_nom_rubriques = $connexion->query("
		SELECT nom_rubrique FROM rubriques 
		WHERE id_rubrique = '" . $_SESSION['id_rubrique'] . "'");
		$resultat_nom_rubriques->setFetchMode(PDO::FETCH_OBJ);				
		$ligne_nom_rubriques = $resultat_nom_rubriques->fetch();

		$resultat_suppr_rubriques = $connexion->exec("
		DELETE FROM rubriques 
		WHERE id_rubrique = '" . $_SESSION['id_rubrique'] . "'");
		
/*		$requete_emplacements = "
		DELETE FROM emplacement_image
		WHERE id_rubrique = '" . $_SESSION['id_rubrique'] . "'";
		$resultat_emplacements = mysql_query($requete_emplacements, $connexion);*/
		
		$_SESSION['infos'] = 'rubrique "' . $ligne_nom_rubriques->nom_rubrique . '" supprimée';
		$_SESSION['couleur_message'] = 'rouge'; 		
		
		$connexion = null;
        header("Location:admin.php?page=rubriques&affichage=oui");             

	}/*************************


	
***********************/
/*************************


	
***********************/
/************************************************************************************************************************







	
	
	
	
	
	
	
	
***********************************************************************************************************************/
/*
notes : 
 - debug sql :
		if (!$resultat)
		{
			die('Requête invalide : ' . mysql_error());
		}


*/
?>



