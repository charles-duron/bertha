<?php
header('content-type: text/css');
session_start();
include("../outils/fonctions.php");
$connexion = connexion();	//connexion à MySQL - etape 1

//on récupère les parametres du site dont le template choisi
$requete0="SELECT p.*, t.*, c.*, f.* FROM parametres p 
          INNER JOIN templates t 
          INNER JOIN css c 
          INNER JOIN fonts f             
          ON p.id_template=t.id_template 
          AND t.id_template=c.id_template 
          AND p.id_font=f.id_font";
$resultat0=mysql_query($requete0,$connexion);
$i=0;
$_SESSION['style']=""; // initialisation de la variable style
while($ligne0=mysql_fetch_object($resultat0))
     {
     if($i==0)
       {
       define("MAIL_RETOUR", $ligne0->mail_retour);
       define("MAIL_REPONSE", $ligne0->mail_reponse);
       $_SESSION['favicon']="<link rel=\"shortcut icon\" href=\"" . $ligne0->favicon . "\" type=\"image/x-icon\" />\n<link rel=\"icon\" href=\"" . $ligne0->favicon . "\" type=\"image/x-icon\" />\n";      
       $_SESSION['titre_flux']=$ligne0->titre_flux;
       $_SESSION['titre_site']=$ligne0->titre_site;
       $_SESSION['description_flux']=$ligne0->description_flux;             
       $_SESSION['galerie_photos']=$ligne0->galerie_photos;       
       $_SESSION['form_contact']=$ligne0->form_contact;
       $_SESSION['form_recherche']=$ligne0->form_recherche; 
       if($ligne0->lien_font!="")
         {
         $_SESSION['lien_font']=$ligne0->lien_font . "\n";
         $_SESSION['nom_font']="body{font-family:'" . $ligne0->nom_font . "', sans-serif;}\n";
         }
       else
         {
         $_SESSION['nom_font']="body{font-family:'" . $ligne0->nom_font . "', sans-serif;}\n";
         }           
       if($ligne0->id_page!=0)
         {
         $page_accueil="?action=page&id_page=" . $ligne0->id_page; 
         }  
       else
         {
         $contenu="<div id=\"notification_page_accueil\"><a href=\"../admin/admin.php?action=parametres&amp;cat=1\">Désignez dans les paramètres la page d'accueil à afficher</a></div>\n";
         }      

       // on traite le logo                   
       if($ligne0->logo!="")
         {
         $image_flux=explode("/",$ligne0->logo);
         $_SESSION['image_flux']=$image_flux[1] . "/" . $image_flux[2];
         $_SESSION['logo_bo']="<a href=\"../pages/global.php?action=apercu\" id=\"logo\"><img src=\"" . $ligne0->logo . "\" alt=\"\" /></a>\n";
         $_SESSION['logo_fo']="<a href=\"../pages/global.php\" id=\"logo\"><img src=\"" . $ligne0->logo . "\" alt=\"" . $ligne0->titre_site . "\" /></a>\n";
         }
       else
         {
         $_SESSION['logo_fo']="<a href=\"../admin/admin.php?action=parametres&cat=1\" id=\"logo\"><span>Insérez le logo<br />dans les paramètres</span></a>\n";
         $_SESSION['logo_bo']="<a href=\"../pages/global.php?action=apercu\" id=\"logo\">R<span>OBERTA</span><br /><span>Cool · Magique · Simple</span></a>\n";
         } 
       if($ligne0->calendrier == "oui")
         {       
         $calendrier="calendrier.php";
         }        
       if($ligne0->rss == "oui")
         {       
         $rss="<a href=\"rss.php\" id=\"rss\" title=\"Flux RSS\" target=\"_blank\"><img src=\"../img/icones/rss.png\" /></a>\n";
         }          
       if($ligne0->favicon!="")
         {
         $favicon="<link rel=\"shortcut icon\" href=\"" . $ligne0->favicon . "\" type=\"image/x-icon\" />\n";
         $favicon.="<link rel=\"icon\" href=\"" . $ligne0->favicon . "\" type=\"image/x-icon\" />\n";
         }  
         
       // on traite le cas des réseaux sociaux  
       if($ligne0->reseaux == "oui")
         {
         $bloc_reseaux="<div id=\"bloc_reseaux\">\n";
         $tab_reseaux=array("facebook", "twitter", "googleplus", "linkedin", "viadeo", "pinterest", "flickr");
         $tab_replace=array("Facebook", "Twitter", "Google +", "Linkedin", "Viadeo", "Pinterest", "Flickr");
         $reseaux=json_decode($ligne0->liste_reseaux, true);
         for($i=0; $i < count($reseaux); $i++)
            {
            if($reseaux[$i][1] == "oui")
              {
              $bloc_reseaux.="<a href=\"" . $reseaux[$i][2] . "\" title=\"" . str_replace( $tab_reseaux, $tab_replace, $tab_reseaux[$i]) . "\" target=\"_blank\"><img src=\"../img/icones/" . $tab_reseaux[$i] . "f.png\" alt=\"compte_" . $tab_reseaux[$i] . "\" /></a>\n";
              }
            }
         $bloc_reseaux.="</div>\n";
         } 
      
       if($ligne0->syndication == "oui")
         {
         $syndication="syndication.php";
         }                        
       }
     $_SESSION['style'].="<link rel=\"stylesheet\" type=\"text/css\"  href=\"../templates/" . $ligne0->lien_css . ".css\"/>\n";
     $_SESSION['style_admin']="<link rel=\"stylesheet\" type=\"text/css\" href=\"../css/admin" . $ligne0->id_theme . ".css\" />\n";      
     $i++;
     }
include("../css/font.css");   
?>
