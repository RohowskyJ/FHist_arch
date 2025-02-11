<?php

/**
 * Zeitungs- Index Liste, Wartung
 *
 * @author J.Rohowsky
 *
 *
 */
session_start();

# die SESSION am leben halten
const Module_Name = 'OEF';
$module = Module_Name;
$tabelle = 'zt_inhalt';

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
# require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';
require $path2ROOT . 'login/common/VF_F_tab_creat.lib.php';

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

if (isset($_GET['ih_id'])) {
    $ih_id = $_GET['ih_id'];
} else {
    $ih_id = "";
}
if (isset($_GET['ih_id'])) {
    $ih_id = $_GET['ih_id'];
}

if ($phase == 99) {
    header('Location: VF_O_ZT_List.php');
}

$zt_id = $_SESSION[$module]['zt_id'];

$tabelle .= "_" . $zt_id;
$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------

Tabellen_Spalten_parms($db, $tabelle);

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {

    if ($ih_id == 0) {
        $neu = array(
            'ih_id' => 0,
            'ih_zt_id' => $zt_id,
            "ih_jahrgang" => "",
            'ih_jahr' => "",
            'ih_nr' => "",
            'ih_kateg' => "",
            "ih_sg" => "",
            'ih_ssg' => "",
            "ih_gruppe" => "",
            "ih_titel" => "",
            'ih_titelerw' => "",
            'ih_autor' => "",
            "ih_email" => "",
            "ih_tel" => "",
            "ih_fax" => "",
            "ih_seite" => "",
            "ih_spalte" => "",
            "ih_fwehr" => "",
            "ih_uidaend" => "",
            "ih_aenddat" => ""
        );
    } else {
        $sql_be = "SELECT * FROM $tabelle ORDER BY `ih_id` ASC";
        $return_be = SQL_QUERY($db, $sql_be);

        $neu = mysqli_fetch_array($return_be);
        mysqli_free_result($return_be);
    }
    # print_r($neu);
}

if ($phase == 1) {
    foreach ($_POST as $name => $value) {
        $neu[$name] = mysqli_real_escape_string($db, $value);
    }
}

$header = "";
HTML_header('Zeitungs- Inhalts- Verwaltung', 'Änderungsdienst', $header, 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

switch ($phase) {
    case 0:
        require ('VF_O_ZT_I_Edit_ph0.inc.php');
        break;
    case 1:
        require "VF_O_ZT_I_Edit_ph1.inc.php";
        break;
}

HTML_trailer();
?>