<?php

/**
 * Fahrzeug- Liste
 *
 * @author Josef Rohowsky - neu 2018, Änderung 20250624, Umstellung von prototype.js auf jquery
 *
 *
 */
session_start();

const Module_Name = 'F_M';
$module = Module_Name;
$tabelle = 'mu_fahrzeug'; # mu_geraet

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = false; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';
require $path2ROOT . 'login/common/VF_M_tab_creat.lib.php';

$flow_list = false;

$LinkDB_database  = '';
$db = LinkDB('VFH');

/**
 * Aussehen der Listen, Default-Werte, Änderbar (VF_List_Funcs.inc)
 *
 * @global array $_SESSION['VF_LISTE']
 *   - select_string
 *   - SelectAnzeige          Ein: Anzeige der SQL- Anforderung
 *   - SpaltenNamenAnzeige    Ein: Anzeige der Spaltennamen
 *   - DropdownAnzeige        Ein: Anzeige Dropdown Menu
 *   - LangListe              Ein: Liste zum Drucken
 *   - VarTableHight          Ein: Tabllenhöhe entsprechend der Satzanzahl
 *   - CSVDatei               Ein: CSV Datei ausgeben
 */
if (!isset($_SESSION['VF_LISTE'])) {
    $_SESSION['VF_LISTE']    = array(
        "select_string"       => "",
        "SelectAnzeige"       => "Aus",
        "SpaltenNamenAnzeige" => "Aus",
        "DropdownAnzeige"     => "Ein",
        "LangListe"           => "Ein",
        "VarTableHight"       => "Ein",
        "CSVDatei"            => "Aus"
    );
}

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$Inc_Arr = array();
$Inc_Arr[] = "VF_FZ_MuFG_List.php";

/**
 * Variablen für Eigentümer und Sammlung initialisieren
 */
if (! isset($_SESSION['Eigner']['eig_eigner'])) {
    $_SESSION['Eigner']['eig_eigner'] = "";
}

if (! isset($_SESSION[$module]['fmsammlung'])) {
    $_SESSION[$module]['fm_sammlung'] = 'MU';
}

/**
 * Haeder ausgeben, body und form
 */

$title = "Muskelbedientes des Eigentümers " . $_SESSION['Eigner']['eig_eigner'];

$header = "";
$jq = $jqui = true; // JQ-UI laden
$BA_AJA = true; // AJAX- Scripts laden
BA_HTML_header('Muskelbedientes des Eigentümers ' . $_SESSION['Eigner']['eig_eigner'], $header, 'Admin', '150em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

initial_debug();

# var_dump($_GET);
VF_chk_valid(); // Test auf gültigen Login- String

VF_set_module_p(); // Setzen Variable für Update, Berechtigung

foreach ($_POST as $name => $value) {
    $post[$name] = $value;
}

if (isset($post['phase'])) {
    $phase = $post['phase'];
} else {
    $phase = 0;
}

if ($phase == 99) {
    header("Location: /login/VF_C_Menu.php");
}

/**
 * neue Sammlung auswählen
 */
if (isset($_GET['ID'])) {
    if ($_GET['ID'] == "NextSam") {
        $_SESSION[$module]['fm_sammlung'] = "MU";
    } else {
        $sammlg = $_SESSION[$module]['fm_sammlung'] = $_GET['ID'];
    }
}

/**
 * neuen Eigentümer auswählen
 */
if (isset($_GET['ID']) && $_GET['ID'] == "NextEig") {
    if ($_SESSION['VF_Prim']['mode'] == 'Mandanten') {
        $_SESSION['Eigner']['eig_eigner'] = "";
    } else {
        $_SESSION['Eigner']['eig_eigner'] = $_SESSION['VF_Prim']['eignr'];
    }
    $_SESSION[$module]['fm_sammlung'] = "MU";
}

if (isset($post['select_string'])) {
    $select_string = $post['select_string'];
} else {
    $select_string = "";
}

$_SESSION[$module]['$select_string'] = $select_string;

/**
 * Eigentümer- Auswahl (Autocomplete)
 */
if (isset($_POST['eigentuemer'])) {
    $ei_id = $_POST['eigentuemer'];
    VF_Displ_Eig($ei_id);
} else {
    $ei_id = $_SESSION['Eigner']['eig_eigner'];
}


/**
 * Sammlung auswählen, Input- Analyse
 */
if (isset($_POST['level1'])) {
    $response = VF_Multi_Sel_Input();
    if ($response == "" || $response == "Nix") {
        $sammlg = $_SESSION[$module]['fm_sammlung'] = "MU_F";
    } else {
        $sammlg = $_SESSION[$module]['fm_sammlung'] = $response;
    }
}

/**
 * Eigentümerdaten  oder Sammlung neu einlesen
 */
if ($_SESSION['Eigner']['eig_eigner'] == "" || $_SESSION[$module]['fm_sammlung'] == "MU") {

    /**VF_Eig_Ausw
     * neuen Eigentümer auswählen
     */
    if (isset($_SESSION['VF_Prim']['mode']) && $_SESSION['VF_Prim']['mode'] == "Mandanten") {
        if ($_SESSION['Eigner']['eig_eigner'] == "") {
            VF_Auto_Eigent('E', '');
        }
    } else {
        $_SESSION['Eigner']['eig_eigner'] = $_SESSION['VF_Prim']['eignr'];
    }

    if ($_SESSION[$module]['fm_sammlung'] == "MU") {
        /**
         * Parameter für den Aufruf von Multi-Dropdown
         *
         * Benötigt Prototype<script type='text/javascript' src='common/javascript/prototype.js' ></script>";
         *
         *
         * @var array $MS_Init  Kostante mt den Initial- Werten (1. Level, die weiteren Dae kommen aus Tabellen) [Werte array(Key=>txt)]
         * @var string $MS_Lvl Anzahl der gewüschten Ebenen - 2 nur eine 2.Ebene ... bis 6 Ebenen
         * @var string $MS_Opt Name der Options- Datei, die die Werte für die weiteren Ebenen liefert
         *
         * @Input-Parm $_POST['Level1...6']
         */

        $MS_Lvl   = 1; # 1 ... 6
        $MS_Opt   = 1; # 1: SA für Sammlung, 2: AO für Archivordnung

        $MS_Txt = array(
            'Auswahl der Sammlungs- Type (1. Ebene)  ',
            'Auswahl der Sammlungs- Gruppe (2. Ebene)  ',
            'Auswahl der Untergrupppe (3. Ebene) ',
            'Auswahl des Spezifikation (4. Ebene)  '
        );

        switch ($MS_Opt) {
            case 1:
                $in_val = '';
                $MS_Init = VF_Sel_SA_MU ; # VF_Sel_SA_Such|VF_Sel_AOrd
                break;
                /*
                 case 2:
                 $in_val = '07';
                 $MS_Init = VF_Sel_AOrd; # VF_Sel_SA_Such|VF_Sel_AOrd
                 break;
                 */
        }

        $titel  = 'Suche nach der Sammlungs- Beschreibung (- oder Änderung der  angezeigten)';
        VF_Multi_Dropdown($in_val, $titel);

    }

    echo "<button type='submit' name='phase' value='1' class=green>Auswahl abspeichern</button></p>";

} else {

    /**
     * Hier erfolgt die Aufteilung nach Fahrzeug oder Gerät
     */
    VF_upd();

    $sql = $sql_where = $orderBy = "";

    if (substr($_SESSION[$module]['fm_sammlung'], 0, 4)  == 'MU_F') {   # Mukelgezogene - Fahrzeuge

        require "VF_FZ_MuF_List.inc.php";

    } elseif (substr($_SESSION[$module]['fm_sammlung'], 0, 4)  == 'MU_G') {  # Muskel betriebene Geräte

        require "VF_FZ_MuG_List.inc.php";

    }

    $sql .= $sql_where . $orderBy;

    List_Create($db, $sql, '', $tabelle, ''); # die liste ausgeben

    echo "</fieldset>";

    BA_HTML_trailer();
}

/**
 * Diese Funktion verändert die Zellen- Inhalte für die Anzeige in der Liste
 *
 * Funktion wird vom List_Funcs einmal pro Datensatz aufgerufen.
 * Die Felder die Funktionen auslösen sollen oder anders angezeigt werden sollen, werden hier entsprechend geändert
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
function modifyRow(array &$row, $tabelle)
{
    global $path2ROOT, $T_List, $module, $herst_arr;

    $s_tab = substr($tabelle, 0, 8);

    switch ($s_tab) {
        case "fh_eigen":
            $ei_id = $row['ei_id'];
            $row['ei_id'] = "<a href='VF_FZ_MuFG_List.php?ei_id=$ei_id' >" . $ei_id . "</a>";
            break;
        case "mu_fahrz":
            $fm_id = $row['fm_id'];
            $row['fm_id'] = "<a href='VF_FZ_MuF_Edit.php?fm_id=$fm_id' >" . $fm_id . "</a>";
            if ($row['fm_foto_1'] != "") {
                $fm_foto_1 = $row['fm_foto_1'];
                $pict_path = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/MuF/";

                $fm_foto_1 = $row['fm_foto_1'];
                $p1 = $pict_path . $row['fm_foto_1'];

                $row['fm_foto_1'] = "<a href='$p1' target='Bild 1' >  <img src='$p1' alter='$p1' width='150px'><br>  $fm_foto_1  </a>";
            }

            $herst = $row['fm_herst'];

            if (isset($herst_arr[$herst])) {
                $row['fm_herst'] = "Hersteller: " . $herst_arr[$herst];
            }

            $row['fm_sammlg'] .= "<br>".$row['sa_name'];

            break;
        case "mu_gerae":
            $mg_id = $row['mg_id'];
            $row['mg_id'] = "<a href='VF_FZ_MuG_Edit.php?mg_id=$mg_id' >" . $mg_id . "</a>";
            if ($row['mg_foto_1'] != "") {
                $mg_foto_1 = $row['mg_foto_1'];
                $pict_path = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/MuG/";

                $mg_foto_1 = $row['mg_foto_1'];
                $p1 = $pict_path . $row['mg_foto_1'];

                $row['mg_foto_1'] = "<a href='$p1' target='Bild 1' > <img src='$p1' alter='$p1' width='150px'><br>  $mg_foto_1  </a>";
            }

            break;
    }

    return true;
} # Ende von Function modifyRow
