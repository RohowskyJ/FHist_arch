<?php

/**
 * Foto Urheber, Wartung
 *
 * @author Josef Rohowsky - neu 2019
 *
 */
session_start();

const Module_Name = 'OEF';
$module = Module_Name;

$tabelle = 'fh_urheber_n';

const Prefix = '';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = False;

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';

$flow_list = False;

$LinkDB_database = '';
$db = LinkDB('VFH');

initial_debug();

// ============================================================================================================
// Eingabenerfassung und defauls
// ============================================================================================================
// =============================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================
if (!isset($_SESSION[$module]['Fo']['Urheber'])  ) {
    $_SESSION[$module]['Fo']['Urheber']['fm_id'] = "";
}
if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}
if (isset($_GET['ID'])) {
    $_SESSION[$module]['Fo']['Urheber']['fm_id'] = $fm_id = $_GET['ID'];
} else {
    # $fm_id = "";
}
if (isset($_GET['fm_id'])) {
    $_SESSION[$module]['Fo']['Urheber']['fm_id'] = $fm_id = $_GET['fm_id'];
}
if (isset($_POST['fm_id'])) {
    $_SESSION[$module]['Fo']['Urheber']['fm_id'] = $fm_id = $_POST['fm_id'];
}

if (isset($_GET['ei_id'])) {
    $eignr = $_GET['ei_id'];
} else {
    $eignr = "";
}
if (!isset($fm_id) ) {
    $fm_id = $_SESSION[$module]['Fo']['Urheber']['fm_id'];
}

if ($phase == 99) {
    header('Location: VF_FO_List.php');
}

$java_script = $java_script_ref = $java_script_such = "";

if ($fm_id != 0) {
    $_SESSION[$module]['fm_id'] = $fm_id;
    $sql = "SELECT * FROM fh_urheber_n WHERE fm_id = '$fm_id' ";
    $return = SQL_QUERY($db, $sql);
    $row = mysqli_fetch_object($return);
    $eignr = $row->fm_eigner;
    VF_Displ_Eig($eignr);
    VF_Displ_Urheb_n($eignr);
    #var_dump($_SESSION[$module]);
}

$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------

Tabellen_Spalten_parms($db, $tabelle);
$eigner = $_SESSION['Eigner']['eig_eigner'];

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {

    if ($fm_id == 0) {

        $neu = array(
            'fm_id' => $fm_id,
            'fm_eigner' => "",
            'fm_urheber' => "",
            'fm_urh_kurzz' => "",
            'fm_typ' => 'F',
            'fm_uidaend' => "",
            'fm_aenddat' => ""
        );
    } else {
        $sql_be = "SELECT * FROM $tabelle WHERE `fm_id` = '" . $_SESSION[$module]['fm_id'] . "'  ORDER BY `fm_id` ASC";
        $return_be = SQL_QUERY($db, $sql_be);

        $neu = mysqli_fetch_array($return_be);
        mysqli_free_result($return_be);
    }
}

if ($phase == 1) {
    require "VF_FO_U_Edit_ph1.inc.php";
}
/*
if ($phase == 11) {
    require "VF_O_FO_Reo_AOrd.inc";
}
*/
$prot= True;
$header = "";
HTML_header('Foto Urheber', 'Änderungsdienst', $header, 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

echo "<script src='" . $path2ROOT . "login/common/javascript/VF_Z_E_AutoLoad.js'></script>";

switch ($phase) {
    case 0:
        require ('VF_FO_U_Edit_ph0.inc.php');
        break;
}

HTML_trailer();

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

    $fs_flnr = $row['fs_flnr'];
    $row['fs_flnr'] = "<a href='VF_FO_U_Ed_Su.php?ID=$fs_flnr'  >" . $fs_flnr . "</a>";

    # -------------------------------------------------------------------------------------------------------------------------
    return True;
} # Ende von Function modifyRow

?>