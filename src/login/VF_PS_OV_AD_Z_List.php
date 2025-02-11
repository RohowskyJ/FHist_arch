<?php

/**
 * Auszeichnungs- Listen, Hauptteil
 * 
 * @author Josef Rohowsky - neu 2019
 * 
 * 
 */
session_start();

const Module_Name = 'PSA';
$module = Module_Name;
$tabelle = 'fh_fw_ort_ref';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';
require $path2ROOT . 'login/common/VF_F_tab_creat.lib.php';

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
$LinkDB_database = '';
$db = LinkDB('Mem'); // Connect zur Datenbank

VF_chk_valid();
VF_set_module_p(); 


if (isset($_GET['proj'])) {
    $_SESSION[$module]['proj'] = $_GET['proj'];
} // else {$_SESSION[$module]['proj']="AERM";}   

if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}
 
if ($phase == 99) {
    header("Location: /login/VF_C_Menu_css_v2.php");
}
 
if (isset($_POST['a_ausw'])) {
    $_SESSION['AZ_List']['ausw'] = $_POST['a_ausw'];
}

if (isset($_POST['select_string'])) {
    $select_string = $_POST['select_string'];
} else {
    $select_string = "";
}

$_SESSION[$module]['select_string'] = $select_string;

if (isset($_POST['a_ausw'])) {
    $a_ausw = $_POST['a_ausw'];
} else {
    $a_ausw = "";
}

# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================

$htext = 'Anzeige der Auszeichnugen , Ehrenzeichen '; // Text für Page- Titel
$ltext = "Liste der Auszeichnungen, Ehrenzeichen, ...  "; // Text für Action . Bar
$T_list_texte = array(
    "NurG" => "nur die Auszeichnugen den Suchbegriff betreffend",
    "Alle" => "Alle Auszeichnungen unter dem Suchbegriff (alle unter dem FW_ID gespeicherten Daten)"
);

# ===========================================================================================================
# Haeder ausgeben
# ===========================================================================================================

BA_HTML_header($htext, '', 'Form', '90em', 'noprint'); # Parm: Titel,Subtitel,HeaderLine,Type,width

List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$sql = "SELECT * FROM $tabelle WHERE fw_id = '" . $_SESSION[$module]['fw_id'] . "'";

$return = SQL_QUERY($db, $sql);

if ($return) {
    $row = mysqli_fetch_assoc($return);
    $_SESSION['AZ_List']['st'] = $row['fw_st_abk'];
    $_SESSION['AZ_List']['bd'] = $row['fw_bd_abk'];
    $_SESSION['AZ_List']['bz'] = $row['fw_bz_name'];
    $_SESSION['AZ_List']['gd'] = $row['fw_gd_name'];
    $_SESSION['AZ_List']['ab'] = $row['fw_ab_name'];
    $_SESSION['AZ_List']['fw'] = $row['fw_fw_name'];
    $_SESSION['AZ_List']['ft'] = $row['fw_fw_typ'];
    $_SESSION['AZ_List']['fo'] = $row['fw_kommentar'];
} else {
    echo "Keine Daten ausgewählt - Abbruch <br/>";
    exit();
}
$tab_a_besch = "az_beschreibg";
$tab_a_desc = "az_adetail";
$tab_a_ausz = "az_auszeich";

if ($_SESSION['AZ_List']['st'] == "CTIF") {

    $tab_a_ausz = "az_ausz_ctif";
    $T_List = "Alle";
} elseif ($_SESSION['AZ_List']['st'] == "VEREINE") {

    $tab_a_ausz = "az_ausz_ve";
} else {}

switch ($T_List) {
    case "NurG": // nur die Auszeichnungen FW_Auswahl drucken
                  # $Tabellen_Spalten = array('fz_id','fz_invnr','fz_sammlg','fz_name','fz_taktbez','fz_indienstst','fz_ausdienst','fz_herstell_fg','fz_baujahr','fz_bild_1'
                  # );

        break;

    case "Alle": // alle unter-Sektionen (bei Staat alle Daten der Bundesländer) mitdrucken.
                  # $Tabellen_Spalten = array('fz_id','fz_invnr','fz_sammlg','fz_name','fz_taktbez','fz_indienstst','fz_ausdienst','fz_herstell_fg','fz_baujahr','fz_bild_1'
                  # );

        break;
}

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Orts - Daten ändern: Auf die Zahl in Spalte <q>fw_id</q> Klicken.</li>' . '<li>Script VF_PS_AD_Z_List</li>';
switch ($T_List) {
    case "Alle":
        break;

    default:

}
$List_Hinweise .= '</ul></li>';
$sel = "";
$zus_ausw = "";

$zus_ausw .= "<b>Land : " . $_SESSION['AZ_List']['st'];

if ($_SESSION['AZ_List']['bd'] !== "") {
    $zus_ausw .= ", BundesLand: " . $_SESSION['AZ_List']['bd'];
}
if ($_SESSION['AZ_List']['bz'] !== "") {
    $zus_ausw .= ", Bezirk: " . $_SESSION['AZ_List']['bz'];
}
if ($_SESSION['AZ_List']['gd'] !== "") {
    $zus_ausw .= ", Gemeinde: " . $_SESSION['AZ_List']['gd'];
}
if ($_SESSION['AZ_List']['ab'] !== "") {
    $zus_ausw .= ", Abschnitt: " . $_SESSION['AZ_List']['ab'];
}
if ($_SESSION['AZ_List']['fw'] !== "") {
    $zus_ausw .= ", Wehr: " . $_SESSION['AZ_List']['fw'];
}
if ($_SESSION['AZ_List']['ft'] !== "") {
    $zus_ausw .= ",<br/> Typ: " . $_SESSION['AZ_List']['ft'];
}
if ($_SESSION['AZ_List']['fo'] !== "") {
    $zus_ausw .= ", Organisaton: " . $_SESSION['AZ_List']['fo'];
}
$zus_ausw .= "</b>";
$l_tit = $zus_ausw;
$zus_ausw .= "<br/><label for='a_ausw'>Auszeichnungs-Art</label> &nbsp; ";
$zus_ausw .= "<select name='a_ausw' id='a_ausw'>";
$zus_ausw .= "<option value='Alle'>Alle Arten";
foreach (VF_Ausz as $key => $value) {
    $zus_ausw .= "<option value='$key'>$value";
}
$zus_ausw .= "</select>";

List_Action_Bar($tab_a_ausz,$ltext, $T_list_texte, $T_List, $List_Hinweise, $zus_ausw); # Action Bar ausgeben

# ===========================================================================================================
#
# Je nach ausgewähltem Radio Button das sql SELECT festlegen
#
# ===========================================================================================================
$sql = "SELECT * FROM $tab_a_besch ";
$sql_where = "";
$orderBy = "";

$sql_where = " WHERE ab_fw_id= '" . $_SESSION[$module]['fw_id'] . "' ";

if ($a_ausw != "Alle") {
    $sql_where .= " AND ab_art='$a_ausw' ";
}

$orderBy = "ORDER BY  ab_stifter, ab_stiftg_datum, ab_art ASC";

echo "</fieldset></div>";
$sql .= $sql_where . $orderBy;

$return = mysqli_query($db, $sql);

if ($debug) {
    echo "<pre class=debug>VF_PS_OV_AD_Z_List.php <br>L 0265 vor auswahl ";
    print_r($_SESSION['AZ_List']);
    echo "<br> \$sql $sql</pre>";
}

$tab_data = "";
$tab_col_width = "";

if ($_SESSION['AZ_List']['st'] == "CTIF") {
    require "VF_PS_OV_AD_Z_List_CTIF.inc.php";
} elseif ($_SESSION['AZ_List']['st'] == "VEREINE") {
    require "VF_PS_OV_AD_Z_List_VEREINE.inc.php";
} else {
    require "VF_PS_OV_AD_Z_List_Standard.inc.php";
}

# echo "\$tab_data $tab_data <br>\$tab_col_width $tab_col_width <br>";
echo "<input type='hidden' name='tab_data' value='$tab_data'>";
echo "<input type='hidden' name='tab_col_width' value='$tab_col_width' >";

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
 * @global string $path2ROOT String zur root-Angleichung für relative Adressierung
 * @global string $T_List Auswahl der Listen- Art
 * @global string $module Modul-Name für $_SESSION[$module] - Parameter
 */
function modifyRow(array &$row,$tabelle)
{
    global $path2ROOT, $T_List, $module;

    # print_r($_SESSION[$module]['all_upd']);
    if ($_SESSION[$module]['all_upd']) {
        $fw_id = $row['fw_id'];
        $row['fw_id'] = "<a href='VF_PS_OV_M_Edit.php?ID=$fw_id' >" . $fw_id . "</a>";
    }

    return True;
} # Ende von Function modifyRow

?>
