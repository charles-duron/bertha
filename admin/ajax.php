<?php

	session_start();
	include('../outils/fonctions.php');
	$connexion = connexion();

	if(isset($_POST['cas']))
	{
		switch($_POST['cas'])
		{
			case "rubriques":

				if(isset($_POST['rang']))
				{
					$requete = "SELECT * FROM rubriques WHERE id_rubrique='". $_POST['id_rubrique'] . "' AND id_langue='" . $_POST['id_langue'] . "'";
					$sql = $connexion->query($requete);
					$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
					$ligne = $sql->fetch();

					if($_POST['sens'] == "up") // si on monte
					{
						if($ligne->rang == 1) $rang = 1;
						else
						{
							$rang = $ligne->rang - 1;
							$requete = "SELECT * FROM rubriques WHERE rang='" . $rang . "' AND id_langue='" . $ligne->id_langue . "'";
							$sql = $connexion->query($requete);
							$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
							$ligne2 = $sql->fetch();
							$suivant = $ligne2->rang + 1;
							$requete = "UPDATE rubriques SET rang='" . $suivant . "' WHERE id_rubrique='" . $ligne2->id_rubrique . "'";
							$sql = $connexion->exec($requete);
						}
					}
					else // si on descend
					{
						$requete = "SELECT count(*) FROM rubriques WHERE id_langue='" . $_POST['id_langue'] . "'";
						$sql = $connexion->prepare($requete);
						$sql->execute();
						$nb = $sql->fetchColumn();

						if($ligne->rang == $nb) $rang = $nb;
						else
						{
							$rang = $ligne->rang + 1;
							$requete = "SELECT * FROM rubriques WHERE rang='" . $rang . "' AND id_langue='" . $ligne->id_langue . "'";
							$sql = $connexion->query($requete);
							$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);

							$ligne3 = $sql->fetch();
							$precedent = $ligne3->rang - 1;
							$requete = "UPDATE rubriques SET rang='" . $precedent . "' WHERE id_rubrique='" . $ligne3->id_rubrique . "'";
							$sql = $connexion->exec($requete);
						}
					}
					$requete = "UPDATE rubriques SET rang='" . $rang . "' WHERE id_rubrique='" . $_POST['id_rubrique'] . "'";
					$sql = $connexion->exec($requete);	          		
				}          

			break;

			case "pages":

				if(isset($_POST['rang']))
				{
					$requete = "SELECT * FROM pages WHERE id_page='". $_POST['id_page'] . "' AND id_rubrique='" . $_POST['id_rubrique'] . "'";
					$sql = $connexion->query($requete);
					$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
					$ligne = $sql->fetch();

					if($_POST['sens'] == "up") // si on monte
					{
						if($ligne->rang == 1) $rang = 1;
						else
						{
							$rang = $ligne->rang - 1;
							$requete = "SELECT * FROM pages WHERE rang='" . $rang . "' AND id_rubrique='" . $ligne->id_rubrique . "'";
							$sql = $connexion->query($requete);
							$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
							
							$ligne2 = $sql->fetch();
							$suivant = $ligne2->rang + 1;
							$requete = "UPDATE pages SET rang='" . $suivant . "' WHERE id_page='" . $ligne2->id_page . "'";
							$sql = $connexion->exec($requete);
						}
					}
					else // si on descend
					{
						$requete = "SELECT count(*) FROM pages WHERE id_rubrique='" . $_POST['id_rubrique'] . "'";
						$sql = $connexion->prepare($requete);
						$sql->execute();
						$nb = $sql->fetchColumn();

						if($ligne->rang == $nb) $rang = $nb;
						else
						{
							$rang = $ligne->rang + 1;
							$requete = "SELECT * FROM pages WHERE rang='" . $rang . "' AND id_rubrique='" . $ligne->id_rubrique . "'";
							$sql = $connexion->query($requete);
							$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
							$ligne3 = $sql->fetch();
							$precedent = $ligne3->rang - 1;
							$requete = "UPDATE pages SET rang='" . $precedent . "' WHERE id_page='" . $ligne3->id_page . "'";
							$sql = $connexion->exec($requete);
						}
					}
					$requete = "UPDATE pages SET rang='" . $rang . "' WHERE id_page='" . $_POST['id_page'] . "'";
					$sql = $connexion->exec($requete);
				}

			break;  
		}
	}
	if(isset($_POST['id_langue']))
	{
		$requete = "SELECT r.*, l.* FROM rubriques r, langues l WHERE r.id_langue=l.id_langue AND r.id_langue='" . $_POST['id_langue'] . "' ORDER BY r.rubrique";
		$sql = $connexion->query($requete);
		$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
		$i = 0;
		while($ligne = $sql->fetch())
		{
			if($i == 0) $rubriques = "<option value=\"\">" . $ligne->pays . "</option>";
			
			if(isset($_SESSION['id_rubrique']) && $_SESSION['id_rubrique'] == $ligne->id_rubrique)
			{
				$selected[$ligne->id_rubrique] = " selected=\"selected\"";
				$selection = $selected[$ligne->id_rubrique];
			}
			else $selection="";

			$rubriques .= "<option value=\"" . $ligne->id_rubrique . "\"" . $selection . ">" . $ligne->rubrique . "</option>";
			$i++;
		}

		if(isset($_SESSION['id_rubrique'])) unset($_SESSION['id_rubrique']);

		echo $rubriques;  
	}
	if(isset($_POST['id_media']))
	{
		$requete = "SELECT titre_media, alt_media FROM medias WHERE id_media = " . $_POST['id_media'];
		$sql = $connexion->query($requete);
		$resultat = $sql->setFetchMode(PDO::FETCH_OBJ);
		$ligne = $sql->fetch();
		
		$resultat = $ligne->titre_media . "#_#" . $ligne->alt_media;

		echo $resultat;  
	}

?>