<?php

/**
 * Inventar Verwaltung
 * 
 * @autor Josef Rohowsky - neu  2020
 */
session_start(); # die SESSION aktivieren
 
$module = 'INV';
$sub_mod = 'all';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$_SESSION[$module]['Inc_Arr']  = array();
$_SESSION[$module]['Inc_Arr'][] = "VF_I_IN_Verw.php";

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
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

$sk = $_SESSION['VF_Prim']['SK'];

# ===========================================================================================================
# Header ausgeben
# ===========================================================================================================

BA_HTML_header('Inventar', '', 'Form', '70em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

echo "<fieldset style='border:2px solid blue;'>";

Edit_Tabellen_Header('Inventar- Verwaltung');

echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
echo "Hier wird die Pflege der Daten durchgeführt. Die Daten werden unter dem Eigentümer abgelegt und bestehen aus der Inventarnummer,
dem entsprechenden Referat, die Epoche der Entstehung/Nutzung, Suchbegriff, Hersteller, genauere Beschreibung,
Daten über einen Leiher und den Ort (Raum, Platz) wo sich der Gegenstand zur Zeit befindet.";
echo "<div class='w3-third'>"; // Beginn der Anzeige Feld-Name
echo "  </div>";  // Ende Feldname
echo "  <div class='w3-row'>"; // Beginn Inhalt- Spalte
echo "<a href='VF_I_IN_List.php?sk=$sk&SpaltenNamenAnzeige=Aus' target='Inventar'>Inventar- Verwaltung</a>";
echo "</div>"; // Ende der Ausgabe- Einheit Feld

Edit_Tabellen_Trailer();

echo "</fieldset>";

BA_HTML_trailer();
?>
