<?php
header('content-type: text/css');
session_start();
$font="body{\nfont-size:" . $_SESSION['taille_font']  . ";\n";
$font.="font-family:'" . $_SESSION['nom_font'] . "', sans-serif;\n";
$font.="}\n";
echo $font;
?>                                
