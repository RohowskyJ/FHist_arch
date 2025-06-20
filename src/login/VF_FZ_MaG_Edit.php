<?php

/**
 * Bearbeitung der Maschinengetriebenen Geräte eines Eigentümers
 *
 * @author Josef Rohowsky  neu 202024
 *
 * 1. Auswahl des Eigentümers
 * 2. Anzeige der Fahrzeuge
 *
 */
session_start();

const Module_Name = 'F_G';
$module = Module_Name;
$tabelle = '';

const Prefix = '';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = False; // Debug output Ein/Aus Schalter

require  $path2ROOT . 'login/common/BA_Funcs.lib.php' ;
require  $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php' ;
require  $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php' ;
require  $path2ROOT . 'login/common/BA_List_Funcs.lib.php' ;
require  $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php' ;
require  $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php' ;
require  $path2ROOT . 'login/common/VF_Const.lib.php' ;
require  $path2ROOT . 'login/common/VF_M_tab_creat.lib.php' ;

/**
 * 
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$Inc_Arr = array();
$Inc_Arr[] = "VF_FZ_MaG_Edit.php";

$flow_list = True;

$jq = $jqui = True;
$BA_AJA = True;
$header = "";
BA_HTML_header('Geräte- Verwaltung', $header, 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

initial_debug();

// ============================================================================================================
// Eingabenerfassung und defauls
// ============================================================================================================

$db = LinkDB('VFH'); // Connect zur Datenbank

// ============================================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================

if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

if (isset($_GET['ge_id'])) {
    $ge_id = $_GET['ge_id'];
} else {
    $ge_id = "";
}
if (isset($_GET['ge_id'])) {
    $ge_id = $_GET['ge_id'];
}

if ($phase == 99) {
    header('Location: VF_FZ_MaFG_List.php');
}

if ($ge_id !== "") {
    $_SESSION[$module]['ge_id'] = $ge_id;
} else {
    $ge_id = $_SESSION[$module]['ge_id'];
}
$java_script = $java_script_ref = $java_script_such = "";
$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------

$eignr = $_SESSION['Eigner']['eig_eigner'];
$tabelle_a = 'ma_geraet_' . $eignr;

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle_a);
$Tabellen_Spalten[] = 'sa_name';
$Tabellen_Spalten_COMMENT['sa_name'] = 'Ausgewählte Sammlung';

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {

    if ($_SESSION[$module]['ge_id'] == 0) {
        $neu = array(
            'ge_id' => 0,
            'ge_eignr' => "",
            "ge_invnr" => "",
            'ge_bezeich' => "",
            'ge_type' => "",
            'ge_leistg' => "0",
            "ge_lei_bed" => "",
            'ge_leinh' => "",
            "ge_herst" => "",
            "ge_baujahr" => "",
            'ge_indienst' => "",
            'ge_ausdienst' => "",
            "ge_komment" => "",
            "ge_gesgew" => "",
            "ge_mo_herst" => "",
            "ge_mo_typ" => "",
            "ge_mo_sernr" => "",
            "ge_mo_treibst" => "",
            "ge_mo_leistung" => "0",
            "ge_mo_leibed" => "",
            "ge_mo_leinh" => "",
            "ge_ag_herst" => "",
            "ge_ag_type" => "",
            "ge_ag_sernr" => "",
            "ge_ag_leistung" => "",
            "ge_ag_leibed" => "",
            "ge_ag_leinh" => "",
            'ge_foto_1' => "",
            'ge_komm_1' => "",
            'ge_foto_2' => "",
            'ge_komm_2' => "",
            'ge_foto_3' => "",
            'ge_komm_3' => "",
            'ge_foto_4' => "",
            'ge_komm_4' => "",
            'ge_foto_5' => "",
            'ge_komm_5' => "",
            'ge_foto_6' => "",
            'ge_komm_6' => "",
            'ge_foto_7' => "",
            'ge_komm_7' => "",
            'ge_foto_8' => "",
            'ge_komm_8' => "",
            'ge_foto_9' => "",
            'ge_komm_9' => "",
            'ge_foto_10' => "",
            'ge_komm_10' => "",
            'ge_sammlg' => "MA_G",
            "ge_g1_name" => "",
            "ge_g1_sernr" => "",
            "ge_g1_beschr" => "",
            "ge_g1_foto" => "",
            "ge_g2_name" => "",
            "ge_g2_sernr" => "",
            "ge_g2_beschr" => "",
            "ge_g2_foto" => "",
            "ge_g3_name" => "",
            "ge_g3_sernr" => "",
            "ge_g3_beschr" => "",
            "ge_g3_foto" => "",
            "ge_g4_name" => "",
            "ge_g4_sernr" => "",
            "ge_g4_beschr" => "",
            "ge_g4_foto" => "",
            "ge_g5_name" => "",
            "ge_g5_sernr" => "",
            "ge_g5_beschr" => "",
            "ge_g5_foto" => "",
            "ge_g6_name" => "",
            "ge_g6_sernr" => "",
            "ge_g6_beschr" => "",
            "ge_g6_foto" => "",
            "ge_g7_name" => "",
            "ge_g7_sernr" => "",
            "ge_g7_beschr" => "",
            "ge_g7_foto" => "",
            "ge_g8_name" => "",
            "ge_g8_sernr" => "",
            "ge_g8_beschr" => "",
            "ge_g8_foto" => "",
            "ge_g9_name" => "",
            "ge_g9_sernr" => "",
            "ge_g9_beschr" => "",
            "ge_g9_foto" => "",
            "ge_g10_name" => "",
            "ge_g10_sernr" => "",
            "ge_g10_beschr" => "",
            "ge_g10_foto" => "",
            "ge_fzg" => "",
            "ge_raum" => "",
            "ge_ort" => "",
            'ge_zustand' => "",
            "ge_pruef_id" => "",
            "ge_pruef_dat" => "",
            "ge_aenduid" => "",
            "ge_aenddat" => "",
            "sa_name"  => "Gerät"
        );
    } else {
       
        $sql_be = "SELECT * FROM $tabelle_a 
             INNER JOIN fh_sammlung ON $tabelle_a.ge_sammlg LIKE fh_sammlung.sa_sammlg
             WHERE `ge_id` = '" . $_SESSION[$module]['ge_id'] . "' ORDER BY `ge_id` ASC"; // INNER JOIN fh_sammlung ON $tabelle_a.ge_sammlg LIKE fh_sammlung.sa_sammlg
        
        $return_be = SQL_QUERY($db, $sql_be);
        
        $neu = mysqli_fetch_array($return_be);
        mysqli_free_result($return_be);
    }
    # print_r($neu);
}

if ($phase == 1) {

    
}


switch ($phase) {
    case 0:
        require ('VF_FZ_MaG_Edit_ph0.inc.php');
        break;
    case 1:
        require "VF_FZ_MaG_Edit_ph1.inc.php";
        break;
}

BA_HTML_trailer();?>