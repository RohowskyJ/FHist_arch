<?php

/**
 * Zeitungs- Liste, Wartung
 *
 * @author J.Rohowsky
 *
 *
 */
session_start();

# die SESSION am leben halten
const Module_Name = 'OEF';
$module = Module_Name;
$tabelle = 'zt_zeitungen';

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
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================

if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

if (isset($_GET['ID'])) {
    $zt_id = $_GET['ID'];
} else {
    $zt_id = "";
}
if (isset($_GET['zt_id'])) {
    $zt_id = $_GET['zt_id'];
}

if ($phase == 99) {
    header('Location: VF_O_ZT_List.php');
}

if ($zt_id !== "") {
    $_SESSION[$module]['zt_id'] = $zt_id;
} else {
    $zt_id = $_SESSION[$module]['zt_id'];
}
$java_script = $java_script_ref = $java_script_such = "";
$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------

Tabellen_Spalten_parms($db, $tabelle);

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {

    if ($zt_id == 0) {
        $neu = array(
            'zt_id' => 0,
            'zt_name' => "",
            "zt_herausg" => "",
            'zt_internet' => "",
            'zt_email' => "",
            'zt_daten' => "0",
            "zt_erstausgdat" => "",
            'zt_letztausgabe' => "",
            "zt_uidaend" => "",
            "zt_aenddat" => ""
        );
    } else {
        # $fw_id = ;
        $sql_be = "SELECT * FROM $tabelle WHERE zt_id = '$zt_id' ORDER BY `zt_id` ASC";
        $return_be = SQL_QUERY($db, $sql_be);

        $neu = mysqli_fetch_assoc($return_be);
    }
}

if ($phase == 1) {
    require "VF_O_ZT_Z_Edit_ph1.inc";
}

$header = "";
BA_HTML_header('Zeitungs- Verwaltung', $header, 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

switch ($phase) {
    case 0:
        require ('VF_O_ZT_Z_Edit_ph0.inc');
        break;
}

BA_HTML_trailer();
?>