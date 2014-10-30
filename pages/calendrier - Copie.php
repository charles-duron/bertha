<?php

	//include("../outils/fonctions.php");
	$connexion = connexion();
	/**
	* $anneeMin : Permet la selection d'années antérieures à celle actuelle (2 = 2 ans antérieur)
	* $anneeMax : Permet la sélection d'années postérieures à celle actuelle (3 = 3 ans postérieur)
	*/
	$anneeMin = 2;
	$anneeMax = 3;

	/**
	* Formatage de la date (par exemple : JJ-MM-AAAA ou JJ|MM|AAAA)
	* $checkzero : ajoute un zéro devant le mois ou le jour s'ils sont inférieur à 10
	*              "false" ou "true"
	* $format    : représente la string qui sépare le mois du jour de l'année
	* $ordre     : détermine l'ordre, de gauche à droite, du jour, mois et année
	*              "a" pour année, "m" pour mois, "j" pour jour
	* $affichage : Pour présenter le calendrier au format anglais ou français
	*              "fr" = commencer par lundi ou "en" = commencer par dimanche
	*/
	$checkzero = "true";
	$format = "/";
	$ordre = array("j", "m", "a");
	$affichage = "fr";

	/**
	* Affichage du calendrier en popup ou non
	* $popup     : Détermine si le calendrier s'affichera sous forme de popup ou non
	*              TRUE = sous forme de popup ou FALSE = directement intégré à la page
	* $formHtml  : Le nom de la FORM où il y a le champ concerné
	* $champ     : Le nom du champ où la date doit être inscrite
	* $page      : Le nom de la page où est inclu le calendrier
	* $larCal    : La largeur du calendrier
	* $marCal    : Les éventuelles marges à donner au calendrier pour bien le placer dans la page
	*/
	$popup = false;
	$formHtml = "calendrier";
	$champ = "even";
	$page = "global.php";

	/**
	* Ci-dessous, le nom des mois et des jours. A changer si on veut d'autres langues (ou utiliser
	* la fonction gettext() de PHP. Ne pas changer les positions dans le tableau
	*/
	$nomj[0] = "D";
	$nomj[1] = "L";
	$nomj[2] = "M";
	$nomj[3] = "Me";
	$nomj[4] = "J";
	$nomj[5] = "V";
	$nomj[6] = "S";

	$nomm[0] = "Janvier";
	$nomm[1] = "F&eacute;vrier";
	$nomm[2] = "Mars";
	$nomm[3] = "Avril";
	$nomm[4] = "Mai";
	$nomm[5] = "Juin";
	$nomm[6] = "Juillet";
	$nomm[7] = "Ao&ucirc;t";
	$nomm[8] = "Septembre";
	$nomm[9] = "Octobre";
	$nomm[10] = "Novembre";
	$nomm[11] = "D&eacute;cembre";

	/**
	---------------------------------------------------------------------------------------------
	* Le reste du code PHP, à priori, y'a plus besoin de le toucher. Par contre, y'a la CSS juste
	* un peu plus bas. Celle-là est parfaitement modifiable (c'est d'ailleurs recommandé, c'est
	* toujours mieux de personnaliser un peu le truc)
	*/
	$ajd = @getdate();

	if(isset($_POST['mois']))
	{
		$mois = $_POST['mois'];
		$annee = $_POST['annee'];
	}
	else
	{
		$mois = $ajd['mon'];
		$annee = $ajd['year'];
	}

	$aujourdhui = array($ajd["mday"], $ajd["mon"], $ajd["year"]);

	$moisCheck = $mois+1;
	$anneeCheck = $annee;
	if ($moisCheck > 12)
	{
		$moisCheck = 1;
		$anneeCheck = $annee+1;
	}

	$dernierJour = @strftime("%d", mktime(0, 0, 0, $moisCheck, 0, $anneeCheck));
	$premierJour = @date("w", @mktime(0, 0, 0, $mois, 1, $annee));
	
/*************************
	On modifie la position du premier jour suivant la disposition des jours qu'on veut
***********************/
	if ($affichage != "en")
	{
		$origine = 1;
		$j = $origine;
		
		for ($i = 0; $i < count($nomj); $i++)
		{
			if ($j >= count($nomj)) $j = 0;

			$temp[] = $nomj[$j];
			$j++;
		}
		$nomj = $temp;
/*************************
	On décale le 1er jour en conséquence
***********************/
		$premierJour--;
		
		if ($premierJour < 0) $premierJour = 6;
	}

	// Affichage des mois
	$ldMois="";
	for ($i = 0; $i < sizeof($nomm); $i++)
	{
		$selected = get_selected($mois - 1, $i);
		$j = $i + 1;
		$ldMois .= "<option value=" . $j . " $selected>" . $nomm[$i] . "</option>\n";
	}

/*************************
	Affichage des années
***********************/
	$ldAnnees = "";
	for ($i = $ajd["year"] - $anneeMin; $i < $ajd["year"] + $anneeMax; $i++)
	{
		$selected2 = get_selected($annee, $i);
		$ldAnnees .= "<option value=" . $i . " $selected2>" . $i . "</option>\n";
	}

	$calEven = "<table id=\"calendar\">\n<tr>\n";

	// Affichage du nom des jours
	for ($jour = 0; $jour < 7; $jour++)
	{
		$classe = get_classe($jour, 1, $affichage);
		$calEven .= "<th $classe>" . $nomj[$jour] . "</th>\n";
	}
	$calEven .= "</tr>\n<tr>\n";

/*************************
	Affichage des cellules vides en début de mois, s'il y en a
***********************/
	for ($prems = 0; $prems < $premierJour; $prems++)
	{
		$classe = get_classe($prems, 2, $affichage);
		$calEven .= "<td $classe>&nbsp;</td>\n";
	}

/*************************
	Affichage des jours du mois
	//à optimiser
***********************/
	$cptJour = 0;
	for ($jour = 1; $jour <= $dernierJour; $jour++)
	{
		$classe = get_classeJour($aujourdhui, $annee, $mois, $jour, $cptJour, $premierJour, $nomj, $prems, $affichage);
		$cptJour++;

		$requete = "SELECT count(*) FROM evenements WHERE (date_debut_evenement<='" . $annee . "-" . $mois . "-" . $jour . "' AND date_fin_evenement>='" . $annee . "-" . $mois . "-" . $jour . "') AND id_langue='" . $_SESSION['langue'] . "' AND visible='oui'";
		$sql = $connexion->prepare($requete);
		$sql->execute();
		$nb = $sql->fetchColumn();
		
		if($nb != 0)
		{
			$requete = "SELECT * FROM evenements WHERE (date_debut_evenement<='" . $annee . "-" . $mois . "-" . $jour . "' AND date_fin_evenement>='" . $annee . "-" . $mois . "-" . $jour . "') AND id_langue='" . $_SESSION['langue'] . "' AND visible='oui'";
			$sql = $connexion->query($requete);
			$sql->setFetchMode(PDO::FETCH_OBJ);			
			$ligne = $sql->fetch();
			
			if($ligne->nature != "") $style = " style=\"background-color:#" . $ligne->nature . "\"";
			else $style = "";

			$calEven .= "<td $classe><a href=\"../pages/global.php?action=calendrier&amp;id_evenement=" . $ligne->id_evenement . "\"" . $style . "><span data-tip=\"" . $ligne->titre_evenement . "\">" . $jour . "</span></a></td>\n";                
		}
		else $calEven .= "<td $classe>" . $jour . "</td>\n";   

		if(is_int(($jour + $prems) / 7))
		{
			$cptJour = 0;
			$calEven .= "</tr>\n";
			
			if($jour<$dernierJour) $calEven .= "<tr>\n";
		}
	}
/*************************
	Affichage des cellules vides en fin de mois, s'il y en a
***********************/
	if ($cptJour != 0)
	{
		for($i = 0; $i < (7 - $cptJour); $i++)
		{
			$classe = get_classeJourReste($i, $cptJour, $affichage);
			$calEven .= "<td $classe>&nbsp;</td>\n";
		}
	}
	$calEven .= "</tr>\n</table>\n";

	include("calendrier.html");

?>