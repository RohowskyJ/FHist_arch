<?php
/**
 * Protokolle hochladen
 *
 * @author  Josef Rohowsky - neu 2019
 *
 *
 */
session_start();

# die SESSION am leben halten
const Module_Name = 'P_RO';
$module = Module_Name;

const Prefix = '';

$path2ROOT = "../";

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_F_tab_creat.lib.php';

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';

initial_debug();

$db = LinkDB("VFH");

// ============================================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================

if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

if (isset($_GET['fo_id'])) {
    $fo_id = $_GET['fo_id'];
} else {
    $fo_id = "";
}

if ($phase == 99) {
    header('Location: VF_P_RO_List.php');
}

$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!
$lowHeight = True; // Auswahl-- und Anzeige- Tabellen mit verschiedenen Höhen - je nach Record-Anzahl

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------
$pict_path = 'AOrd_Verz/1/01/01/';
# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {

    if ($fo_id == 0) {
        $neu['fo_id'] = $fo_id;
        $neu['pr_name'] = "";
    } else {
        echo "kein Neues Protokoll zum hochladen <br>";
        exit();
    }
}

$header = "";
BA_HTML_header('Protokoll hochladen', $header, 'Form', '80em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

switch ($phase) {
    case 0:
        require ('VF_P_RO_Edit_ph0.inc.php');
        break;
    case 1:
        require "VF_P_RO_Edit_ph1.inc.php";
        break;
}

BA_HTML_trailer();
?>