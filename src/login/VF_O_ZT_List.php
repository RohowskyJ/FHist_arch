<?php
/**
 * Zeitungs- Index Liste
 * 
 * @author J.Rohowsky
 * 
 * 
 */
session_start(); # die SESSION aktivieren

$module  = 'OEF';
$sub_mod = 'ZT';

if (! isset($tabelle_m)) {
    $tabelle_m = '';
}
$tabelle = "";

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
$_SESSION[$module]['Inc_Arr'][] = "VF_O_ZT_List.php"; 

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';
require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/VF_F_tab_creat.lib.php';

$flow_list = False;

$LinkDB_database  = '';
$db = LinkDB('VFH');

# ===========================================================================================================
# Haeder ausgeben
# ===========================================================================================================
$title = "Inhalt der Zeitung " ; #. $_SESSION[$module]['zt_name'];

$header = "";
$jq_tabsort = $jq = true;
BA_HTML_header('Zeitungs- Inhaltsverzeichnis ', '', 'Admin', '150em'); # Parm: Titel,Subtitel,HeaderLine,Type,width    . $_SESSION[$module]['zt_name']

echo "<fieldset>";

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

$ErrMsg = "";

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

VF_Count_add();

if (isset($_GET['ID'])) {
    if ($_GET['ID'] == "NextEig") {
        $_SESSION[$module]['zt_id'] = "";
    }
}

if (isset($_POST['select_string'])) {
    $select_string = $_POST['select_string'];
} else {
    $select_string = "";
}

$_SESSION[$module]['$select_string'] = $select_string;

if (isset($_GET['zt_id'])) {
    $zt_id = $_GET['zt_id'];
} else {
    $zt_id = "";
}
$_SESSION[$module]['zt_id'] = $zt_id;

if ($_SESSION[$module]['zt_id'] == "") {
    $eig_header = "Zeitungs- Auswahl";
    require ('VF_O_ZT_Select_List.inc.php');
} else {

    $sql = "SELECT * FROM zt_zeitungen WHERE zt_id = '$zt_id' ";
    
    echo "<div class='toggle-SqlDisp'>";
    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'OZT List  $sql </pre>";
    echo "</div>";
    
    $return = SQL_QUERY($db, $sql);
    if ($return) {
        $row = mysqli_fetch_object($return);
        $_SESSION[$module]['zt_name'] = $row->zt_name;
    }

    $tabelle_m = "zt_inhalt_" . $_SESSION[$module]['zt_id'];
    # ===========================================================================================
    # Definition der Auswahlmöglichkeiten (mittels radio Buttons)
    # ===========================================================================================

    $T_list_texte = array(
        "Alle" => "Alle Eintragungen. ",
        "A" => "nur Artikel",
        "W" => "nur Werbung",
        "V" => "nur Veranstaltungen"
    );

    $NeuRec = " &nbsp; &nbsp; &nbsp; <a href='VF_O_ZT_I_Edit.php?ih_id=0' > neue Beschreibung eingeben </a>";

    List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

    $List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Eintrag - Daten ändern: Auf die Zahl in Spalte <q>*_id</q> Klicken.</li>';

    $List_Hinweise .= '</ul></li>';

    List_Action_Bar($tabelle,"Zeitungs- Inhalte", $T_list_texte, $T_List, $List_Hinweise); # Action Bar ausgeben

    $tabelle_m = $_SESSION[$module]['tabelle_m'] = "zt_inhalt";
    $tabelle = $tabelle_m . "_" . $_SESSION[$module]['zt_id'];

    $Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle); # lesen der Tabellen Spalten Informationen
    
    $Tabellen_Spalten = array('ih_id','ih_zt_id', 'ih_jahr','ih_kateg', 'ih_sg','ih_titel','ih_autor','ih_seite','ih_spalte','ih_fwehr');

    $return = Cr_n_zt_inhalt($tabelle);
    if ($return != True) {
        echo "$tabelle error: mysqli_errno($return)";
    }

    $sql = "SELECT * FROM $tabelle ";
    $sql_where = "";
    $sql_orderBy = " ORDER BY ih_id ";

    switch ($T_List) {
        case "All":
            $sql_where = " ";
            break;
        case "A":
            $sql_where = " WHERE ih_kateg = 'A' ";
            break;
        case "W":
            $sql_where = " WHERE ih_kateg = 'W' ";
            break;
        case "V":
            $sql_where = " WHERE ih_kateg = 'V' ";
            break;
        default:
            $sql_where = " ";
    }

    $sql .= $sql_where . $sql_orderBy;
    
    echo "<div class='toggle-SqlDisp'>";
    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'O ZT List vor list_create $sql </pre>";
    echo "</div>";

    $zus_text = "Zeitschrift: ".$_SESSION[$module]['zt_name'].", ". $T_list_texte[$T_List] ;
    
    List_Create($db, $sql,'', $tabelle,''); # die liste ausgeben
    
    echo "</fieldset>";
    
  BA_HTML_trailer();
}

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

    $s_tab = substr($tabelle, 0, 8);

    switch ($s_tab) {
        case "zt_zeitu":
            $zt_id = $row['zt_id'];
            $row['zt_id'] = "<a href='VF_O_ZT_Z_Edit.php?zt_id=$zt_id' >" . $zt_id . "</a>";

            $zt_name = $row['zt_name'];
            $row['zt_name'] = "<a href='VF_O_ZT_List.php?zt_id=$zt_id' >" . $zt_name . "</a>";

            $zt_daten = $row['zt_daten'];
            if ($zt_daten == 1) {
                $row['zt_daten'] = "Daten vorhanden";
            } else {
                $row['zt_daten'] = "nichts vorhanden";
            }

            break;
        case "zt_inhal":
            $ih_id = $row['ih_id'];
            $row['ih_id'] = "<a href='VF_O_ZT_I_Edit.php?ih_id=$ih_id' >" . $ih_id . "</a>";

            if ($row['ih_kateg'] != "0" && $row['ih_kateg'] != "") {
                $row['ih_kateg'] = VF_ZT_Kategorie[$row['ih_kateg']];
            }

            if ($row['ih_sg'] != "0" && $row['ih_sg'] != "") {
                $row['ih_sg'] = VF_ZT_Sachgeb[$row['ih_sg']];
            }

            if ($row['ih_ssg'] != "0" && $row['ih_ssg'] != "") {
                $row['ih_sg'] .= "<br> ".VF_ZT_Sub_Sachg[$row['ih_ssg']];
            }

            $row['ih_jahr'] .= "-".$row['ih_nr'];
            
            $row['ih_titel'] .= "<br> ".$row['ih_titelerw'];
            
            $row['ih_email'] .= "<br>Tel: ".$row['ih_tel']."<br>Fax: ".$row['ih_fax'];
            
            break;
    }

    return True;
} # Ende von Function modifyRow

?>
