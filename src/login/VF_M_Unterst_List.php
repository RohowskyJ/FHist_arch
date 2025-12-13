<?php

/**
 * Mitglieder Verwaltung Liste
 * 
 * @author Josef Rohowsky - neu 2020
 * 
 * 
 */
session_start();

$module = 'MVW';
$sub_mod = 'all';

$tabelle = 'fh_unterst';

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
$_SESSION[$module]['Inc_Arr'][] = "VF_Unterst_list.php";

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';

$flow_list = False;
$_SESSION[$module]['Return'] = False;

$LinkDB_database  = '';
$db = LinkDB('VFH'); 

VF_chk_valid();

VF_set_module_p();

$sk = $_SESSION['VF_Prim']['SK'];

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
 *   - select_string
 *   - DropdownAnzeige        Ein: Anzeige Dropdown Menu
 *   - LangListe              Ein: Liste zum Drucken
 *   - VarTableHight          Ein: Tabllenhöhe entsprechend der Satzanzahl
 *   - CSVDatei               Ein: CSV Datei ausgeben
 */
if (!isset($_SESSION['VF_LISTE'])) {
    $_SESSION['VF_LISTE']    = array(
        "select_string"       => "",
        "DropdownAnzeige"     => "Ein",
        "LangListe"           => "Ein",
        "VarTableHight"       => "Ein",
        "CSVDatei"            => "Ein"
    );
}

$mitgl_nrs = "";
$mitgl_einv_n = 0;

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
    "Alle" => "Alle jemals eingtragenen Unterstützer ( Auswahl ) ",
    "Aktive" => "Aktive Unterstützer    ( Auswahl ) ",
    "InAktive" => "In- Aktive Unterstützer    ( Auswahl ) ",
    "WeihnP" => "Für den Versand vorgesehene Einträge   ( Auswahl ) ",
    "AdrListE" => "Adress-Liste für die Aussendung, Änderungen    ",
    "AdrListV" => "Adress-Liste für die Aussendung, Versand    ",
); 

# ===========================================================================================================
# Haeder ausgeben
# ===========================================================================================================
$title = "Unterstützer Daten";

$jq_tabsort = $jq = true;
$logo = 'NEIN';
BA_HTML_header('Mitglieder- Verwaltung', '', 'Admin', '200em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

$NeuRec = " &nbsp; &nbsp; &nbsp; &nbsp; <a href='VF_M_Unt_Edit.php?fu_id=0' >Neuen Unterstützer eingeben</a>";

List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle);

switch ($T_List) {
    case "Alle":
    case "Aktive":
    case "InAktive" :   
    case "WeihnP" : 
        $Tabellen_Spalten = array(
        'fu_id',
        'fu_kateg',
        'fu_aktiv',
        'fu_orgname',
        'fu_anrede',
        'fu_tit_vor',
        'fu_vname',
        'fu_name',
        'fu_tit_nach',
        'fu_adresse',
        'fu_plz',
        'fu_ort',
        'fu_tel',
        'fu_email'
        );
        break;
        
    case "AdrListE":
    case "AdrListV":
        $Tabellen_Spalten = array(
        'fu_id',
        'fu_orgname',
        'fu_anrede',
        'fu_tit_vor',
        'fu_vname',
        'fu_name',
        'fu_tit_nach',
        'fu_adresse',
        'fu_plz',
        'fu_ort'
            );
        break;

    default:
        $Tabellen_Spalten = array(
        'fu_id',
        'fu_kateg',
        'fu_aktiv',
        'fu_orgname',
        'fu_anrede',
        'fu_tit_vor',
        'fu_vname',
        'fu_name',
        'fu_tit_nach',
        'fu_adresse',
        'fu_plz',
        'fu_ort',
        'fu_tel',
        'fu_email'
        );
}

$Tabellen_Spalten_style['fu_id'] = $Tabellen_Spalten_style['va_datum'] = $Tabellen_Spalten_style['va_begzt'] = 'text-align:center;';

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Mitglieder Daten ändern: Auf die Zahl in Spalte <q>fu_id</q> Klicken.</li>';
switch ($T_List) {
    case "Alle":
  
        break;

    default:
    /*
     * $List_Hinweise .= '<li>Anmelde Daten ändern: Auf die Zahl in Spalte <q>fu_id</q> Klicken.</li>'
     * . '<li>E-Mail an Mitglied senden: Auf die E-Mail-Adresse in Spalte <q>EMail</q> Klicken.</li>'
     * . '<li>Home Page des Mitglieds ansehen: Auf den Link in Spalte <q>Home_Page</q> Klicken.</li>'
     * . '<li>Forum Teilnehmer Daten ansehen: Auf die Zahl in Spalte <q>lco_email</q> Klicken.</li>';
     */
}
$List_Hinweise .= '</ul></li>';
/*
 * if ($debug) {echo "<pre class=debug>Tabellen_Spalten "; print_r($Tabellen_Spalten); echo "</pre>";
 * echo "<pre class=debug>Tabellen_Spalten_COMMENT "; print_r($Tabellen_Spalten_COMMENT); echo "</pre>";
 * echo "<pre class=debug>Tabellen_Spalten_style "; print_r($Tabellen_Spalten_style); echo "</pre>";
 * }
 */
List_Action_Bar("fh_unterst","Unterstützer- Verwaltung", $T_list_texte, $T_List, $List_Hinweise); # Action Bar ausgeben

# ===========================================================================================================
# Je nach ausgewähltem Radio Button das sql SELECT festlegen
# ===========================================================================================================
$act_datum = date("Y-m-d");
$sql_where = $order_By = "";
$sql = "SELECT * FROM fh_unterst ";
switch ($T_List) {
    case "Alle":
        $sql_where = " WHERE fu_name!='' ";
        $orderBy = ' ORDER BY fu_name ';
        break;
    case "Aktive":
        $sql_where = " WHERE fu_aktive ='J' ";
        $orderBy = ' ORDER BY fu_name ';
        break;
    case "InAktiv":
        $sql_where = " WHERE fu_active ='N' ";
        $orderBy = ' ORDER BY fu_name ';
        break;
    case "AdrListE": 
        $sql_where = " WHERE fu_name!='' ";
        $orderBy = ' ORDER BY fu_name ';
        break;
    case "AdrListV": 
        $sql_where = " WHERE fu_name!='' AND fu_weihn_post = 'J' ";
        $orderBy = ' ORDER BY fu_name ';
        break;
    default :
        $sql_where = " WHERE fu_name!='' ";
        $orderBy = ' ORDER BY fu_name ';
        break;
}

if ($select_string != '') {
    switch ($T_List) {

        default:
            $sql_where .= " AND fu_name LIKE '%$select_string%'";
    }
}
$sql .= $sql_where . $orderBy;
$Kateg_Name = "";
# ===========================================================================================================
# Die Daten lesen und Ausgeben
# ===========================================================================================================
if ($T_List != "AdrListE" && $T_List != "AdrListV" ) { 
    $Kateg_Name = $T_List;
    $csv_DSN = $path2ROOT . "login/Downloads/Unterst.csv";

} else {
    
    $folge = array('FF','FG','FK','SP','BP','FA');
    foreach ($folge as $grp) {
        $Kateg_Name = $grp." ".VF_Unterst[$grp] ;
        $csv_DSN = $path2ROOT . "login/Downloads/".VF_Unterst[$grp].".csv";
        $sql = "SELECT * FROM fh_unterst ";
        $sql_where = " WHERE fu_kateg = '$grp' AND fu_aktiv = 'J' ";
        $orderBy = ' ORDER BY fu_name ';
        $sql .= $sql_where . $orderBy;

    }
    
}

echo "<div class='toggle-SqlDisp'>";
echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>M Unt List $sql </pre>";
echo "</div>";

List_Create($db, $sql,'', $tabelle,''); # die liste ausgeben

#echo "</fieldset>";

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
    global $path2ROOT, $T_List, $mitgl_einv_n, $bez_cnt, $abo_cnt;

    $defjahr = date("y"); // Beitragsjahr, ist Gegenwärtiges Jahr

    $fu_id = $row['fu_id'];
    $row['fu_id'] = "<a href='VF_M_Unt_Edit.php?fu_id=$fu_id'>$fu_id</a>";

    $fu_kateg = $row['fu_kateg'];
    $row['fu_kateg'] = $fu_kateg ." - ".VF_Unterst[$fu_kateg];
   
    return True;
} # Ende von Function modifyRow

?>