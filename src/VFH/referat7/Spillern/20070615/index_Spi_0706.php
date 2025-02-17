<?php
session_start();
# ------------------------------------------------------------------------------------------------------------------
#
#Inhaltsverzeichnis und Aufruf für Archivierte Berichte


$module    = 'VF_Arch';
$path2ROOT = "../../../../";

$debug = True; $debug = False;  // Debug output Ein/Aus Schalter

 require $path2ROOT.'login/common/BA_Funcs.lib.php' ;  // Diverse Unterprogramme

initial_debug(); # Wenn $debug=true - Ausgabe von Debug Informationen: $_POST, $_GET, $_FILES

$logo = 'JA';
$header = "<link  href='".$path2ROOT."login/common/css/frame_pict.css' rel='stylesheet' type='text/css'>";
HTML_header('Verein Feuerwehrhistoriker in NÖ) ','Achivierte Berichte',$header,'Form','75em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

?>

<body class="w3-container ">
<div class="w3-content ">  <!-- max-width:45em; margin:5em;--->
<fieldset>

<h1>135 Jahre FF Spillern mit Fahrzeug- und Fahnenweihe</h1>

<img src="../../../../login/AOrd_Verz/124/09/06/20070617/JR-20070617-img_5926.WebP" alt="Fahnenweihe" align="right">
<p>Am 15. Juni 2007 fand die 135 Jahrfeier der FF Spillern statt. Am Festakt
wurde musikalisch von der Kapelle Feldbach begleitet. Eine Abteilung der
Hoch- und Deutschmeister mit Fahnenträgern war vertreten, ebenso einige Fahnengruppen
aus Österreich und aus dem Ausland.
</p>

<p>Quellen: Foto: Josef Rohowsky
</p>



</fieldset>
</div>
<?php 
HTML_trailer();
 ?>
