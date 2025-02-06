<?php

/**
 * Liste der Geräte eines Eigentümers
 *
 * @author Josef Rohowsky  neu 2019 
 *
 * 1. Auswahl des Eigentümers
 * 2. Anzeige der Fahrzeuge
 *
 */
session_start();

const Module_Name = 'F_M';
$module = Module_Name;
$tabelle = 'mu_geraet';

const Prefix = '';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = False; // Debug output Ein/Aus Schalter

require  $path2ROOT . 'login/common/VF_M_tab_creat.lib.php' ;
require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';

$flow_list = True;

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

if (isset($_GET['mg_id'])) {
    $mg_id = $_GET['mg_id'];
} else {
    $mg_id = "";
}
if (isset($_GET['mg_id'])) {
    $mg_id = $_GET['mg_id'];
}

if ($phase == 99) {
    header('Location: VF_FM_List.php');
}

if ($mg_id !== "") {
    $_SESSION[$module]['mg_id'] = $mg_id;
} else {
    $mg_id = $_SESSION[$module]['mg_id'];
}

$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!

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

    if ($_SESSION[$module]['mg_id'] == 0) {
        $neu = array(
            'mg_id' => 0,
            'mg_eignr' => "",
            "mg_invnr" => "",
            'mg_bezeich' => "",
            'mg_type' => "",
            "mg_herst" => "",
            "mg_baujahr" => "",
            'mg_fgstnr' => "",
            'mg_indienst' => "",
            'mg_ausdienst' => "",
            'mg_zustand' => '',
            'mg_gew' => '',
            "mg_komment" => "",
            'mg_foto_1' => "",
            'mg_komm_1' => "",
            'mg_foto_2' => "",
            'mg_komm_2' => "",
            'mg_foto_3' => "",
            'mg_komm_3' => "",
            'mg_foto_4' => "",
            'mg_komm_4' => "",
            'mg_sammlg' => "MU_G",
            "mg_fzg" => "",
            "mg_raum" => "",
            "mg_ort" => "",
            "mg_pruef_id" => "",
            "mg_pruef_dat" => "",
            "mg_uidaend" => "",
            "mg_aenddat" => ""
        );
    } else {

        $sql_be = "SELECT *
        FROM $tabelle_a
        INNER JOIN fh_sammlung ON $tabelle_a.mg_sammlg LIKE fh_sammlung.sa_sammlg
        WHERE `mg_id` = '" . $_SESSION[$module]['mg_id'] . "' ORDER BY `mg_id` ASC"; // INNER JOIN fh_sammlung ON $tabelle_a.mg_sammlg = fh_sammlung.sa_sammlg
        
        $return_be = SQL_QUERY($db, $sql_be);
        
        $neu = mysqli_fetch_array($return_be);
        mysqli_free_result($return_be);

    }
    # print_r($neu);
}

if ($phase == 1) {

}

$prot = True;
$header = "";
BA_HTML_header('Geräte- Verwaltung',  $header, 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

switch ($phase) {
    case 0:
        require ('VF_FM_GE_Edit_ph0.inc.php');
        break;
    case 1:
        require "VF_FM_GE_Edit_ph1.inc.php";
        break;
}

BA_HTML_trailer();?>