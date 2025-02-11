<?php

/**
 * PSA, Bilder- Beschreibungen
 *
 * @author Josef Rohowsky - neu 2018
 */
session_start(); # die SESSION aktivieren  

const Module_Name   = 'PSA';
$module             = Module_Name;
# const Tabellen_Name = 'fh_dokumente'; 

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT          = "../";

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';

# require $path2ROOT . 'login/common/VF_Comm_Funcs.inc' ;

initial_debug();

# ========================================================================================================
#                                            Header ausgeben
# ===========================================================================================================

  BA_HTML_header('Information zu Bildern für Auszeichnungen, Ärmelabzeichen, Wappen ','','','Form','70em'); # Parm: Titel,Subtitel,HeaderLine,Type,width 
 
 
  echo "<div class='w3-row' >"; // Beginn der Einheit Ausgab
  
  Edit_Separator_Zeile('Bildformate zum Hochladen'); 
  
  echo "<div class='w3-row' >"; // Beginn der Einheit Ausgab
  echo "Die maximale Größe von Einzelbildern für die Darstellung von Fotos darf 15 x 15 cm, Auflösung 96 DPI nicht überschreiten.
       <br>";
  echo "Die empfohlenen Größen für Auszeichnungen und Abzeichen:";
  echo "<dl compact>";
  
  echo "<dt><b>Große Bilder</b></dt>";
  echo "<dd><b><i>15 x 15 cm 96 DPI</i></b>, z.B.: Ärmelabzeichen, Auszeichnungen mit Band oder größer </dd>";
  
  echo "<dt><b>Mittlere Bilder</b></dt>";
  echo "<dd><b><i>8 x 8 cm 96 DPI</b></i>, z.B.: Leistungsabzeichen, Medaillen ohne Band, ... </dd>";
  
  echo "<dt><b>Kleine Bilder</b></dt>";
  echo "<dd><b><i>6 x 6 cm 96 DPI</i></b>, z.B.: Miniaturen, Jugenabzeichen (alt)</dd>";
 
  echo "</dl>";
  
  echo "</div>";


BA_HTML_trailer();
?>
