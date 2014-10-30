<?php
// -----------
// Préparation
// -----------
 
    // On définit les dossiers par défaut
    $rootFolder = "/";
    $archivesFolder = $rootFolder."_archives/";
 
    // On définit où seront les fichiers archivés (
    $nameFilesBkp = $archivesFolder.date("D")."_files";
    $nameBddBkp = $archivesFolder.date("D")."_bdd";
     
    // Information de la base de donnée
    $pdo_host = "votreserveurovh";      // Exemple : "mysql2-1.start"
    $pdo_user = "votrenomdutilisateur"; 
    $bdd_name = "votrebasededonnee";    // Chez OVH, c'est la même chose que $pdo_user
    $pdo_pwd = "votremotdepasse";
 
    // Si il n'existe pas, on créé le dossier d'archive
    if(!file_exists($archivesFolder))
        mkdir($archivesFolder, 0777, true);
 
         
// -----------------------------
// Archives de la base de donnée
// -----------------------------
     
    // Extrait et compresse les données en une seule commande
    system("mysqldump -h".$pdo_host." -u".$pdo_user." -p".$pdo_pwd." ".$bdd_name." | gzip> ".$nameBddBkp.".zip");
 
 
// ------------------------------------------------------------------
// Archives des fichiers
// (depuis http://www.ingeny.fr/php/compression-zip-recursive-en-php)
// ------------------------------------------------------------------
 
    // Teste la version de PHP
    if(substr(phpversion(), 0, 1) < 5)
        exit("Vous devez utiliser <strong>PHP5 ou supérieur</strong>. Vous utilisez <strong>".phpversion()."</strong>");
 
    // Teste que le module php5-zip est chargé
    if(false === extension_loaded('zip'))
        exit("Le module PHP5 <strong>Zip</strong> n'est pas chargé");
 
    // On créée un objet itérateur qui permettra de parser les fichiers récursivement
    $iter = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(
            ROOT_DIR ,RecursiveDirectoryIterator::KEY_AS_FILENAME
        ), RecursiveIteratorIterator::SELF_FIRST
    );
 
    // On instantie une ressource ZIP
    $zip = new ZipArchive();
     
    // On fait pointerla ressource au bon endroit
    $fichier= $nameFilesBkp.".zip";
    $res = $zip->open($fichier, ZipArchive::CREATE);
 
    // Puis, dossier par dossier, on compresse les fichiers
    foreach ($iter as $entry):  
        $filename = str_replace(ROOT_DIR, '', $entry);
 
        if('/' === $filename{0})
        $filename = substr($filename, 1);
 
        $zip->addFile($entry, $filename);
 
    endforeach;
 
    $zip->close();
	
?>