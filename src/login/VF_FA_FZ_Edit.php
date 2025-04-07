<?php

/**
 * Fahrzeug- Liste, Wartung
 *
 * @author Josef Rohowsky - neu 2018
 *
 *
 */
session_start();

# die SESSION am leben halten
const Module_Name = 'F_G';
$module = Module_Name;
$tabelle = 'ma_fz_beschr';

const Prefix = '';

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
require $path2ROOT . 'login/common/VF_M_tab_creat.lib.php';

$flow_list = True;

$LinkDB_database  = '';
$db = LinkDB('VFH');

$prot = True;
$header = "";

BA_HTML_header('Fahrzeug- und Geräte- Verwaltung', $header, 'Form', '150em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

initial_debug();

// ============================================================================================================
// Eingabenerfassung und defauls
// ============================================================================================================

// ============================================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================

if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

if (isset($_GET['ID'])) {
    $fz_id = $_GET['ID'];
} else {
    $fz_id = "";
}
if (isset($_GET['fz_id'])) {
    $fz_id = $_GET['fz_id'];
}

if ($phase == 99) {
    header('Location: VF_FA_List.php');
}

if ($fz_id != "") {
    $_SESSION[$module]['fz_id'] = $fz_id;
} else {
    $fz_id = $_SESSION[$module]['fz_id'];
}

$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!
$Tabellen_Spalten[] = 'sa_name';
$Tabellen_Spalten_COMMENT['sa_name'] = 'Ausgewählte Sammlung';

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------


$eignr = $_SESSION['Eigner']['eig_eigner'];
$tabelle_a = $tabelle . "_$eignr";

Tabellen_Spalten_parms($db, $tabelle_a);

if ($_SESSION[$module]['all_upd']) {

    $edit_allow = 1;
    $read_only = "";
} else {
    $edit_allow = 0;
    $read_only = "readonly";
}

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {
    $Tabellen_Spalten_COMMENT['ct_juroren'] = 'Juroren';
    $Tabellen_Spalten_COMMENT['ct_darstjahr'] = 'Darstellungs- Jahr';
    if ($_SESSION[$module]['fz_id'] == 0) {

        $neu = array(
            'fz_id' => 0,
            'fz_eignr' => "",
            "fz_invnr" => "0",
            "sa_name" => "Kraftfahrzeug",
            'fz_name' => "",
            'fz_taktbez' => "",
            'fz_indienstst' => "",
            'fz_ausdienst' => "",
            'fz_zeitraum' => "",
            "fz_komment" => "",
            'fz_sammlg' => $_SESSION[$module]['sammlung'],
            'fz_bild_1' => "",
            'fz_b_1_komm' => "",
            'fz_bild_2' => "",
            'fz_b_2_komm' => "",
            'fz_zustand' => "",
            'fz_ctifklass' => "",
            'fz_ctifdate' => "",
            "fz_beschreibg_det" => "",
            "fz_eigent_freig" => "",
            "fz_verfueg_freig" => "",
            "fz_pruefg_id" => "",
            "fz_pruefg" => "",
            "fz_aenduid" => "",
            "fz_aenddat" => "",
            "ct_darstjahr" => "",
            "ct_juroren" => "",
            "fz_herstell_fg" => "",
            "fz_baujahr" => ""
        );
    } else {

        #$sql_be = "SELECT * FROM $tabelle_a WHERE `fz_id` = '" . $_SESSION[$module]['fz_id'] . "' ORDER BY `fz_id` ASC";
        
        $sql_be = "SELECT *
        FROM $tabelle_a
        LEFT JOIN fh_sammlung ON $tabelle_a.fz_sammlg = fh_sammlung.sa_sammlg 
        WHERE `fz_id` = '" . $_SESSION[$module]['fz_id'] . "' OR fh_sammlung.sa_sammlg IS NULL ORDER BY `fz_id` ASC";
        
        $return_be = SQL_QUERY($db, $sql_be);

        $neu = mysqli_fetch_array($return_be);
        mysqli_free_result($return_be);
        
        $_SESSION[$module]['fz_id_a'] = $neu['fz_id'];
        if ($neu['fz_sammlg'] != "") {
            $_SESSION[$module]['fz_sammlg'] = $neu['fz_sammlg'];
        }

        $sql_in = "SELECT * FROM fz_ctif_klass WHERE `fz_id`='" . $_SESSION[$module]['fz_id'] . "' AND `fz_eignr`='" . $_SESSION['Eigner']['eig_eigner'] . "'";
        $return_in = SQL_QUERY($db, $sql_in);
        $num_rows = mysqli_num_rows($return_in);
        if ($num_rows >= 1) {
            while ($row = mysqli_fetch_object($return_in)) {
                $neu['ct_juroren'] = $row->fz_juroren;
                $neu['ct_darstjahr'] = $row->fz_darstjahr;
            }
        } else {
            $neu['ct_darstjahr'] = '';
            $neu['ct_juroren'] = " ";
        }
    }
}

if ($phase == 1) {
    
}

switch ($phase) {
    case 0:
        require ('VF_FA_FZ_Edit_ph0.inc.php');
        break;
    case 1:
        require "VF_FA_FZ_Edit_ph1.inc.php";
        break;
}

BA_HTML_trailer();?>