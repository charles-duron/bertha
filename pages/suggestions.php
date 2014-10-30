<?php
include("../outils/fonctions.php");
$connexion=connexion();

if(isset($_POST['suggestions']))
{    
   $requete="SELECT recherche FROM pages";
   $resultat=mysql_query($requete, $connexion);
   $tab_mots=array();
   while($ligne=mysql_fetch_object($resultat))
   {
     $explode=explode(" ", $ligne->recherche);
     for($i=0; $i < count($explode); $i++)
     {
       array_push($tab_mots, $explode[$i]);
     }
   }
   echo json_encode($tab_mots);
}
?>