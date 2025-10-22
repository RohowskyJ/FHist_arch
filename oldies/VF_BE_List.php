<?php

/**
 * Liste der Veranstaltungsberichte
 *
 * @author Josef Rohowsky - neu 2018
 *
 */
session_start();

# die SESSION aktivieren
const Module_Name = 'OEF';
$module = Module_Name;
$tabelle = 'vb_bericht_4';  

/**
 * Pfad zum Root- Verzeichnis, wird abgelöst
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";


$Inc_Arr = array();
$Inc_Arr[] = "VF_BE_List.php";

/**
 * Debug- Option
 *
 * @var boolean $debug Ausgabe von vordefinierten Debug Informationen True - Ein, False - Aus
 */
$debug = False;

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
#require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';
#require $path2ROOT . 'login/common/VF_F_tab_creat.lib.php';

$flow_list = False;

$LinkDB_database = '';
$db = LinkDB('VFH');

initial_debug();

VF_chk_valid();

VF_set_module_p();

/**
 * Aussehen der Listen, Default-Werte, Änderbar (VF_List_Funcs.inc)
 *
 * @global array $_SESSION['VF_LISTE']
 *         - select_string
 *         - SelectAnzeige Ein: Anzeige der SQL- Anforderung
 *         - SpaltenNamenAnzeige Ein: Anzeige der Apsltennamen
 *         - DropdownAnzeige Ein: Anzeige Dropdown Menu
 *         - LangListe Ein: Liste zum Drucken
 *         - VarTableHight Ein: Tabllenhöhe entsprechend der Satzanzahl
 *         - CSVDatei Ein: CSV Datei ausgeben
 */
if (! isset($_SESSION['VF_LISTE'])) {
    $_SESSION['VF_LISTE'] = array(
        "SelectAnzeige" => "Aus",
        "SpaltenNamenAnzeige" => "Aus",
        "DropdownAnzeige" => "Ein",
        "LangListe" => "Aus",
        "VarTableHight" => "Ein",
        "CSVDatei" => "Aus"
    );
}

VF_Count_add();
// Wennn Aufruf von Offener Seite kommt - NUR LESE-Berechtigung!

if (! isset($_SESSION[$module]['all_upd'])) {
    $_SESSION[$module]['all_upd'] = False;
}

if (isset($_GET['Act'])) {
    IF ( $_GET['Act'] == 1) {
        $_SESSION[$module]['Act'] = $Act = $_GET['Act'];
        VF_chk_valid();
        VF_set_module_p();
    } else {
        if (!isset($_SESSION[$module]['Act'])){
            $_SESSION[$module]['Act'] = $Act = 0;
            $_SESSION['VF_Prim']['p_uid'] = 999999999;
            $_SESSION[$module]['all_upd'] = False;
        }
    }
}

$_SESSION[$module]['Fo']['FOTO'] = True;
$_SESSION[$module]['Fo']['BERI'] = False;

if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

if ($phase == 99) {
    if ($_SESSION['VF_Prim']['p_uid'] == 999999999) {
        header("Location: ../");
    } else {
        header("Location: /login/VF_C_Menu.php");
    }
}

VF_Count_add();

if (isset($_GET['ID'])) {
    if ($_GET['ID'] == "NextEig") {
        $_SESSION['Eigner']['eig_eigner'] = "";
        $eig_header = "Eigentümer/Urheber Auswahl";
 ##       require ('VF_FO_U_Select_List.inc.php');
        VF_Displ_Eig($_SESSION['Eigner']['eig_eigner']);
    } else {
 ##       require "VF_BE_List.inc.php";
    }
}

if (! isset($_SESSION['Eigner']['eig_eigner'])) {
    $_SESSION['Eigner']['eig_eigner'] = "";
}
if (isset($_POST['select_string'])) {
    $select_string = $_POST['select_string'];
} else {
    $select_string = "";
}

$_SESSION[$module]['$select_string'] = $select_string;

# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================
if ($_SESSION['VF_Prim']['p_uid'] == 999999999) {
    $T_list_texte = array(
        "Alle" => "Alle verfügbaren Veranstaltungs- Berichte "
    );
} else {
    $T_list_texte = array(
        "Alle" => "Alle verfügbaren Veranstaltungs- Berichte ",
        "NextEig" => "<a href='VF_BE_List.php?ID=NextEig' > anderen Eigentümer auswählen </a>",
        "NeuItem" => "<a href='VF_BE_Edit.php?vb_flnr=0' > Neuen Datensatz anlegen </a>"
    );
}

if (isset($_GET['ei_id'])) {
    $_SESSION['Eigner']['eig_eigner'] = $ei_id = $_GET['ei_id'];
} else {
    $ei_id = $_SESSION['Eigner']['eig_eigner'];
}
VF_Displ_Eig($ei_id);
VF_Displ_Urheb($ei_id);

# ===========================================================================================================
# Haeder ausgeben
# ===========================================================================================================

BA_HTML_header('Veranstaltungs- Berichte- Verwaltung', '', 'Admin', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width
#var_dump($_SESSION[$module]['URHEBER']['BE']);
echo "<fieldset>";
List_Prolog($module, $T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

/**
 * *******************************************************************
 * Urheber- Listen Array anlegen
 * *******************************************************************
 */

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle); # lesen der Tabellen Spalten Informationen

$Tabellen_Spalten = array(
    'vb_flnr',
    'vb_datum',
    'vb_titel',
    'vb_foto'
);

$Tabellen_Spalten_style['vb_flnr'] = $Tabellen_Spalten_style['vb_uid'] = 'text-align:center;';

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Berichts - Daten ändern: Auf die Zahl in Spalte <q>vb_lfnr</q> Klicken.</li>' . '<li>Script: VF_BE_List.php</li>';

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
$List_Hinweise .= '</ul></li>';

List_Action_Bar($tabelle, "Veranstaltungs- Berichte ", $T_list_texte, $T_List, $List_Hinweise, ''); # Action Bar ausgeben

# ===========================================================================================================
# Je nach ausgewähltem Radio Button das sql SELECT festlegen
# ===========================================================================================================
$tabelle = "vb_bericht_4";
$sql = "SELECT * FROM $tabelle ";
switch ($T_List) {
    case "Alle":
        $sql_where = " ";
        $orderBy = ' ORDER BY vb_flnr';
        break;
    # case "Thema" : $sql_where="WHERE dk_Thema = '$doc_art'"; $orderBy = ' ORDER BY dk_nr'; break;

    default:
       BA_HTML_trailer();
        exit(); # wenn noch nix gewählt wurde >> beenden
}

# $select_string = $_SESSION[$module]['select_string'];
if ($select_string != '') {
    switch ($T_List) {
        case "Alle":
            $sql_where = " ";
            break;
        # case "Thema" : $sql_where = " WHERE dk_Thema ='$doc_art'"; break;
        # default : $sql_where .= " AND mi_name LIKE '$select_string%'";
    }
}
$sql .= $sql_where . $orderBy;

$New_Link = "";
if ($_SESSION[$module]['all_upd']) {
    # $New_Link = "<a href='VF_M_Edit_v3.php?$Parm_Call&ID=0' > Neu</a>";
}
/**
 * Phase, in der die EIngabe in der Tabelle landen soll
 *
 * @var integer $TabButton
 */
$TabButton = "1|green|Bilder für den Bericht speichern.||True"; # 0: phase, 1: Farbe, 2: Text, 3: Rücksprung-Link, 5: True Änderungen abspeichern
List_Create($db, $sql, '', $tabelle, ''); # die liste ausgeben
echo "</fieldset>";
BA_HTML_trailer();

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
 * @global string $path2VF String zur root-Angleichung für relative Adressierung
 * @global string $T_List Auswahl der Listen- Art
 * @global string $module Modul-Name für $_SESSION[$module] - Parameter
 */
function modifyRow(array &$row, $tabelle)
{
    global $path2VF, $T_List, $module;
var_Dump($row);
    $s_tab = substr($tabelle, 0, 8);

    switch ($s_tab) {
        case "fh_eigen":
            $ei_id = $row['ei_id'];
            $row['ei_id'] = "<a href='VF_BE_List.php?ei_id=$ei_id&Act=1&sk=" . $_SESSION['VF_Prim']['SK'] . "' >" . $ei_id . "</a>";
            break;

        case "vb_beric":
            $pict_path = $path2VF . "login/AOrd_Verz/";

            $vb_flnr = $row['vb_flnr'];

            if ($_SESSION['VF_Prim']['p_uid'] == 999999999) {
                $row['vb_flnr'] = "<a href='VF_BE_Bericht.php?vb_flnr=" . $vb_flnr . "'  >" . $vb_flnr . " Bericht</a>";
            } else {
                $row['vb_flnr'] = "<a href='VF_BE_Edit.php?vb_flnr=" . $vb_flnr . "'  >" . $vb_flnr . "</a><hr><a href='VF_BE_Bericht.php?vb_flnr=" . $vb_flnr . "'  >" . $vb_flnr . " Bericht</a>";
            }

            if ($row['vb_foto'] != "") {
                $d_arr = explode("-", $row['vb_datum']);
                $v_date = $d_arr[0] . $d_arr[1] . $d_arr[2];
                VF_Displ_Urheb($row['vb_urheb']);
                $vb_foto = $row['vb_foto'];
                $DsName = $pict_path . $row['vb_urheb'] . "/09/06/$v_date/" . $row['vb_foto'];
                $image1 = "<img src='$DsName' alt='Foto1' width='100px'/> ";
                $row['vb_foto'] = "<a href=\"$DsName\" target='Foto'> $image1 </a> "; # <br>Urheber: " . $row['fo_Urheber'];
            }
            break;
        case "fo_urheb":
            $fm_id = $row['fm_id'];
            $row['fm_id'] = "<a href='VF_FO_U_Edit.php?fm_id=$fm_id' >" . $fm_id . "</a>";
            $fm_eigner = $row['fm_eigner'];
            if ($row['fm_typ'] == "F") {
                $row['fm_eigner'] = "<a href='VF_FO_List.php?fm_eigner=$fm_eigner&typ=F&fm_id=$fm_id'  target='Foto'>" . $fm_eigner . "  </a> Fotos";
            } elseif ($row['fm_typ'] == "V") {
                $row['fm_eigner'] = "<a href='VF_FO_List.php?fm_eigner=$fm_eigner&typ=V&fm_id=$fm_id'  target='Video'>" . $fm_eigner . " </a> Videos";
            } else {
                $row['fm_eigner'] = "<a href='VF_FO_List.php?fm_eigner=$fm_eigner&typ=A&fm_id=$fm_id'  target='Audio'>" . $fm_eigner . " </a> Audios";
            }
            break;
    }

    return True;
} # Ende von Function modifyRow
?>