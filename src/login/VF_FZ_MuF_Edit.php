<?php

/**
 * Liste der Geräte eines Eigentümers, Wartung
 *
 * @author Josef Rohowsky  neu 2019
 *
 */
session_start();

const Module_Name = 'F_M';
$module = Module_Name;
$tabelle = 'mu_fahrzeug_';

const Prefix = '';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = false; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_M_tab_creat.lib.php';
require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';

$flow_list = true;

$Inc_Arr = array();
$Inc_Arr[] = "VF_FZ_MuF_Edit.php";

$LinkDB_database  = '';
$db = LinkDB('VFH');

$jq = $jqui = true;
$BA_AJA = true;
# $js_upl = true;
$header = "";
BA_HTML_header('Muskelbewegte Fahrzeuge', $header, 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

initial_debug();

// ============================================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================

if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

if (isset($_GET['fm_id'])) {
    $fm_id = $_GET['fm_id'];
} else {
    $fm_id = "";
}
if (isset($_POST['fm_id'])) {
    $fm_id = $_POST['fm_id'];
}

if ($phase == 99) {
    header('Location: VF_FA_FM_List.php');
}

if ($fm_id != "") {
    $_SESSION[$module]['fm_id'] = $fm_id;
} else {
    $fm_id = $_SESSION[$module]['fm_id'];
}

$Edit_Funcs_FeldName = false; // Feldname der Tabelle wird nicht angezeigt !!
$lowHeight = true; // Auswahl-- und Anzeige- Tabellen mit verschiedenen Höhen - je nach Record-Anzahl

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------

$eignr = $_SESSION['Eigner']['eig_eigner'];

$tabelle_a = $tabelle . $eignr;

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle_a, );
$Tabellen_Spalten[] = 'sa_name';
$Tabellen_Spalten_COMMENT['sa_name'] = 'Ausgewählte Sammlung';
# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {

    if ($_SESSION[$module]['fm_id'] == 0) {
        $neu = array(
            'fm_id' => 0,
            'fm_eignr' => "",
            "fm_invnr" => "",
            'fm_bezeich' => "",
            "fm_komment" => "",
            'fm_type' => "",
            'fm_leistung' => "",
            "fm_lei_bed" => "",
            "fm_herst" => "",
            "fm_baujahr" => "",
            "fm_fgstnr" => "",
            'fm_indienst' => "",
            'fm_ausdienst' => "",
            "fm_zustand" => "",
            "fm_gew" => "",
            "fm_zug" => "",
            'fm_foto_1' => "",
            'fm_komm_1' => "",
            'fm_foto_2' => "",
            'fm_komm_2' => "",
            'fm_foto_3' => "",
            'fm_komm_3' => "",
            'fm_foto_4' => "",
            'fm_komm_4' => '',
            'fm_sammlg' => "MU_F",
            'sa_name' => 'Muskelbewegtes',
            "fm_uidaend" => "",
            "fm_aenddat" => ""
        );
    } else {
        $sql_be = "SELECT * FROM $tabelle_a \n
            LEFT JOIN fh_sammlung ON $tabelle_a.fm_sammlg = fh_sammlung.sa_sammlg \n
            LEFT JOIN fh_firmen   ON $tabelle_a.fm_herst = fh_firmen.fi_abk   \n
            WHERE `fm_id` = '" . $_SESSION[$module]['fm_id'] . "' ORDER BY `fm_id` ASC";

        $return_be = SQL_QUERY($db, $sql_be);

        $neu = mysqli_fetch_array($return_be);

        mysqli_free_result($return_be);
    }
}

if ($phase == 1) {
}

switch ($phase) {
    case 0:
        echo "<script type='text/javascript' src='" . $path2ROOT . "login/common/javascript/BA_Upload_Module.js' ></script>";
        require('VF_FZ_MuF_Edit_ph0.inc.php');
        break;
    case 1:
        require "VF_FZ_MuF_Edit_ph1.inc.php";
        break;
}

BA_HTML_trailer();
