<?php

/**
 * Daten aus csv- Dateien in Tabellen einlesen
 * 
 * @author Josef Rohowsky - neu 2018
 * 
 * 
 */
session_start(); # die SESSION am leben halten 
const Module_Name = 'ADM';
$module    = Module_Name;
# $tabelle = 'fh_benutzer';
const Prefix  = '';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = False;  // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php' ; 
require $path2ROOT . 'login/common/BA_Funcs.lib.php' ; 
require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php' ;

initial_debug(); 

VF_chk_valid();
// ============================================================================================================
// Eingabenerfassung und defauls
// ============================================================================================================

$db = LinkDB('VFH'); 

// ============================================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================

if (isset($_POST['phase'])) {$phase = $_POST['phase'];} else {$phase = "0";}
if (isset($_POST['indata'])) {$indata = $_POST['indata'];} else {$indata = "";}

  # -------------------------------------------------------------------------------------------------------
  # Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
  # -------------------------------------------------------------------------------------------------------
  
  $titel = "Daten aus FlatFile (csv | separated) in Tabelle laden: ";
BA_HTML_header($titel,'','Form','90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

?>
  <form id="myform" name="myform" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>" enctype="multipart/form-data">
  <fieldset>
<?php
# echo "C_DS_2 L 062: \$phase $phase <br/>";
  switch ($phase)
  { case 0 : require 'VF_Z_DS_2_ph0.inc.php' ;
             break;
    case 1 : require 'VF_Z_DS_2_ph1.inc.php' ;
             break;
  }
?>
  </fieldset>
  </form>
<?php 
BA_HTML_trailer();
?>
