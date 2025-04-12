<?php
/**
 * Zentrale Verwaltung
 * 
 * @author Josef Rohowsky - neu 2020
 */
session_start();

const Module_Name = 'ADM';
$module = Module_Name;

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';

$flow_list = False;

VF_chk_valid();

VF_set_module_p();

initial_debug();

$db = linkDB('VFH'); // Connect zur Datenbank

$sk = $_SESSION['VF_Prim']['SK'];
# echo "L 028 sk $sk <br>";
# ===========================================================================================================
# Haeder ausgeben
# ===========================================================================================================

BA_HTML_header('Administration', '', 'Form', '70em'); # Parm: Titel,Subtitel,HeaderLine,Type,width


if ($_SESSION['VF_Prim']['ADM'] == "V"  || $_SESSION['VF_Prim']['WVM'] != "N") {
    Edit_Separator_Zeile('Mitglieder- Verwaltung');
    echo "<div class='w3-row' >"; // Beginn der Einheit Ausgabe
    echo "Verwaltung der Mitglieder, Zahlungeingang und Kontrolle, Mitteilung der gespeicherten Daten nach DSGVO, E-Mail an andere Mitglieder ohne Kenntnis deren Adresse.<br>";
    echo "<a href='VF_M_Mitgl_Verw.php?sk=$sk' target='M-Verwaltung'>Mitgliederverwaltung</a>"; # neu OK
    echo "</div>";
}
if ($_SESSION['VF_Prim']['ADM'] == "V" || $_SESSION['VF_Prim']['MVW'] == "V" ) {
    echo "<div class='w3-row' >"; // Beginn der Einheit Ausgabe
    Edit_Separator_Zeile('Eigentümerverwaltung');
    echo "Da hier auch Daten von Nicht-Mitgliedern aufgenommen werden können, ist eine eigene Verwaltung ohne Mitglieder-Bezug notwendig.<br>";
    echo "<a href='VF_Z_E_List.php?sk=$sk&SpaltenNamenAnzeige=Aus' target='Eigentm'>Eigentümerverwaltung </a>"; # neu OK
    echo "</div>";
}
    
if ($_SESSION['VF_Prim']['ADM'] == "V" || $_SESSION['VF_Prim']['WVM'] == "Q") {       
    Edit_Separator_Zeile('Benutzer- und Zugriffsverwaltung');
    echo "<div class='w3-row' >"; // Beginn der Einheit Ausgabe
    echo "Pflege der berechtigten Benutzer, Passworte und Berechtigungen.</d><br>";
    echo "<a href='VF_Z_B_List.php?sk=$sk' target='Benutz'>Benutzer- und Zugriffs- Verwaltung </a>"; # neu
    echo "</div>";
}
if ($_SESSION['VF_Prim']['ADM'] == "V" ) {  
    
    Edit_Separator_Zeile('Liste der Empfänger von administrativen E-Mails (Mitglieds- Neuanmeldung, Bezahlung, ... ');
    echo "<div class='w3-row' >"; // Beginn der Einheit Ausgabe
    echo "<a href='VF_Z_EM_List.php?sk=$sk' target='Mail_List'>Empfänger der automatischen E-Mails</a>"; # neu ok
    echo "</div>";
    
    Edit_Separator_Zeile('Konfiguration der Seite ');
    echo "<div class='w3-row' >"; // Beginn der Einheit Ausgabe
    echo "<tr><TD>Betreiber der Seite, Vereinsregister, E-Mail-Adresse,  </d><br>";
    
    echo "<a href='common/Proj_Conf_Edit.php?sk=$sk' target='Config'>Konfigurations- Parameter der URL</a>"; # neu OK
    echo "</div>";
    
    if ($_SESSION['VF_Prim']['p_uid'] == "1") {
        Edit_Separator_Zeile('Prozesse, die zu Analysen und Korrekturen dienen, aber unter Umständen vorher geändert/angepasst werden müssen.');
        echo "<div class='w3-row' >"; // Beginn der Einheit Ausgabe
        echo "Pflege verschiedener Daten </br>";
        echo "<a href='VF_Z_Suchb_Gen.php?sk=$sk' target='suchbegr'>Suchbegriffe (Findbücher) regenerieren </a><br>";
        echo "<a href='VF_Z_Pict_Valid.php?sk=$sk' target='Bilder Prüfg'>Bilder- Prüfung (Tabellen - Dirs / vorhanden - nicht vorhanden)</a><br>";
        echo "<a href='VF_Z_DS_2_Table.php?sk=$sk' target='Flat-File Imp'>FlatFile Import in eine Tabelle</a><br>";
        echo "<a href='VF_Z_AR_Renum_AN.php?sk=$sk&ei_id=1' target='ArchNr-Renum'>Archiv- Nummern Renum Eig=1 (Verein)</a><br>";
        echo "<a href='VF_Z_AR_Renum_AN.php?sk=$sk&ei_id=21' target='ArchNr-Renum'>Archiv- Nummern Renum Eig=21 (FF WrNdf)</a><br>";
        echo "</div>";
    }
    
    Edit_Separator_Zeile('Sitzungs- Protokolle');
    echo "<div class='w3-row' >"; // Beginn der Einheit Ausgabe
    echo "Protokolle, .... .<br>";
    echo "<a href='VF_P_RO_List.php?sk=$sk' target='P-Verwaltung'>Liste der Protokolle</a>";
    echo "</div>";
    
} 

Edit_Separator_Zeile('Mitglieder- E-Mail an');
echo "<div class='w3-row' >"; // Beginn der Einheit Ausgabe
echo "Mitglieder können E-Mails an andere Mitglieder senden, ohne das Sie die E-Mail Adresse kennen.</a><br>";
echo "<a href='VF_M_Mail.php?sk=$sk' target='M-Mail'>Mail an andere Mitglieder senden </a>";
echo "</div>";

Edit_Separator_Zeile('Mitglieder- Auskuft laut DSVGO');
echo "<div class='w3-row' >"; // Beginn der Einheit Ausgabe
echo "<Jedes Mitglied kann sich die im System gespeicherten persönlichne Daten entsprechend der DSVGO selbst anfordern und bekommt sie sofort per E-Mail zugeschickt.<br>";
echo "<a href='VF_M_yellow.php?sk=$sk' target='M-Datenabfrage'>Mitglieder-Daten Auskunft laut DSGVO</a></td></tr>";
echo "</div>";

BA_HTML_trailer();
?>
