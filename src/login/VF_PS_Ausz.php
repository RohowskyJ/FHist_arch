<?php

/**
 * PSA Auszeichnungen und Ehrungen, Aufteilung in Staatliche und Private (Vereine) und CTIF Leistungsbew. Abz
 *
 * @author Josef Rohowsky - neu 2024
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

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php' ;
 
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';

$flow_list = False;

$LinkDB_database  = '';
$db = LinkDB('VFH');

initial_debug(); 

VF_chk_valid();

VF_set_module_p();

VF_Count_add();

# ===========================================================================================================
#                                            Haeder ausgeben
# ===========================================================================================================

  if ($_SESSION[$module]['p_zug'] == "Q") {
      $_SESSION[$module]['all_upd'] = True;
  }

  $sk= $_SESSION['VF_Prim']['SK'];
  
  if (isset($_GET['proj'])) {$_SESSION[$module]['proj'] = $_GET['proj'];}
  
  BA_HTML_header('Auswahl der Stifter (Staatlich, Privat, CTIF) ','','Form','70em'); # Parm: Titel,Subtitel,HeaderLine,Type,width 
 
  echo "</div>";
  
  echo "<div class='w3-row' >"; // Beginn der Einheit Ausgab
  
  Edit_Separator_Zeile('Staatliche Auszeichnungen und Ehrenzeichen'); 
  
  echo "<div class='w3-row' >"; // Beginn der Einheit Ausgab
  echo "Staatliche (und alle Stufen wie Bundesland, Bezirk, Gemeinde, LFKDO) Dekorationen";
  echo "</div>";
  
  echo "<div class='w3-row' >"; // Beginn der Einheit Ausgab
  echo "Gemeinsam ist die Stiftung per Gesetz oder entsprechender Beschluss (Gemeinderat, LFKDO)";
  echo "</div>";
  
  echo "<div class='w3-row' >"; // Beginn der Einheit Ausgab
  echo "<a href='VF_PS_OV_O_List.php?sk=$sk&proj=AUSZ' target='Auszeichnungs-Daten'>Orts-, Auszeichnungen, Ehrenzeichenverwaltung</a>";
  echo "</div>";
  
  Edit_Separator_Zeile('Auszeichnungen Privater Instanzen (Vereine)'); 
  
  echo "<div class='w3-row' >"; // Beginn der Einheit Ausgab
  echo "Die Dekorationen werden in den Vereinsstatuten geregelt";
  echo "</div>";
  
  echo "<div class='w3-row' >"; // Beginn der Einheit Ausgab
  echo "<a href='VF_PS_OV_O_Edit.php?ID=3757'>Vereine</a>";
  echo "</div>";
  
  echo "<div class='w3-row' >"; // Beginn der Einheit Ausgab
  echo "<a href='VF_PS_OV_O_Edit.php?ID=3758'>ÖRK</a>";
  echo "</div>";
  
  echo "<div class='w3-row' >"; // Beginn der Einheit Ausgab
  echo "<a href='VF_PS_OV_O_Edit.php?ID=3755'>CTIF</a>";
  echo "</div>";

BA_HTML_trailer();
?>
