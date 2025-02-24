<?php

/**
 * Archivalien Veraltung, Einstieg
 * 
 * @author Josef Rohowsky - neu 2020
 */
session_start();

# die SESSION aktivieren
const Module_Name = 'ARC';
$module = Module_Name;
# const Tabellen_Name = 'fh_dokumente';

/**
 * Liste der Archivalien
 *
 * @author josef Rohowsky - neu 2019
 *
 */
$path2ROOT = "../";

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';

$flow_list = True;

$LinkDB_database  = '';
$db = LinkDB('VFH');

initial_debug();

VF_chk_valid();

VF_set_module_p();

$sk = $_SESSION['VF_Prim']['SK'];

VF_Count_add();

$logo = "J";
# ===========================================================================================================
# Haeder ausgeben
# ===========================================================================================================

BA_HTML_header('Archivaliendaten Verwaltung, erweiterte Archivordnung ', '', 'Form', '70em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

echo "<fieldset style='border:2px solid blue;'>";

Edit_Separator_Zeile('Archivalien');
echo "<div class='w3-row' >"; // Beginn der Einheit Ausgabe
echo "Archivalien- Verwaltung: Einordnung entsprechend der Archivordnug, Eingeben der Daten einer Archivalie (Dokument), Eingabe der Schlagworte zum Suchen <br>";
echo "<a href='VF_A_AR_List.php?sk=$sk' target='Archiv'>Archivalien- Verwaltung </a><br>";
echo "Die Anzahl der digitalisierten Objekte in den jeweiligen Archiv- Teilen anzeigen.<br>";
echo "<a href='VF_A_AR_DocCnt_AN.php?sk=$sk&ei_id=21' target='Arch-Docs'>Archiv- Dokumente Anzahl je Arch_Ordnung Eig=21 (FF WrNdf)</a>";
echo "</div>";

Edit_Separator_Zeile('Archivordnung, erweitert');
echo "<div class='w3-row' >"; // Beginn der Einheit Ausgabe
echo "Die Grundlegende Archivordnung ist im OBFV- Feuerwehrgeschichte definiert und hat zwei Ebenen.<br>";
echo "Die erweiterte Archivordung nutzt die Definierte Ordnung und fügt vier Ebenen hinzu, die in der Basis zu grob definiert sind,
     und für die genauere Suche hilfreich sind.<br>";
echo "<a href='VF_A_ORD_List.php?sk=$sk' target='ArchivOrd'>Erweiterung der Archiv- Ordnung </a>";
echo "</div>";


Edit_Separator_Zeile('Massen- Hochladen von Dokumenten');
echo "<div class='w3-row' >"; // Beginn der Einheit Ausgabe
echo "Hier können die die digitalisierten Dokumente massenweise (je Gruppe innerhalb der Archivordnung) geladen und die Tabelle für die 
Dokumente in den jeweiligen Archivordnung werden erstellt. Die Beschreibungen müssen dann in der Archivalien-Verwaltung eingegeben werden. <br>";
echo "<a href='VF_A_AR_MassUp.php?sk=$sk&Act=l' target='Arch'>Massen- Upload von Dokumenten</a> <br>";
echo "Das Massen- Upload speichert die Daten in VF_Upload/eigner, von diesem Speicherplatz werden sie mit dem Programm in der nächsten Zeile in die Datenbank eingebunden und sind dann mit der Archivalien-Liste einsehbar. <br>";
echo "<a href='VF_A_AR_MassUp2_Arch_Tabs.php?sk=$sk' target='Arch'>Massen- Datei- Hochladen - Tabellen erstellen (Dokumente)</a>";
echo "</div>";

echo "</fieldset>";

BA_HTML_trailer();
?>
