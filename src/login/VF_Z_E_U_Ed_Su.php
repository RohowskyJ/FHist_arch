<?php

/**
 * Foto Urheber, Details, Wartung
 * 
 * @author Josef Rohowsky - neu 2023
 * 
 * 
 */
session_start();

const Module_Name = 'OEF';
$module = Module_Name;
$tabelle = 'fh_eign_urh';

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
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';

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
    $fs_flnr = $_GET['ID'];
} else {
    $fs_flnr = "NeuItem";
}
if (isset($_GET['us'])) {
    $us = $_GET['us'];
} else {
    $us = "K";
}
if (isset($_GET['fs_flnr'])) {
    $fs_flnr = $_GET['fs_flnr'];
}

if ($phase == 99) {
    header('Location: VF_FO_Z_E_List.php');
}
$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------

$p_uid = $_SESSION['VF_Prim']['p_uid'];

Tabellen_Spalten_parms($db, $tabelle);

$Err_Msg = $Err_Urheb = "";
$Err_Nr = 0;
# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {

    if ($fs_flnr == 0) {
        $neu['fs_flnr'] = 0;
        $neu['fs_eigner'] = $_SESSION[$module]['URHEBER']['fm_eigner'];
        $neu['fs_typ'] = $_SESSION[$module]['URHEBER']['fm_typ'];
        $neu['fs_fotograf'] = $_SESSION[$module]['URHEBER']['fm_urheber'];
        $neu['fs_urh_kurzz'] = "";
        $neu['fs_urh_nr'] = "";
        $neu['fs_urh_verzeich'] = "";
        $Neu['fs_anz_verze'] = 0;
        $neu['fs_anz_dateien'] = 0;
        
        $neu['fs_uidaend'] = $neu['fs_aenddat'] = "";
    } else {
        $sql = "SELECT * FROM $tabelle WHERE fs_flnr= '$fs_flnr' ";

        $result = SQL_QUERY($db, $sql);

        $num_rows = mysqli_num_rows($result);

        $neu = mysqli_fetch_array($result);

        if ($debug) {
            echo '<pre class=debug>';
            echo '<hr>$neu: ';
            print_r($neu);
            echo '</pre>';
        }
    }
}

if ($phase == 1) {

    foreach ($_POST as $name => $value) {
        $neu[$name] = mysqli_real_escape_string($db, $value);
    }

    if ($debug) {
        echo '<pre class=debug>';
        echo 'L 090: <hr>\$neu: ';
        print_r($neu);
        echo "<br> files ";
        print_r($_FILES);
        echo '</pre>';
    }

    if ($Err_Nr > 0) {
        $phase = 0;
    }
}

HTML_header('Urheber', '', '', 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

switch ($phase) {
    case 0:
        require ('VF_Z_E_U_Ed_Su_ph0.inc.php');
        break;
    case 1:
        require ('VF_Z_E_U_Ed_Su_ph1.inc.php');
        break;
}
HTML_trailer();
?>