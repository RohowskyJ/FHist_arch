<?php

/**
 * Liste der Geräte eines Eigentümers, Wartung
 *
 * @author Josef Rohowsky  neu 2019
 *
 */
session_start();

const Module_Name = 'F_G';
$module = Module_Name;
$tabelle = 'fz_muskel';

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
require $path2ROOT . 'login/common/M_tab_creat.inc';

require $path2ROOT . 'login/common/const.inc';
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

/* ++++++++++ vars for Suchbgriffe ++++++++++++++++ */
$sb_s0 = $sb_s1 = $sb_s2 = $sb_s3 = $sb_s4 = $sb_s5 = "";

$a1sb = $a2sb = $a3sb = $a4b = 0;
$ak_s0 = $ak_1 = $ak_s2 = $al_s3 = "";

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
if (isset($_GET['fm_id'])) {
    $fm_id = $_GET['fm_id'];
}

if ($phase == 99) {
    header('Location: VF_1_FG_List_v3.php');
}
$java_script = $java_script_ref = $java_script_such = "";

if ($fm_id !== "") {
    $_SESSION[$module]['fm_id'] = $fm_id;
} else {
    $fm_id = $_SESSION[$module]['fm_id'];
}

$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!
$lowHeight = True; // Auswahl-- und Anzeige- Tabellen mit verschiedenen Höhen - je nach Record-Anzahl

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------

Tabellen_Spalten_parms($db, $tabelle);
$eignr = $_SESSION['Eigner']['eig_eigner'];
$tabelle_a = $tabelle . "_$eignr";

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
            'fm_leistung' => "0",
            "fm_lei_bed" => "",
            "fm_herst" => "",
            "fm_baujahr" => "",
            "fm_fgstnr" => "",
            'fm_indienst' => "",
            'fm_ausdienst' => "",
            "fm_zustand" => "",
            "fm_gew" => "0",
            "fm_zug" => "",
            'fm_foto_1' => "",
            'fm_komm_1' => "",
            'fm_foto_2' => "",
            'fm_komm_2' => "",
            'fm_foto_3' => "",
            'fm_komm_3' => "",
            'fm_foto_4' => "",
            'fm_komm_4' => '',
            "fm_suchbegr_1" => "0100",
            "fm_suchbegr_2" => "",
            "fm_suchbegr_3" => "",
            "fm_suchbegr_4" => "",
            "fm_suchbegr_5" => "",
            "fm_suchbegr_6" => "",
            'fm_sammlg' => "",
            "fm_uidaend" => "",
            "fm_aenddat" => ""
        );
    } else {
        $sql_be = "SELECT * FROM $tabelle_a WHERE `fm_id` = '" . $_SESSION[$module]['fm_id'] . "' ORDER BY `fm_id` ASC";
        $return_be = SQL_QUERY($db, $sql_be);

        $errno = mysqli_errno($db);
        if ($errno == "1146") {
            $neu = array(
                'fm_id' => "NeuItem",
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
                "fm_suchbegr_1" => "0100",
                "fm_suchbegr_2" => "",
                "fm_suchbegr_3" => "",
                "fm_suchbegr_4" => "",
                "fm_suchbegr_5" => "",
                "fm_suchbegr_6" => "",
                'fm_sammlg' => "",
                "fm_uidaend" => "",
                "fm_aenddat" => ""
            );
        } else {
            $neu = mysqli_fetch_array($return_be);
            mysqli_free_result($return_be);
        }
    }
}

if ($phase == 1) {
    require "VF_FM_MU_Edit_ph1.inc";
}

$jq = True;
$header = "";
HTML_header('Muskelbewegtes- Verwaltung', 'Änderungsdienst', $header, 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width


switch ($phase) {
    case 0:
        require ('VF_FM_MU_Edit_ph0.inc');
        break;
}
echo "<script type='text/javascript' src='VF_C_Suchbegr_Funcs.js'></script>";
# echo "<script type='text/javascript' src='common/javascript/netspeed.js'></script>";
echo "<script type='text/javascript' src='VF_C_AOrd_Funcs.js'></script>";
HTML_trailer();
?>