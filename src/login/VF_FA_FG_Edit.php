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
const Module_Name = 'F_M';
$module = Module_Name;
$tabelle = 'fz_beschr';

const Prefix = '';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";
$debug = True;
$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Comm_Funcs.inc';
require $path2ROOT . 'login/common/const.inc';
require $path2ROOT . 'login/common/M_tab_creat.inc';
require $path2ROOT . 'login/common/Ajax_Funcs.inc';

require $path2ROOT . 'login/common/Funcs.inc';
require $path2ROOT . 'login/common/Edit_Funcs.inc';
require $path2ROOT . 'login/common/List_Funcs.inc';
require $path2ROOT . 'login/common/Tabellen_Spalten.inc';

$flow_list = False;

$LinkDB_database  = '';
$db = LinkDB('VFH');

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
    header('Location: VF_FA_FG_List.php');
}

$java_script = $java_script_ref = $java_script_such = "";

if ($fz_id !== "") {
    $_SESSION[$module]['fz_id'] = $fz_id;
} else {
    $fz_id = $_SESSION[$module]['fz_id'];
}

$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------

Tabellen_Spalten_parms($db, $tabelle);
$eignr = $_SESSION['Eigner']['eig_eigner'];
$tabelle_a = $tabelle . "_$eignr";

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
            "fz_invnr" => "",
            'fz_name' => "",
            'fz_taktbez' => "",
            'fz_indienstst' => "",
            'fz_ausdienst' => "",
            'fz_zeitraum' => "",
            "fz_komment" => "",
            'fz_sammlg' => "",
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
            "fz_suchbegr1" => "0200",
            "fz_suchbegr2" => "",
            "fz_suchbegr3" => "",
            "fz_suchbegr4" => "",
            "fz_suchbegr5" => "",
            "fz_suchbegr6" => "",
            "fz_aenduid" => "",
            "fz_aenddat" => "",
            "ct_darstjahr" => "",
            "ct_juroren" => "",
            "fz_herstell_fg" => "",
            "fz_baujahr" => ""
        );
    } else {

        $sql_be = "SELECT * FROM $tabelle_a WHERE `fz_id` = '" . $_SESSION[$module]['fz_id'] . "' ORDER BY `fz_id` ASC";
        $return_be = SQL_QUERY($db, $sql_be);

        $errno = mysqli_errno($db);
        $neu = mysqli_fetch_array($return_be);
        mysqli_free_result($return_be);

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
    require "VF_FA_FG_Edit_ph1.inc";
}
$jq = True;
$header = ""; # "<script type='module' src='common/javascript/jq_3-6/src/jquery.js' ></script>";

HTML_header('Fahrzeug- und Geräte- Verwaltung', 'Änderungsdienst', $header, 'Form', '150em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

switch ($phase) {
    case 0:
        require ('VF_FA_FG_Edit_ph0.inc');
        break;
}
echo "<script type='text/javascript' src='VF_C_Suchbegr_Funcs.js'></script>";
# echo "<script type='text/javascript' src='common/javascript/netspeed.js'></script>";
echo "<script type='text/javascript' src='VF_C_AOrd_Funcs.js'></script>";
HTML_trailer();?>