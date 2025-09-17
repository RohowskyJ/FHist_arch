<?php

/**
 * Geräte- Verwaltung, Auswahl
 *
 * @author Josef Rohowsky - neu 2023
 */
session_start();

# die SESSION aktivieren
const Module_Name = 'F_G';
$module = Module_Name;
# const Tabellen_Name = 'fh_dokumente';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = false; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';

$flow_list = false;

$LinkDB_database  = '';
$db = LinkDB('VFH');

initial_debug();

VF_chk_valid();

VF_set_module_p();

VF_Count_add();

# ===========================================================================================================
# Haeder ausgeben
# ===========================================================================================================

$sk = $_SESSION['VF_Prim']['SK'];
BA_HTML_header('Fahrzeuge und Geräte', '', 'Form', '70em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

Edit_Tabellen_HEader('Beschreibungen der Fahrzeuge und Geräte');

Edit_Separator_Zeile('Mit Muskelkraft bewegte Fahrzeuge und Geräte');

echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
echo "Beschreibungen der Muskelbewegten Fahrzeuge und Geräte.";
echo "  </div>";  // Ende Feldname
echo "  <div class='w3-row'>"; // Beginn Inhalt- Spalte
echo "<a href='VF_FZ_MuFG_List.php?sk=$sk&ID=NextEig' target='MuskelFzgGer'>Muskelgezogene Fahrzeuge und muskelbetriebene Geräte - Wartung </a>";
echo "</div>"; // Ende der Inhalt Spalte
echo "  <div class='w3-row'>"; // Beginn Inhalt- Spalte
echo "<a href='VF_FZ_MuFG_Katalog_List.php?sk=$sk' target='MuskelFzgGerKat'>Katalog der Muskelgezogenen Fahrzeug- und Geräte</a>";
echo "</div>"; // Ende der Inhalt Spalte
echo "  <div class='w3-row'>"; // Beginn Inhalt- Spalte
echo "<a href='VF_O_DO_List.php?sk=$sk&sel_thema=1' target='Dokumente'>Vereins- Dokumentation zu Muskelbewegtem</a>";
echo "</div>"; // Ende der Inhalt Spalte

Edit_Separator_Zeile('Maschinenbewegte Fahrzeuge (Automobile) und motorbetriebene Geräte ');
echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
echo "Beschreibungen der mit Motorkraft bewegten Fahrzeuge und für Motorfahrzeuge konstruierten Anhänger, Geräte.";
echo "  </div>";  // Ende Feldname
echo "  <div class='w3-row'>"; // Beginn Inhalt- Spalte
echo "<a href='VF_FZ_MaFG_List.php?sk=$sk&ID=NextEig' target='MotorFahrzeuge'>Motorisierte Fahrzeug- und Geräte Wartung </a>";
echo "</div>"; // Ende der Inhalt Spalte

echo "  <div class='w3-row'>"; // Beginn Inhalt- Spalte
echo "<a href='VF_FZ_MaFG_Katalog_List.php?sk=$sk' target='Automobil-Katalog'>Automobil- Fahrzeugkatalog</a>";
echo "</div>"; // Ende der Inhalt Spalte

echo "  <div class='w3-row'>"; // Beginn Inhalt- Spalte
echo "<a href='VF_O_DO_List.php?sk=$sk&sel_thema=2' target='Dokumente'>Vereins- Dokumentation zu Fahrzeugen </a></td></tr>";
echo "</div>"; // Ende der Inhalt Spalte

Edit_Tabellen_trailer();

$res = VF_Urh_ini();

BA_HTML_trailer();
