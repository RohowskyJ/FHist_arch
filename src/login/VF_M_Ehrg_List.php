<?php

/**
 * Liste der vom Verein durchgeführten Ehrungen
 * 
 * @author Josef Rohowsky - neu 2024
 * 
 * 
 */
session_start();

const Module_Name = 'MVW';
$module = Module_Name;
$tabelle = 'fh_m_ehrung';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";
$debug = True;
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
 *   - SelectAnzeige          Ein: Anzeige der SQL- Anforderung
 *   - SpaltenNamenAnzeige    Ein: Anzeige der Apsltennamen
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

$mitgl_nrs = "";
$mitgl_einv_n = 0;

if (isset($_POST['select_string'])) {
    $select_string = $_POST['select_string'];
} else {
    $select_string = "";
}

$_SESSION['VF']['$select_string'] = $select_string;
$_SESSION[$module]['Return'] = True;

initial_debug();
$bez_cnt = $abo_cnt = 0;
# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================
$T_list_texte = array(
    "Alle" => "Alle",
    "NeuItem" => "<a href='VF_M_EH_Edit.php?ID=0' >Neuen Datensatz eingeben</a>"
); 
#     "NeuItem" => "<a href='VF_M_Edit.php?ID=0' >Neues Mitglied eingeben</a>"

# ===========================================================================================================
# Haeder ausgeben
# ===========================================================================================================
$title = "Ehrungen";
# $heading = "<h1>Mitglieder Daten aus Tabelle <q>".$tabelle."</q></h1>";

$logo = 'NEIN';
BA_HTML_header('Ehrungen', '', 'Admin', '200em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

#echo "<fieldset>";

List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle);

switch ($T_List) {
    case "Alle":
 
        $Tabellen_Spalten = array(
            'fe_lfnr',
            'fe_m_id',
            'fe_ehrung',
            'fe_eh_datum',
            'fe_begruendg',
            'fe_bild1',
            'fe_bild2',
            'fe_bild3',
            'fe_bild4'
        );
        break;


    default:
        $Tabellen_Spalten = array(
        'fe_lfnr',
        'fe_m_id',
        'fe_ehrung',
        'fe_eh_datum',
        'fe_begruendg',
        'fe_bild1',
        'fe_bild2',
        'fe_bild3',
        'fe_bild4'
            );
}

$Tabellen_Spalten_style['fe_lfnr'] = $Tabellen_Spalten_style['fe_m_id'] = 'text-align:center;';

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Mitglieder Daten ändern: Auf die Zahl in Spalte <q>mi_id</q> Klicken.</li>';
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
/*
 * if ($debug) {echo "<pre class=debug>Tabellen_Spalten "; print_r($Tabellen_Spalten); echo "</pre>";
 * echo "<pre class=debug>Tabellen_Spalten_COMMENT "; print_r($Tabellen_Spalten_COMMENT); echo "</pre>";
 * echo "<pre class=debug>Tabellen_Spalten_style "; print_r($Tabellen_Spalten_style); echo "</pre>";
 * }
 */
List_Action_Bar("fh_m_ehrung","Ehrungen ", $T_list_texte, $T_List, $List_Hinweise); # Action Bar ausgeben

# ===========================================================================================================
# Je nach ausgewähltem Radio Button das sql SELECT festlegen
# ===========================================================================================================
$act_datum = date("Y-m-d");
$sql_where = $order_By = "";
$sql = "SELECT * FROM fh_m_ehrung ";
switch ($T_List) {
    case "Alle":
        $sql_where = "  ";
        $order_By = ' ORDER BY fe_eh_datum ';
        break;
}

if ($select_string != '') {
    switch ($T_List) {

        default:
      #      $sql_where .= " AND mi_name LIKE '%$select_string%'";
    }
}
$sql .= $sql_where . $order_By;

# ===========================================================================================================
# Die Daten lesen und Ausgeben
# ===========================================================================================================

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

    if ($tabelle == "fh_m_ehrung") {
        # $pict_path = "referat4/AUSZ/".$_SESSION[$proj]['fw_bd_abk']."/";
        $fe_lfnr = $row['fe_lfnr'];
        $row['fe_lfnr'] = "<a href='VF_M_EH_Edit.php?ID=$fe_lfnr' >" . $fe_lfnr . "</a>";
        $pict_path = "AOrd_Verz/1/MITGL/";
        if ($row['fe_bild1'] != "") {     
            $fe_bild1 = $row['fe_bild1'];
            $p1 = $pict_path . $row['fe_bild1']; 
            $row['fe_bild1'] = "<a href='$p1' target='Bild 1' > <img src='$p1' alter='$p1' width='150px'>  $fe_bild1  </a>";
            if (stripos($row['fe_bild1'],".pdf")) {
                $row['fe_bild1'] = "<a href='$p1' target='Bild 1' > Dokument</a>";
            } else {
                $row['fe_bild1'] = "<a href='$p1' target='Bild 1' > <img src='$p1' alter='$p1' width='150'>  $fe_bild1  </a>";
            }
            
        }
        if ($row['fe_bild2'] != "") {
            $fe_bild2 = $row['fe_bild2'];
            $p2 = $pict_path . $row['fe_bild2'];
            
            $row['fe_bild2'] = "<a href='$p2' target='Bild 1' > <img src='$p2' alter='$p2' width=150px'>  $fe_bild2  </a>";
            if (stripos($row['fe_bild2'],".pdf")) {
                $row['fe_bild2'] = "<a href='$p2' target='Bild 2' > Dokument</a>";
            } else {
                $row['fe_bild2'] = "<a href='$p2' target='Bild 2' > <img src='$p2' alter='$p2' width='150'>  $fe_bild2  </a>";
            }
        }
        if ($row['fe_bild3'] != "") {
            $fe_bild3 = $row['fe_bild3'];
            $p3 = $pict_path . $row['fe_bild3'];
            if (stripos($row['fe_bild3'],".pdf")) {
                $row['fe_bild3'] = "<a href='$p3' target='Bild 3' > Dokument</a>";
            } else {
                $row['fe_bild3'] = "<a href='$p3' target='Bild 3' > <img src='$p3' alter='$p3' width='150'>  $fe_bild3  </a>";
            }
        }
        if ($row['fe_bild4'] != "") {
            $fe_bild4 = $row['fe_bild4'];
            $p4 = $pict_path . $row['fe_bild4'];
            if (stripos($row['fe_bild4'],".pdf")) {
                $row['fe_bild4'] = "<a href='$p4' target='Bild 4' > Dokument</a>";
            } else {
                $row['fe_bild4'] = "<a href='$p4' target='Bild 4' > <img src='$p4' alter='$p4' width='150'>  $fe_bild4  </a>";
            }
        }
    }
    
    return True;
} # Ende von Function modifyRow

?>