<?php

/**
 * Liste der Dokumentationen
 * 
 * @author Josef Rohowsky - neu 2018 
 * 
 */
session_start();

$module = 'OEF';
$sub_mod = 'DO';

$tabelle = 'fh_dokumente';

/**
 * Angleichung an den Root-Path
 *f
 * @var string $path2ROOT
 */
$path2ROOT = "../";

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$_SESSION[$module]['Inc_Arr']  = array();
$_SESSION[$module]['Inc_Arr'][] = "VF_O_DO_List.php"; 

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';

$flow_list = False;

$LinkDB_database  = '';
$db = LinkDB('VFH');

# ===========================================================================================================
# Haeder ausgeben
# ===========================================================================================================

BA_HTML_header('Dokumentations- Verwaltung', '', 'Admin', '150em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

echo "<main>";
echo "<frameset>";

initial_debug();

/**
 * Aussehen der Listen, Default-Werte, Änderbar (VF_List_Funcs.inc)
 *
 * @global array $_SESSION['VF_LISTE']
 *   - DropdownAnzeige        Ein: Anzeige Dropdown Menu
 *   - LangListe              Ein: Liste zum Drucken
 *   - VarTableHight          Ein: Tabllenhöhe entsprechend der Satzanzahl
 *   - CSVDatei               Ein: CSV Datei ausgeben
 */
if (!isset($_SESSION['VF_LISTE'])) {
    $_SESSION['VF_LISTE']    = array(
        "DropdownAnzeige"     => "Aus",
        "LangListe"           => "Ein",
        "VarTableHight"       => "Ein",
        "CSVDatei"            => "Aus"
    );
}

$sel_thema = 0;
// Wennn Aufruf von Offener Seite kommt - NUR LESE-Berechtigung!

if (! isset($_SESSION[$module]['all_upd'])) {
    $_SESSION[$module]['all_upd'] = False;
}

VF_chk_valid();
VF_set_module_p();

if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

if ($phase == 99) {
    header("Location: /login/VF_C_Menu.php");
}

$csv_DSN = "";
$NoSelects = True;
$NoSort = False;
$lowHeight = False;

VF_Count_add();

if (isset($_POST['select_string'])) {
    $select_string = $_POST['select_string'];
} else {
    $select_string = "";
}

$_SESSION[$module]['$select_string'] = $select_string;

if (isset($_GET['thema'])) {
    $thema = $_GET['thema'];
}
if (!isset($thema)) {
    $thema = "0";
}
if (isset($_GET['sel_thema'])) {
    $sel_thema = $_GET['sel_thema'];
}
if (isset($_POST['sel_thema'])) {
    $sel_thema = $_POST['sel_thema'];
}
if (isset($_GET['T_List']) and $_GET['T_List'] == "Thema") {
    $sel_thema = 0;
}

# ============================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================

$T_list_texte = array(
    "Alle" => "Alle verfügbaren Dokumente (Auswahl)",
    "Thema" => "<a href='VF_O_DO_List.php?thema=1' > Thema wechseln </a>"
);

$NeuRec = " &nbsp; &nbsp; &nbsp; &nbsp; <a href='VF_O_DO_Edit.php?ID=0' > Neuen Datensatz anlegen </a>";

List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle); # lesen der Tabellen Spalten Informationen

$Tabellen_Spalten = array(
    'dk_nr',
    'dk_Thema',
    'dk_Titel',
    'dk_Author',
    'dk_Urspr',
    'dk_Dsn',
    'dk_sg'
);

$Tabellen_Spalten_style['dk_nr'] = 
$Tabellen_Spalten_style['bu_fw_st_abk'] = $Tabellen_Spalten_style['ei_staat'] = 'text-align:center;';

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Buch - Daten ändern: Auf die Zahl in Spalte <q>dk_nr</q> Klicken.</li>';
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
# echo "L 0138 \$sel_thema $sel_thema <br>";

$Sel_Dok = "";
if ($thema == 1) {
    $sel = VF_Doku_Art; 
    $Sel_Dok = "Themen- Auswahl: &nbsp; &nbsp;  <select name='sel_thema' onchange= submitForm() > ";
    foreach ($sel as $key => $value) {
        $Sel_Dok .= "<option value='$key'>$value</option>";
    }
    $Sel_Dok .= "</select><br>";
}

List_Action_Bar($tabelle," Dokumentationen ", $T_list_texte, $T_List, $List_Hinweise, $Sel_Dok); # Action Bar ausgeben
echo "<divclass='w3-lightblue'>";
echo $Sel_Dok;
echo "</div>";

# ===========================================================================================================
# Je nach ausgewähltem Radio Button das sql SELECT festlegen
# ===========================================================================================================
$sql = "SELECT * FROM $tabelle ";
$sql_where = "";
if ($sel_thema != "0" ) {
    $sql_where = " WHERE dk_Thema = '$sel_thema' ";
    if ($sel_thema == 'kb') {
        $sql_where = " WHERE dk_thema = 'kb' ORDER BY dk_sg";
    }
    if ($sel_thema == 1 || $sel_thema == 2) {
        $sql_where = " WHERE dk_sg = '$sel_thema' ";
    }
}
$sql .= $sql_where;

echo "<div class='toggle-SqlDisp'>";
echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'O DO List vor list_create $sql </pre>";
echo "</div>";

$New_Link = "";

List_Create($db, $sql,'', $tabelle,''); # die liste ausgeben

echo "</frameset>";
echo "</main>";

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

    if ($_SESSION[$module]['all_upd']) {

        $dk_nr = $row['dk_nr'];

        $row['dk_nr'] = "<a href='VF_O_DO_Edit.php?ID=" . $dk_nr . "' >" . $dk_nr . "</a>";
    }

    $row['dk_Titel'] = str_replace('.', '.<wbr>', $row['dk_Titel']);

    $dokPath = "AOrd_Verz/Downloads/";
    if ($row['dk_Dsn'] != "") {

        $dk_Dsn = $row['dk_Dsn'];
        $DsName = $row['dk_Path2Dsn'] . $row['dk_Dsn'];
        $row['dk_Dsn'] = "<a href='$dokPath$DsName' target='Doc 1'> $dk_Dsn </a>";
    }
    if ($row['dk_Dsn_2'] != "") {

        $dk_Dsn_2 = $row['dk_Dsn_2'];
        $DsName_2 = $row['dk_Path2Dsn'] . $row['dk_Dsn_2'];
        $row['dk_Dsn'] .= "<br><a href='$dokPath$DsName_2' target='Dok 2'> $dk_Dsn_2 </a>";
    }

    if ($row['dk_Thema'] != "") {
        $dk_Thema = $row['dk_Thema'];
        $text = VF_Doku_Art[$dk_Thema]; # VF_Disp_Dok_Thema($dk_Thema, "");
        $row['dk_Thema'] = $text;
    }
    if ($row['dk_url'] != "") {
        $url = $row['dk_url'];
        $row['dk_Dsn'] .= "<br><a href='$url' target='ext. Seite' >$url</a> ";
    }
    # -------------------------------------------------------------------------------------------------------------------------
    return True;
} # Ende von Function modifyRow
?>
