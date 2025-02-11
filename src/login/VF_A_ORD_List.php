<?php
/**
 * Auswahl der lokalen Archivordnung
 * 
 *  * @author Josef Rohowsky -  - neu 2018, reorg Tabelle 2024
 *  
 */
session_start();

# die SESSION aktivieren
const Module_Name = 'ARC';
$module = Module_Name;
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

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';
require $path2ROOT . 'login/common/VF_F_tab_creat.lib.php';

$flow_list = True;
$LinkDB_database  = '';
$db = LinkDB('VFH');

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

$ErrMsg = "";

$Err_Msg_lv1_2_sel = "";
if (! isset($_SESSION[$module]['aeb_1'])) {
    $_SESSION[$module]['aeb_1'] = $_SESSION[$module]['aeb_2'] = $_SESSION[$module]['aeb_3'] = $_SESSION[$module]['aeb_4'] = "0";
}

if (!isset($_SESSION['Eigner']['eig_eigner'])) {
    $_SESSION['Eigner']['eig_eigner'] = "";
}
if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

if ($phase == 99) {
    header("Location: /login/VF_C_Menu.php");
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

    $T_list_texte = array(
        "Alle" => "Archivordnung "
    );

    # ===========================================================================================================
    # Haeder ausgeben
    # ===========================================================================================================
    #$title = "Erweiterte Archivordnung des Eigentümers " . $_SESSION['Eigner']['eig_eigner'];
    
    $header = "";

    $prot = True;
    BA_HTML_header('Erweiterte Archivordnung', $header, 'Admin', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width
    
    echo "<main>";
    echo "<fieldset>";

    List_Prolog($module, $T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

    $List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Fahrzeug - Daten ändern: Auf die Zahl in Spalte <q>ar_id</q> Klicken.</li>';
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

 
    $zus_ausw = "";

    $tabelle = "ar_chivord";

    List_Action_Bar($tabelle, "Archivordnung ", $T_list_texte, $T_List, $List_Hinweise, $zus_ausw); # Action Bar ausgeben

    $Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle); # lesen der Tabellen Spalten Informationen

    $sql = "SELECT * FROM $tabelle ";
    $sql_where = "";
    $sql_orderBy = "";

    $Tabellen_Spalten = array(
        'ar_id',
        'ar_sg',
        'ar_sub_sg',
        'ar_sgname'
    );
    $Tabellen_Spalten_style['ar_id'] = $Tabellen_Spalten_style['ar_sg'] = $Tabellen_Spalten_style['ar_sub_sg'] = $Tabellen_Spalten_style['ar_usg'] = 'text-align:center;';
    $sql_where = "";

    $sql .= $sql_where . $sql_orderBy;

    List_Create($db, $sql,'', $tabelle,''); # die liste ausgeben
    

echo "</fieldset>";
echo "</main>";

BA_HTML_trailer();
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
    global $path2ROOT, $T_List, $module;

    $s_tab = substr($tabelle, 0, 8);
    # print_r($_SESSION[$module]['all_upd']);

    switch ($s_tab) {
        case "fh_eigen":
            $ei_id = $row['ei_id'];
            $row['ei_id'] = "<a href='VF_A_ORD_List.php?ei_id=$ei_id' >" . $ei_id . "</a>";
            break;
        case "ar_chivo":
            $ar_id = $row['ar_id'];
            if ($row['ar_sub_sg'] != "") {
                $row['ar_id'] = "<a href='VF_A_AOR_List.php?ar_id=$ar_id' >" . $ar_id . "</a>";
            }
            break;
    }

    return True;
} # Ende von Function modifyRow

?>
