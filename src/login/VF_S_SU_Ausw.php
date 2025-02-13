<?php

/**
 * Auswahl nach Suchbegriffen zur Anzeige
 *
 * @author Josef Rohowsky - neu 2018
 *
 * Abfrage nach was / mit welchen Möglichkeiten gesucht werden soll
 *
 *
 *
 */
session_start();

const Module_Name = 'SUC';
$module = Module_Name;
$tabelle = '';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = False; # Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/BA_AJAX_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';

$flow_list = False;

$LinkDB_database  = '';
$db = LinkDB('VFH');

$item = "";

$form_start = True;
$prot = True;
$header = "";

BA_HTML_header('Suche nach Suchbegriffen', $header, 'Form', '150em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

initial_debug();

/**
 * Aussehen der Listen, Default-Werte, Änderbar (VF_List_Funcs.inc)
 *
 * @global array $_SESSION['VF_LISTE']
 *   - SelectAnzeige          Ein: Anzeige der SQL- Anforderung
 *   - SpaltenNamenAnzeige    Ein: Anzeige der Apsltennamen
 *   - DropDownAnzeige        Ein: Anzeige Dropdown Menu
 *   - LangListe              Ein: Liste zum Drucken
 *   - VarTableHight          Ein: Tabllenhöhe entsprechend der Satzanzahl
 *   - CSVDatei               Ein: CSV Datei ausgeben
 */
if (!isset($_SESSION['VF_LISTE'])) {
    $_SESSION['VF_LISTE']    = array(
        "SelectAnzeige"       => "Aus",
        "SpaltenNamenAnzeige" => "Aus",
        "DropDownAnzeige"     => "Ein",
        "LangListe"           => "Ein",
        "VarTableHight"       => "Ein",
        "CSVDatei"            => "Aus"
    );
}

VF_chk_valid();
VF_set_module_p();

$_SESSION[$module]['su_Umf'] = "A,I,G,F"; // Such- Umfang: A = Alle = 'I,G,F' oder einzelne davon
$_SESSION[$module]['su_Eig'] = "A";
$_SESSION[$module]['eig_ausw'] = "Alle Eigentümer";
$_SESSION[$module]['su_Su'] = "SB";

if (! isset($_SESSION[$module]['Get_Next_Ord'])) {
    $_SESSION[$module]['Get_Next_Ord'] = True;
}
if (! isset($_SESSION[$module]['ArOrd_Name'])) {
    $_SESSION[$module]['ArOrd_Name'] = "Anzeiger der ausgewählten Daten";
}
if (! isset($_SESSION[$module]['Ord_Ausw'])) {
    $_SESSION[$module]['Ord_Ausw'] = "0,0,0,0";
}
if (! isset($_SESSION[$module]['List_Sel'])) {
    $_SESSION[$module]['List_Sel'] = "";
}

$_SESSION[$module]['sammlung'] = "";


if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

if ($phase == 99) {
    header("Location: /login/VF_C_Menu.php");
}

if (!isset($_POST['suche_bei']) OR $_POST['suche_bei'] == "allen'") {
    $eigen = "Alle Eigentümer";
} else {
    if (isset($_POST['eigentmr'])) {
        $eigen = "Eigentümer " . $_POST['eigentmr'];
    } else {
        $eigen = "Alle Eigentümer";
    }
}

$err_Msg = "";

if ($phase == 1) {
    #$debug = True;
    #var_dump($_POST);
    foreach ($_POST as $name => $value) {
        if ($name == 'suchumfang') {continue;}
        $neu[$name] = mysqli_real_escape_string($db, $value);
    }
    # var_dump($neu);
    switch ($neu['suchb_ausw']) {
        case "sammlung":
            if ($neu['level1'] == "Nix" ) {
                $err_Msg = "Für suche nach Sammlung muss eine Sammlunng ausgesucht werden";
            }
            break;
        case "findbuch":
            if ($neu['suchtext'] == "") {
                $err_Msg = "Für suche nach Findbuch muss ein Suchtext  im entsprechenden Feld eingegeben werden";
            }
            break;
        case "namen":
            if ($neu['suchname'] == "") {
                $err_Msg = "Für suche nach Namen muss ein Suchtext  im entsprechenden Feld eingegeben werden";
            }
            break;
        case "ffname":
            if ($neu['suchffname'] == "") {
                $err_Msg = "Für suche nach Feuerwehrnamen muss ein Suchtext im entsprechenden Feld eingegeben werden";
            }
            break;
        case "autnam":
            if ($neu['suchztauth'] == "") {
                $err_Msg = "Für suche nach Authoren muss ein Suchtext im entsprechenden Feld eingegeben werden";
            }
            break;
    }

    if ($err_Msg != "") { 
        $phase = 0;
    }
}

switch ($phase) {
    case 0:
        require ('VF_S_SU_Ausw_ph0.inc.php');
        break;
    case 1:
        require 'VF_S_SU_Ausw_ph1.inc.php';
        break;
}

echo "</div>";
# echo "<script type='text/javascript' src='VF_C_Suchbegr_Funcs.js'></script>";
# echo "<script type='text/javascript' src='VF_C_AOrd_Funcs.js'></script>";

BA_HTML_trailer();

?>
