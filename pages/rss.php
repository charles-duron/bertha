<?php
// permet de virer les phpsessid dans l'url
ini_set('session.use_cookies', '1');
ini_set('session.use_trans_sid', '0');
ini_set('session.use_only_cookies', '1');
ini_set('url_rewriter.tags', '');
session_start();
include("../outils/fonctions.php");
$connexion = connexion();

//1. on stocke les actus valides dans un tableau de variables
$requete = "SELECT * FROM actus"; 
$sql = $connexion->query($requete);
$sql->setFetchMode(PDO::FETCH_OBJ);
$tab_actus = array();
$i = 0;
while($ligne = $sql->fetch())
     {
     if(($ligne->date_debut_actu=="0000-00-00" && $ligne->date_fin_actu=="0000-00-00") || 
        ($ligne->date_debut_actu=="0000-00-00" && $ligne->date_fin_actu>=@date("Y-m-d")) || 
        ($ligne->date_debut_actu<=@date("Y-m-d") && $ligne->date_fin_actu=="0000-00-00") ||
        ($ligne->date_debut_actu<=@date("Y-m-d") && $ligne->date_fin_actu>=@date("Y-m-d")))
       {
       $tab_actus[$i]=$ligne->id_actu;
       }
     $i++;
     }
     
//2. on affiche pour chaque id_actu trouvée       
$requete2 = "SELECT * FROM actus WHERE id_actu IN";
for($i=0;$i<sizeof($tab_actus);$i++)
   {
   if($i==0)
     {
     $requete2.="('" . $tab_actus[$i] . "'";
     }
   else
     {
     $requete2.=",'" . $tab_actus[$i] . "'";
     }   
   }
$requete2.=") AND id_langue='" . $_SESSION['langue'] . "' ORDER BY date_creation_actu DESC";  

$flux_rss=generer_flux_rss($requete2,$connexion, $_SESSION['titre_flux'], $_SESSION['description_flux']);
  
include("rss.xml");
?>
