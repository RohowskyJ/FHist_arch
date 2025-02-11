<?php

/**
 * Anzeige der Wappen, Ärmelabzeichen
 * 
 * @author Josef Rohowsky - neu 2019
 */
session_start();

const Module_Name = 'PSA';
$module = Module_Name;

const Tabellen_Name = 'fh_fw_ort_ref';
$tabelle = Tabellen_Name;

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = True;
$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Comm_Funcs.inc';

require $path2ROOT . 'login/common/const.inc';
require $path2ROOT . 'login/common/Funcs.inc';
require $path2ROOT . 'login/common/Edit_Funcs.inc';
require $path2ROOT . 'login/common/List_Funcs.inc';
require $path2ROOT . 'login/common/Tabellen_Spalten.inc';

$flow_list = False;

$LinkDB_database  = '';
$db = LinkDB('VFH');

initial_debug();

VF_chk_valid();

VF_set_module_p();

if (isset($_GET['proj'])) {
    $_SESSION[$module]['proj'] = $_GET['proj'];
}
if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

if ($phase == 99) {
    header("Location: /login/VF_C_Menu.php");
}

/**
 * Aussehen der Listen, Default-Werte, Änderbar (VF_List_Funcs.inc)
 *
 * @global array $_SESSION['VF_LISTE']
 *   - SelectAnzeige          Ein: Anzeige der SQL- Anforderung
 *   - SpaltenNamenAnzeige    Ein: Anzeige der Apsltennamen
 *   - DropdownAnzeige        Ein: Anzeige Dropdown Menu
 *   - LangListe              Ein: Liste zum Drucken
 *   - VarTableHight          Ein: Tabllenhöhe entsprechend der Satzanzahl
 *   - CSVDatei               Ein: CSV Datei ausgeben
 */
if (!isset($_SESSION['VF_LISTE'])) {
    $_SESSION['VF_LISTE']    = array(
        "SelectAnzeige"       => "Aus",
        "SpaltenNamenAnzeige" => "Aus",
        "DropdownAnzeige"     => "Ein",
        "LangListe"           => "Ein",
        "VarTableHight"       => "Ein",
        "CSVDatei"            => "Aus"
    );
}

if (isset($_POST['select_string'])) {
    $select_string = $_POST['select_string'];
} else {
    $select_string = "";
}

$_SESSION[$module]['select_string'] = $select_string;

if (isset($_SESSION[$module]['List_Parm'])) {
    $VF_List_parm = $_SESSION[$module]['List_Parm'];
}

# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================
switch ($_SESSION[$module]['proj']) {
    case "AERM":
        if ($_SESSION[$module]['all_upd']) {
            $htext = 'Orts- und Feuerwehr- Daten- Verwaltung'; // Text für Page- Titel
            $ltext = "Orts-, Fahrzeug-Wappen, Ärmelabzeichen - Administrator "; // Text für Action . Bar
            $T_List_Texte = array(
                "Name" => "Nach Name (Land/Bundesld/Bezirk/Ort/Feuerwehr) Mit Auswahl. ",
                "Alle" => "Alle Daten - mit Auswahl",
                "NeuItem" => "<a href='VF_PS_OV_M_Edit.php?ID=0' > Neuen Datensatz anlegen </a>"
            );
        } else {
            $htext = 'Orts- und Feuerwehr- Daten- Anzeige'; // Text für Page- Titel
            $ltext = "Orts-, Fahrzeug-Wappen, Ärmelabzeichen  "; // Text für Action . Bar
            $T_List_Texte = array(
                "Name" => "Nach Name (Land/Bundesld/Bezirk/Ort/Feuerwehr) Mit Auswahl. ",
                "Alle" => "Alle Daten - mit Auswahl"
            );
        }

        break;

    case "AUSZ":
        if ($_SESSION[$module]['all_upd']) {
            $htext = 'Orts- Struktur Verwaltung, Auszeichnungen, Ehrenzeichen, ... '; // Text für Page- Titel
            $ltext = "Orts-, Auszeichnungen, Ehrenzeichen, ...  "; // Text für Action . Bar
            $T_List_Texte = array(
                "Name" => "Nach Name (Land/Bundesld/Bezirk/Ort/Feuerwehr) Mit Auswahl. ",
                "Alle" => "Alle Daten - mit Auswahl",
                "NeuItem" => "<a href='VF_PS_OV_M_Edit.php?ID=0' > Neuen Datensatz anlegen </a>"
            );
        } else {
            $htext = 'Orts- Struktur Anzeige, Auszeichnungen, Ehrenzeichen, ... '; // Text für Page- Titel
            $ltext = "Orts-, Auszeichnungen, Ehrenzeichen, ...  "; // Text für Action . Bar
            $T_List_Texte = array(
                "Name" => "Nach Name (Land/Bundesld/Bezirk/Ort/Feuerwehr) Mit Auswahl. ",
                "Alle" => "Alle Daten - mit Auswahl"
            );
        }

        break;
    default:
}

# ===========================================================================================================
# Haeder ausgeben
# ===========================================================================================================

$logo = 'NEIN';

HTML_header($htext, 'Auswahl', '', 'Admin', '150em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

echo "<fieldset>";

List_Prolog($module,$T_List_Texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle); # lesen der Tabellen Spalten Informationen

switch ($_SESSION[$module]['proj']) {
    case "AERM":
        switch ($T_List) {
            case "Alle":
                # $Tabellen_Spalten = array('fz_id','fz_invnr','fz_sammlg','fz_name','fz_taktbez','fz_indienstst','fz_ausdienst','fz_herstell_fg','fz_baujahr','fz_bild_1'
                # );

                break;
            default:
                $Tabellen_Spalten = array('fw_id','fw_st_abk','fw_bd_abk','fw_bz_abk','fw_bz_name','fw_ab_nr','fw_ab_name','fw_gd_name','fw_gd_art',
                    'fw_fw_nr','fw_fw_name','fw_fw_typ', 'fw_grdg_dat','fw_end_dat','fw_aermelw','fw_kommentar','fw_ort_komm'
                );
        }
        break;

    case "AUSZ":
        switch ($T_List) {
            case "Alle":
                # $Tabellen_Spalten = array('fz_id','fz_invnr','fz_sammlg','fz_name','fz_taktbez','fz_indienstst','fz_ausdienst','fz_herstell_fg','fz_baujahr','fz_bild_1'
                # );

                break;
            default:
                $Tabellen_Spalten = array('fw_id','fw_st_abk','fw_bd_abk','fw_bz_abk','fw_bz_name','fw_ab_nr','fw_ab_name','fw_gd_name', 'fw_gd_art',
                    'fw_fw_nr','fw_fw_name','fw_fw_typ','fw_grdg_dat','fw_end_dat','fw_auszeich','fw_kommentar','fw_ort_komm'
                );
        }
        break;
    default:
}

$Tabellen_Spalten_style['fw_id'] = $Tabellen_Spalten_style['fw_st_abk'] = $Tabellen_Spalten_style['fw_bd_abk'] = $Tabellen_Spalten_style['fw_ab_nr'] = $Tabellen_Spalten_style['fw_gd_art'] = $Tabellen_Spalten_style['fw_fw_nr'] = $Tabellen_Spalten_style['fw_fw_typ'] = $Tabellen_Spalten_style['fw_grdg_dat'] = $Tabellen_Spalten_style['fw_end_dat'] = $Tabellen_Spalten_style['fw_aermelw'] = $Tabellen_Spalten_style['fw_auszeich'] = $Tabellen_Spalten_style['ei_staat'] = 'text-align:center;';

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">';

switch ($T_List) {
    case "Alle":

        break;

    default:
    /*
     * $List_Hinweise .= '<li>Anmelde Daten ändern: Auf die Zahl in Spalte <q>mi_id</q> Klicken.</li>'
     * . '<li>E-Mail an Mitglied senden: Auf die E-Mail-Adresse in Spalte <q>EMail</q> Klicken.</li>'
     * . '<li>Home Page des Mitglieds ansehen: Auf den Link in Spalte <q>Home_Page</q> Klicken.</li>'
     * . '<li>Forum Teilnehmer Daten ansehen: Auf die Zahl in Spalte <q>lco_email</q> Klicken.</li>';
     */
}
switch ($_SESSION[$module]['proj']) {
    case "AERM":
        $List_Hinweise .= '<li>Orts oder Ärmelabzeichen - Daten ändern: Auf die Zahl in Spalte <q>fw_id</q> Klicken.</li>';
        $List_Hinweise .= '<li>Vorhandene Ärmelabzeichen:  wenn in Spalte <q>fw_aermelw</q> der Buchstabe "J" ist</li>';
        break;
    case 'AUSZ':
        $List_Hinweise .= '<li>Auszeichnungs - Daten ändern: Auf die Zahl in Spalte <q>fw_id</q> Klicken.</li>';
        $List_Hinweise .= '<li>Vorhandene AUs- oder Abzeichen:  wenn in Spalte <q>fw_auszeich</q der Buchstabe "J" ist</li>';
        $List_Hinweise .= '<li>Auszeichnungen von Vereinen Suche nach "<font style=\'color:green\'>VEREINE</font>" (Unterstützungs- Vereine, Wiener Tierschutzverein)</li>';
        $List_Hinweise .= '<li>Auszeichnungen der Internationalen Feuerwehrwettkämpfe nach "<font style=\'color:green\'>CTIF</font>" suchen</li>';
        $List_Hinweise .= '<li>Österr. Rotes Kreuz :  "<font style=\'color:green\'>OERK</font>" suchen</li>';
        $List_Hinweise .= '<li>Die anderen Auszeichungen sind unter dem Herausgeber zu suchen. Staat (und die Vorgänger unter Staat) ÖBFV, Land, LandesKDO unter Land, Gemeinde und FeuerwehrEhrungen der Wehren unter Orts- bzw Feuerwehrnamen</li>';
        $List_Hinweise .= '<li>Script: VF_PS_OV_M_List</li>';
        break;
}

$List_Hinweise .= '</ul></li>';

if (isset($VF_List_parm)) {
    $_SESSION[$module]['List_Parm'] = $VF_List_parm;
}
List_Action_Bar($tabelle,$ltext, $T_List_Texte, $T_List, $List_Hinweise, ''); # Action Bar ausgeben

# ===========================================================================================================
# Je nach ausgewähltem Radio Button das sql SELECT festlegen
# ===========================================================================================================
$sql = "SELECT * FROM $tabelle ";

$sql_where = "";
$orderBy = "";
$depend = "";
$orderBy = " ORDER BY fw_st_abk, fw_bd_abk,fw_bz_name,fw_ab_name,fw_gd_name,fw_fw_name ASC ";

if (! $_SESSION[$module]['all_upd']) {
    if ($_SESSION[$module]['proj'] = "AERM") {
        $depend = "  fw_aermelw='J' AND ";
    }
    if ($_SESSION[$module]['proj'] = "AUSZ") {
        $depend = " fw_auszeich='J' AND ";
    }
}

$sql_where = "";
if ($_SESSION[$module]['select_string'] != '') {
    $select_string = $_SESSION[$module]['select_string'];

    switch ($T_List) {
        case "Alle":
        case "Name":
            $sql_where = "WHERE $depend (fw_st_abk LIKE '$select_string%' OR fw_bd_abk LIKE '$select_string%' OR fw_bz_name LIKE '$select_string%' OR fw_ab_name LIKE '$select_string%'  OR fw_gd_name LIKE '$select_string%'  OR fw_fw_name  LIKE '$select_string%') ";
            break;

        default:
            $sql_where = "WHERE $depend (fw_st_abk LIKE '%$select_string%' OR fw_bd_abk LIKE '%$select_string%' OR fw_bz_name LIKE '%$select_string%' OR fw_ab_name LIKE '%$select_string%'  OR fw_gd_name LIKE '%$select_string%'  OR fw_fw_name  LIKE '%$select_string%') ";
            break;
    }
}

$sql .= $sql_where . $orderBy;

List_Create($db, $sql,'', $tabelle,''); # die liste ausgeben

echo "</fieldset>";

HTML_trailer();


/**
 * Diese Funktion verändert die Zellen- Inhalte für die Anzeige in der Liste
 *
 * Funktion wird vom List_Funcs einmal pro Datensatz aufgerufen.
 * Die Felder die Funktioen auslösen sollen oder anders angezeigt werden sollen, werden hier entsprechend geändert
 *
 *
 * @param array $row
 * @param string $tabelle
 * @return boolean immer true
 *        
 * @global string $path2ROOT String zur root-Angleichung für relative Adressierung
 * @global string $T_List Auswahl der Listen- Art
 * @global string $module Modul-Name für $_SESSION[$module] - Parameter
 */
function modifyRow(array &$row,$tabelle)
{
    global $path2ROOT, $T_List, $module;
# echo "<br>L 285 modrow $tabelle <br>";
    $fw_id = $row['fw_id'];
    $row['fw_id'] = "<a href='VF_PS_OV_M_Edit.php?ID=$fw_id' >" . $fw_id . "</a>";

    return True;
} # Ende von Function modifyRow

?>
