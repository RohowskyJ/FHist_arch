<?php

/**
 * Wartung Wappen und Auszeichnungen
 *
 * @author Josef Rohowsky - neu 2019
 */
session_start();

const Module_Name = 'PSA';
$module = Module_Name;
$tabelle = 'fh_fw_ort_ref';

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
# require $path2ROOT . 'login/common/VF_4_Comm.inc';

require $path2ROOT . 'login/common/const.inc';
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
// ===============================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================

if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

if (isset($_GET['ID'])) {
    $fw_id = $_GET['ID'];
} else {
    $fw_id = "";
}
if (isset($_GET['fw_id'])) {
    $fw_id = $_GET['fw_id'];
}

if ($phase == 99) {
    header('Location: VF_PS_OV_M_List.php');
}

if ($fw_id !== "") {
    $_SESSION[$module]['fw_id'] = $fw_id;
} else {
    $fw_id = $_SESSION[$module]['fw_id'];
}
$proj = $_SESSION[$module]['proj'];
if (! isset($_SESSION[$proj]['fw_id'])) {
    $SESSION[$proj]['fw_id'] = $fw_id;
}

$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!
$lowHeight = False; // Auswahl-- und Anzeige- Tabellen mit verschiedenen Höhen - je nach Record-Anzahl

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------

Tabellen_Spalten_parms($db, $tabelle);

if ($_SESSION[$module]['all_upd']) {
    $Edit_Funcs_Protect = False;
    # $edit_allow = 1;
    # $read_only = "";
} else {
    # $edit_allow = 0;
    # $read_only = "readonly";
    $Edit_Funcs_Protect= True;
}

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {
    if ($_SESSION[$module]['fw_id'] == 0) {
        $neu = array(
            'fw_id' => "NeuItem",
            'fw_st_abk' => "AT",
            "fw_bd_abk" => "NOE",
            'fw_bz_abk' => "",
            'fw_bz_name' => "",
            'fw_ab_nr' => "",
            'fw_ab_name' => "",
            'fw_gd_nr' => "",
            "fw_gd_name" => "",
            'fw_gd_art' => "",
            'fw_fw_nr' => "",
            'fw_fw_name' => "",
            'fw_fw_typ' => "",
            'fw_grdg_dat' => "",
            'fw_end_dat' => "",
            'fw_kommentar' => "",
            'fw_auszeich' => '',
            'fw_aermelw' => '',
            'fw_ort_komm' => "",
            'fw_uid_aend' => $_SESSION[$module]['p_uid'],
            "fw_aenddat" => ""
        );
    } else {
        # $fw_id = ;
        $sql_be = "SELECT * FROM $tabelle WHERE `fw_id` = '" . $_SESSION[$module]['fw_id'] . "' ORDER BY `fw_id` ASC";
        $return_be = SQL_QUERY($db, $sql_be) or die("Datenbankabfrage gescheitert. " . mysqli_error($db));

        $neu = mysqli_fetch_array($return_be);
        mysqli_free_result($return_be);
    }

    $_SESSION[$proj]['fw_id'] = $neu['fw_id'];
    $_SESSION[$proj]['fw_st_abk'] = $neu['fw_st_abk'];
    if ($neu['fw_bd_abk'] != "") {
        $_SESSION[$proj]['fw_bd_abk'] = $neu['fw_bd_abk'];
    } else {
        $_SESSION[$proj]['fw_bd_abk'] = $neu['fw_st_abk'];
    }
}

if ($phase == 1) {
    require "VF_PS_OV_M_Edit_ph1.inc";
}

switch ($_SESSION[$module]['proj']) {
    case "AERM":
        $htext = 'Orts- Struktur Verwaltung, Wappen, Ärmelabzeichen';
        break;

    case "AUSZ":
        $htext = 'Orts- Struktur Verwaltung, Auszeichnungen, Ehrenzeichen, ... ';
        break;
    default:
}
HTML_header($htext, 'Änderungsdienst', '', 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

switch ($phase) {
    case 0:
        require 'VF_PS_OV_M_Edit_ph0.inc';
        break;
}
HTML_trailer();?>