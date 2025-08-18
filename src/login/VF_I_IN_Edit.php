<?php
/**
 * Inventarverwaltung für Feuerwehren, Eingabe / Änderung der Daten
 * 
 * @author  Josef Rohowsky - neu 2019
 * 
 * 
 */
session_start();

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$Inc_Arr = array();
$Inc_Arr[] = "VF_IZ_IN_Edit.php";

# die SESSION am leben halten
const Module_Name = 'INV';
$module = Module_Name;
$tabelle = 'in_ventar';

const Prefix = '';

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
require $path2ROOT . 'login/common/VF_F_tab_creat.lib.php';

$flow_list = True;

$jq = $jqui = true;
$BA_AJA = true;

$LinkDB_database  = '';
$db = LinkDB('VFH');

$Eigent = "Eigentümer: " . $_SESSION['Eigner']['eig_eigner'];

if (! $_SESSION['Eigner']['eig_name'] == "") {
    $Eigent .= ", " . $_SESSION['Eigner']['eig_name'];
}
$Eigent .= ", " . $_SESSION['Eigner']['eig_verant'];

$jq = $jqui = True;
$BA_AJA = True;
$header = "";
BA_HTML_header('Inventar- Verwaltung ' . $Eigent, $header, 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width


initial_debug();

if (! empty($reflvl2)) {
    $referat = $reflvl2;
} elseif (! empty($reflvl1)) {
    $referat = $reflvl1;
}

// ==============================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================

if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

if (isset($_GET['in_id'])) {
    $in_id = $_GET['in_id'];
} else {
    $in_id = "";
}

if ($phase == 99) {
    header('Location: VF_I_IN_List.php');
}

if ($in_id !== "") {
    $_SESSION[$module]['in_id'] = $in_id;
} else {
    $in_id = $_SESSION[$module]['in_id'];
}

$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!
# $lowHeight = True; // Auswahl-- und Anzeige- Tabellen mit verschiedenen Höhen - je nach Record-Anzahl

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------


$eignr = $_SESSION['Eigner']['eig_eigner'];
$tabelle_a = $tabelle . "_$eignr";

Tabellen_Spalten_parms($db, $tabelle_a);
$Tabellen_Spalten[] = 'sa_name';
$Tabellen_Spalten_COMMENT['sa_name'] = 'Ausgewählte Sammlung';

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {

    if ($_SESSION[$module]['in_id'] == 0) {
        $eignr = $_SESSION['Eigner']['eig_eigner'];
        $neu = array(
            'in_id' => "0",
            'ei_id' => "$eignr",
            "in_invjahr" => "",
            'in_eingbuchnr' => "",
            'in_eingbuchdat' => "",
            'in_altbestand' => "",
            "in_invnr" => "",
            "in_sammlg" => "",
            'in_epoche' => "",
            'in_zustand' => "",
            "in_entstehungszeit" => "",
            "in_hersteller" => "",
            'in_bezeichnung' => "",
            "in_beschreibg" => "",
            "in_det_beschrbg" => "",
            "in_wert_neu" => "0",
            "in_neu_waehrg" => "",
            "in_wert_kauf" => "0",
            "in_kauf_waehrung" => "",
            "in_wert_besch" => "0",
            'in_besch_waehrung' => "",
            'in_abmess' => "",
            'in_gewicht' => '',
            'in_linkerkl' => "",
            'in_kommentar' => "",
            'in_namen' => "",
            'in_vwlinks' => "",
            'in_beschreibung' => "",
            'in_foto_1' => "",
            'in_fbeschr_1' => "",
            'in_foto_2' => "",
            'in_fbeschr_2' => "",
            'in_refindex' => "",
            'in_raum' => "",
            'in_platz' => "",
            'in_erstdat' => "",
            'in_ausgdat' => "",
            'in_neueigner' => "",
            'in_uidaend' => "",
            'in_aenddat' => "",
            'sa_name' => ""
        );

    } else {
        
        $sql_be = "SELECT * FROM $tabelle_a
             INNER JOIN fh_sammlung ON $tabelle_a.in_sammlg LIKE fh_sammlung.sa_sammlg
             WHERE `in_id` = '" . $_SESSION[$module]['in_id'] . "' ORDER BY `in_id` ASC"; // INNER JOIN fh_sammlung ON $tabelle_a.in_sammlg LIKE fh_sammlung.sa_sammlg 

        $return_be = SQL_QUERY($db, $sql_be); 

        $num_recs = mysqli_num_rows($return_be);
        if ($num_recs == 0) {
            $sql_be = "SELECT * FROM $tabelle_a
      
            WHERE `in_id` = '" . $_SESSION[$module]['in_id'] . "' ORDER BY `in_id` ASC"; // INNER JOIN fh_sammlung ON $tabelle_a.in_sammlg LIKE fh_sammlung.sa_sammlg
            $return_be = SQL_QUERY($db, $sql_be);
       }
       
        $neu = mysqli_fetch_array($return_be);
        mysqli_free_result($return_be);
        if (!isset($neu['sa_sammlg'])) {
            $neu['sa_sammlg'] = "";
            $neu['sa_name'] = "";
        }
    }
}

if ($phase == 1) {
    
}



switch ($phase) {
    case 0:
        require ('VF_I_IN_Edit_ph0.inc.php');
        break;
    case 1:
        require "VF_I_IN_Edit_ph1.inc.php";
        break;
}

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
function modifyRow(array &$row, # die Werte - das array wird by Name übergeben um die Inhalte ändern zu könnnen !!!!
$tabelle)
{
    global $path2ROOT, $T_List, $module;

    # print_r($_SESSION[$module]['all_upd']);
    if ($_SESSION[$module]['all_upd']) {
        $s_tab = substr($tabelle, 0, 8);
        # echo "<br/> L 135: \$see[$module][all_upd] \$s_tab $s_tab <br/>";
        switch ($s_tab) {
            case "in_vent_":
                $pict_path = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/INV/";
                $vl_id = $row['vl_id'];
                $row['vl_id'] = "<a href='VF_I_IN_VL_Edit.php?vl_id=$vl_id' >" . $vl_id . "</a>";
                $ei_zust_aus_bild = $row['ei_zust_aus_bild'];
                $p1 = $pict_path . $ei_zust_aus_bild;
                $row['ei_zust_aus_bild'] = "<a href='$p1' target='Bild 1' > <img src='$p1' alter='$p1' width='150px'> Groß  </a>";
                break;
            case "in_venta":
                
                if ($row['in_foto_1'] != "") {
                    $pictpath = "AOrd_verz/" . $_SESSION['Eigner']['eig_eigner'] . "/INV/";
                    
                    $in_foto_1 = $row['in_foto_1'];
                    $p1 = $pictpath . $row['in_foto_1'];
                    
                    $row['in_foto_1'] = "<a href='$p1' target='Bild 1' > <img src='$p1' alter='$in_foto_1' width='150px'> $in_foto_1 </a>";
                    
                }

        }
    }
    return True;
} # Ende von Function modifyRow

?>