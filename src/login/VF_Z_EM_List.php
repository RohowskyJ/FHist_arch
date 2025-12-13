<?php

/**
 * Automatische Benachrichtigung für ADMINS bei Änderungen
 * 
 * @author Josef Rohowsky - neu 2023
 * 
 */
session_start(); # die SESSION aktivieren

$module  = 'ADM';
$sub_mod = 'all';

$tabelle = 'fh_m_mail';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';
require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';

$flow_list = False;

$LinkDB_database = '';
$db = LinkDB('VFH');

VF_chk_valid();

VF_Count_add();

$mitgl_nrs = "";
$mitgl_einv_n = 0;

if (isset($_POST['select_string'])) {
    $select_string = $_POST['select_string'];
} else {
    $select_string = "";
}
$_SESSION['VF']['$select_string'] = $select_string;

initial_debug();

/**
 * Aussehen der Listen, Default-Werte, Änderbar (VF_List_Funcs.inc)
 *
 * @global array $_SESSION['VF_LISTE']
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
        "LangListe" => "Ein",
        "VarTableHight" => "Ein",
        "CSVDatei" => "Aus"
    );
}

$title = "E-Mail- Empfänger für automatische E-Mails ";

$jq_tabsort = $jq = true;
BA_HTML_header($title, '', 'Admin', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

initial_debug();

# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================
$T_list_texte = array(
    "Alle" => "Alle Benutzer "
);
$NeuRec = "<a href='VF_Z_EM_Edit.php?ID=0' >Neuen Benutzer eingeben</a>";
# ===========================================================================================================
# Haeder ausgeben
# ===========================================================================================================

List_Prolog($module, $T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle);

switch ($T_List) {
    case "Alle":
        $Tabellen_Spalten = array(
            'em_flnr',
            'em_mitgl_nr',
            'em_mail_grp',
            'mi_name',
            'mi_email',
            'em_active',
            'em_uidaend',
            'em_aenddat'
        );
        $Tabellen_Spalten_COMMENT['mi_name'] = "Mitgiedsname";
        $Tabellen_Spalten_COMMENT['mi_email'] = "E-Mail Adresse";
        break;

    default:
        $Tabellen_Spalten = array(
            'em_flnr',
            'em_mitgl_nr',
            'em_mail_grp',
            'mi_name',
            'mi_email',
            'em_active',
            'em_uidaend',
            'em_aenddat'
        );
        $Tabellen_Spalten_COMMENT['mi_name'] = "Mitgiedsname";
        $Tabellen_Spalten_COMMENT['mi_email'] = "E-Mail Adresse";
        break;
}

$Tabellen_Spalten_style['em_flnr'] = 
$Tabellen_Spalten_style['em_mitgl_nr'] = 'text-align:center;';

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Mitglieder Daten ändern: Auf die Zahl in Spalte <q>be_id</q> Klicken.</li>';
switch ($T_List) {
    case "Alle":

    case "AdrList":
        break;

    default:
}
$List_Hinweise .= '</ul></li>';

List_Action_Bar($tabelle, $title, $T_list_texte, $T_List, $List_Hinweise); # Action Bar ausgeben

# ===========================================================================================================
# Je nach ausgewähltem Radio Button das sql SELECT festlegen
# ===========================================================================================================
$act_datum = date("Y-m-d");
$sql_where = "";
$orderBy = "";
$sql = "SELECT * FROM $tabelle LEFT JOIN fh_mitglieder ON $tabelle.em_mitgl_nr = fh_mitglieder.mi_id";

switch ($T_List) {
    # case "Alle" : $sql_where=" WHERE be_name!='' "; $orderBy = ' ORDER BY be_id '; break;
    # case "AdrList" : $sql_where=" WHERE ((be_sterbdat<='0000-00-00' AND be_abgdat<='0000-00-00') OR (be_sterbdat IS NOT NULL AND be_abgdat IS NULL) ) "; $orderBy = ' ORDER BY be_name'; break;

    # default: VF_HTML_trailer(); exit; # wenn noch nix gewählt wurde >> beenden
}

if ($select_string != '') {
    switch ($T_List) {

        # default : $sql_where .= " AND (be_org_name LIKE '%$select_string%' OR be_name LIKE '%$select_string%' )";
    }
}
$sql .= $sql_where . $orderBy;

# ===========================================================================================================
# Die Daten lesen und Ausgeben
# ===========================================================================================================

List_Create($db, $sql,'', $tabelle,''); # die liste ausgeben

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
function modifyRow(array &$row, $tabelle)
{
    global $path2ROOT, $T_List;

    $em_flnr = $row['em_flnr'];
    $row['em_flnr'] = "<a href='VF_Z_EM_Edit.php?ID=$em_flnr'>$em_flnr</a>";

    return True;
} # Ende von Function modifyRow

?>