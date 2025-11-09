<?php
/**
 * Archivordnung, Erweiterungen für Eigentümer (Lokale Erweiterungs- Liste)
 * 
 * @author Josef Rohowsky - neu 2018, reorg Tabelle 2024
 * 
 * Hinzufügen zweier zusätzlicher Ebenen zur Archivordnung vom ÖBFV
 */
session_start();
# die SESSION aktivieren

$module = 'ARC';
$sub_mod ='all';

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
# $_SESSION[$module]['Inc_Arr']  = array();
$_SESSION[$module]['Inc_Arr'][] = "VF_A_AOR_List.php";  

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
# $_SESSION[$module]['Inc_Arr']  = array();
$_SESSION[$module]['Inc_Arr'][] = "VF_A_AOR_List.php";

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';
require $path2ROOT . 'login/common/VF_F_tab_creat.lib.php';

$flow_list = False;

$LinkDB_database  = '';
$db = LinkDB('VFH');

# ===========================================================================================================
# Haeder ausgeben
# ===========================================================================================================
$title = "Erweiterte Archivordnung des Eigentümers " . $_SESSION['Eigner']['eig_eigner'];

$jq = True;
$header = ""; // "<script type='text/javascript' src='common/javascript/prototype.js' ></script>";

BA_HTML_header('Erweiterte Archivordnung ', $header, 'Admin', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

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
        "DropdownAnzeige"     => "Ein",
        "LangListe"           => "Ein",
        "VarTableHight"       => "Ein",
        "CSVDatei"            => "Aus"
    );
}

$ErrMsg = "";

if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

if ($phase == 99) {
    header("Location: $path2ROOT/login/VF_C_Menu.php");
}

if (isset($_POST['select_string'])) {
    $_SESSION[$module]['$select_string'] = $select_string = $_POST['select_string'];
} else {
    $_SESSION[$module]['$select_string'] = $select_string = "";
}

    if (!isset($_SESSION[$module]['ar_id'])) {
        $_SESSION[$module]['ar_id'] = '01';
    }
    
    if (isset($_GET['ar_id'])) {
        $_SESSION[$module]['ar_id'] = $ar_id = $_GET['ar_id'];
    }
    
    /**
     * ***********************
     * Einlesen des Gruppen-Reords (Ebene 1 und 2)
     */
    $ar_id = $_SESSION[$module]['ar_id'];
    $sql_grp = "SELECT *  FROM ar_chivord WHERE ar_id = '$ar_id' ";
    $return_grp = SQL_QUERY($db, $sql_grp);
    $row_grp = mysqli_fetch_assoc($return_grp);
    
    $_SESSION[$module]['ar_grp'] = $row_grp['ar_sg'] . "." . $row_grp['ar_sub_sg'];
    $_SESSION[$module]['ar_name'] = $row_grp['ar_sgname'];
    
    # ===========================================================================================
    # Definition der Auswahlmöglichkeiten (mittels radio Buttons)
    # ===========================================================================================
    
    $T_list_texte = array(
        "Alle" => "Archivordnung, Erweiterungen für den Eigentümer "
    );
    

    $NeuRec = " &nbsp; &nbsp; &nbsp; <a href='VF_A_AOR_Edit.php?al_id=0' > Neuen Datensatz anlegen </a>";
    
    List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen
    
    $List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Erweiterungs- Daten ändern: Auf die Zahl in Spalte <q>al_id</q> Klicken.</li>';
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
    
    # ===========================================================================================================
    # Die Sammlungs- Auswahl anzeigen:
    # ===========================================================================================================
   
    $tabelle = "ar_ord_local";
    
    $zus_ausw = "";
    List_Action_Bar($tabelle,"Archivordnung " , $T_list_texte, $T_List, $List_Hinweise, $zus_ausw); # Action Bar ausgeben
    
    $Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle_m); # lesen der Tabellen Spalten Informationen
    
    $sql = "SELECT * FROM $tabelle ";
    # $sql_where = " WHERE al_sg='" . $_SESSION[$module]['ar_grp'] . "' ";
    $sql_where = " WHERE al_sg='" . $_SESSION[$module]['ar_grp'] . "' ";
    # $sql_orderBy = "ORDER BY al_id ASC";
    $sql_orderBy = "ORDER BY al_sg,al_lcsg,al_lcssg,al_lcssg_s0,al_lcssg_s1 ASC";
    $Tabellen_Spalten = array(
        'al_id',
        'al_sg',
        'al_lcsg',
        'al_lcssg',
        'al_lcssg_s0',
        'al_lcssg_s1',
        'al_bezeich',
        'al_sammlung'
    );
    $Tabellen_Spalten_style['al_id'] = $Tabellen_Spalten_style['al_sg'] = $Tabellen_Spalten_style['al_lcsg'] = $Tabellen_Spalten_style['al_lcssg'] = $Tabellen_Spalten_style['ar_usg'] = 'text-align:center;';
    
    $sql .= $sql_where . $sql_orderBy;
    
    echo "<div class='toggle-SqlDisp'>";
    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>A_AOR List vor list_create $sql </pre>";
    echo "</div>";
    
    List_Create($db, $sql,'', $tabelle,''); # die liste ausgeben


echo "<p><a href='VF_A_ORD_List.php'>Zurück zur Liste</a></p>";

BA_HTML_trailer();

# echo "<script type='text/javascript' src='VF_C_ako_proc.js'></script>";

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
    global $path2ROOT, $T_List, $module;

    $s_tab = substr($tabelle, 0, 8);

    switch ($s_tab) {
        case "fh_eigen":
            $ei_id = $row['ei_id'];
            $row['ei_id'] = "<a href='VF_A_AOR_List.php?ei_id=$ei_id' >" . $ei_id . "</a>";
            break;
        case "ar_ord_l":
            $al_id = $row['al_id'];
            if ($row['al_lcssg'] != "") {
                $row['al_id'] = "<a href='VF_A_AOR_Edit.php?al_id=$al_id' >" . $al_id . "</a>";
            }
            break;
    }

    return True;
} # Ende von Function modifyRow

?>
