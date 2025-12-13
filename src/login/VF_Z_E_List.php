<?php

/**
 * Liste der Eigentümer
 * 
 * @author Josef Rohowsky - neu 2018
 * 
 * 
 */
session_start();

$module  = 'ADM';
$sub_mod = 'Einr';

$tabelle = 'fh_eigentuemer';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */

$path2ROOT = "../";
/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$_SESSION[$module]['Inc_Arr']  = array();
$_SESSION[$module]['Inc_Arr'][] = "VF_Z_E_List.php"; 

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';
require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';

$flow_list = False;

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

$LinkDB_database = '';
$db = LinkDB('VFH');

VF_chk_valid();
VF_set_module_p();

VF_Count_add();

$mitgl_nrs = "";
$mitgl_einv_n = 0;

# ===========================================================================================================
# Haeder ausgeben
# ===========================================================================================================
$title = "Eigentümer Daten";

$jq_tabsort = $jq = true;
BA_HTML_header('Eigentümer- Verwaltung', '', 'Admin', '150em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

echo "<fieldset>";
if (isset($_POST['select_string'])) {
    $select_string = $_POST['select_string'];
} else {
    $select_string = "";
}
$_SESSION['VF']['$select_string'] = $select_string;

initial_debug();

# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================
$T_list_texte = array(
    "Alle" => "Alle jemals gemeldeten Eigentümer ( Auswahl ) ",
    "AdrList" => "Adress-Liste der Eigentümer   "
);

$NeuRec = " &nbsp; &nbsp; &nbsp; &nbsp; <a href='VF_Z_E_Edit.php?ID=0' >Neuen Eigentümer eingeben</a>";

List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle);

switch ($T_List) {
    case "Alle":

    /*
     * $Tabellen_Spalten = array('ei_id','ei_mitglnr','ei_staat','ei_bdld','ei_bezirk','ei_orgtyp','ei_org_name','kont_name','ei_fwkz','ei_grgjahrmi_ort'
     * ,'mi_gebtag','mi_tel_handy','mi_handy','mi_fax','mi_email','mi_email_status','mi_ref_leit','mi_ref_ma','mi_ref_int','mi_sterbdat'
     * ,'mi_austrdat','mi_einv_art','mi_einversterkl','mi_einv_dat','mi_uidaend','mi_aenddat'
     * );
     * break;
     */
    case "AdrList":
        $Tabellen_Spalten = array(
            'ei_id',
            'ei_org_typ',
            'ei_org_name',
            'kont_name',
            'ei_anrede',
            'ei_titel',
            'ei_name',
            'ei_vname',
            'ei_adresse',
            'ei_plz',
            'ei_ort'
        );
        break;

    default:
        $Tabellen_Spalten = array(
            'ei_id',
            'ei_org_typ',
            'ei_org_name',
            'kont_name',
            'ei_anrede',
            'ei_titel',
            'ei_name',
            'ei_vname',
            'ei_adresse',
            'ei_plz',
            'ei_ort'
        );
        break;
}

$Tabellen_Spalten_style['ei_id'] = 
$Tabellen_Spalten_style['ei_mitglnr'] = $Tabellen_Spalten_style['va_begzt'] = 'text-align:center;';

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Mitglieder Daten ändern: Auf die Zahl in Spalte <q>ei_id</q> Klicken.</li>';

$List_Hinweise .= '</ul></li>';

List_Action_Bar('fh_eigentuemer',"Eigentümer- Verwaltung - Administrator ", $T_list_texte, $T_List, $List_Hinweise); # Action Bar ausgeben

# ===========================================================================================================
# Je nach ausgewähltem Radio Button das sql SELECT festlegen
# ===========================================================================================================
$act_datum = date("Y-m-d");
$sql_where = $order_By = "";
$sql = "SELECT * FROM fh_eigentuemer ";
switch ($T_List) {
    case "Alle":
        $sql_where = " WHERE ei_name!='' ";
        $orderBy = ' ORDER BY ei_id ';
        break;
    case "AdrList":
        $sql_where = " WHERE ((ei_sterbdat<='0000-00-00' AND ei_abgdat<='0000-00-00') OR (ei_sterbdat IS NOT NULL AND ei_abgdat IS NULL) ) ";
        $orderBy = ' ORDER BY ei_name';
        break;

    # default: VF_HTML_trailer(); exit; # wenn noch nix gewählt wurde >> beenden
}

if ($select_string != '') {
    switch ($T_List) {

        default:
            $sql_where .= " AND (ei_org_name LIKE '%$select_string%' OR ei_name LIKE '%$select_string%' )";
    }
}
$sql .= $sql_where . $orderBy;

echo "<div class='toggle-SqlDisp'>";
echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>Z E List vor list_create $sql </pre>";
echo "</div>";

# ===========================================================================================================
# Die Daten lesen und Ausgeben
# ===========================================================================================================

List_Create($db, $sql,'', $tabelle,''); # die liste ausgeben

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
 * @global string $path2ROOT String zur root-Angleichung für relative Adressierung
 * @global string $T_List Auswahl der Listen- Art
 * @global string $module Modul-Name für $_SESSION[$module] - Parameter
 */
function modifyRow(array &$row, # die Werte - das array wird by Name übergeben um die Inhalte ändern zu könnnen !!!!
$tabelle)
{
    global $path2ROOT, $T_List;

    $defjahr = date("y"); // Beitragsjahr, ist Gegenwärtiges Jahr

    $ei_id = $row['ei_id'];
    $row['ei_id'] = "<a href='VF_Z_E_Edit.php?ID=$ei_id'>$ei_id</a>";

    $ei_org_typ = $row['ei_org_typ'];
    $row['ei_org_typ'] = VF_Eig_Org_Typ[$ei_org_typ];
    
    return True;
} # Ende von Function modifyRow

?>