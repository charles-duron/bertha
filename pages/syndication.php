<?php
$connexion = connexion();
$requete = "SELECT count(*) FROM syndications WHERE affiche='oui' AND id_langue=" . $_SESSION['langue'] . "";
$sql = $connexion->prepare($requete);
$sql->execute();
$nb = $sql->fetchColumn();

if($nb > 0)
{
	$requete = "SELECT * FROM syndications WHERE affiche='oui' AND id_langue=" . $_SESSION['langue'] . "";
	$sql = $connexion->query($requete);
	$sql->setFetchMode(PDO::FETCH_OBJ);
	while($ligne = $sql->fetch())
	{
		$xml = lit_xml(stripslashes($ligne->url_syndication),"item",array("title","link","description"),$ligne->nombre);
		$actus_syndiquees = "<div id=\"syndications\">\n";
		$actus_syndiquees .= "<h3>" . stripslashes($ligne->titre_syndication) . "</h3>\n";
		$actus_syndiquees .= "<ul>\n";
		foreach($xml as $row)
		{
			$actus_syndiquees .= "<li><a href=\"" . $row[1] . "\" target=\"_blank\">" . utf8_encode($row[0]) . "</a></li>\n";
		}
		$actus_syndiquees .= "</ul>\n";
		$actus_syndiquees .= "</div>\n";
		echo utf8_encode(utf8_decode($actus_syndiquees));
	}
} 

?>